<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Events\ArticleCreatedEvent;
use App\Form\ArticleFormType;
use App\Homework\ArticleWordsFilter;
use App\Repository\ArticleRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ArticlesController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     * @Route("/admin/articles", name="app_admin_articles")
     */
    public function index(ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator)
    {
        $pagination = $paginator->paginate(
            $articleRepository->findAllWithSoftDelNoResult(
                $request->query->get('q')
            ), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('countView', 20) /*limit per page*/
        );

        return $this->render('admin/articles/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/articles/create", name="app_admin_articles_create")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function create(
        EntityManagerInterface $em,
        Request $request,
        ArticleWordsFilter $filter,
        FileUploader $articleFileUploader,
        EventDispatcherInterface $dispatcher
    ) {
        $form = $this->createForm(ArticleFormType::class);

        $filenameArray = [
            'images/article-1.jpeg',
            'images/article-2.jpeg',
            'images/article-3.jpg'
        ];


        if ($article = $this->handleFormRequest(
            $em,
            $request,
            $form,
            $articleFileUploader,
            $filter,
            $dispatcher,
            true
        )) {
            $this->addFlash('flash_message', 'Статья успешно создана');
            return $this->redirectToRoute('app_admin_articles');
        }

        return $this->render('admin/articles/create.html.twig', [
            'articleForm' => $form->createView(),
            'showError' =>  $form->isSubmitted(),

        ]);
    }

    /**
     * @Route("/admin/articles/{id}/edit", name="app_admin_articles_edit")
     * @IsGranted("VOTER_ARTICLE_EDIT", subject="article")
     */
    public function edit(
        EntityManagerInterface $em,
        Request $request,
        Article $article,
        FileUploader $articleFileUploader
    ) {
        $form = $this->createForm(ArticleFormType::class, $article);

        if ($article = $this->handleFormRequest($em, $request, $form, $articleFileUploader)) {
            $this->addFlash('flash_message', 'Статья успешно изменена');
            return $this->redirectToRoute('app_admin_articles_edit', ['id'  =>  $article->getId()]);
        }

        return $this->render('admin/articles/edit.html.twig', [
            'articleForm' => $form->createView(),
            'showError' =>  $form->isSubmitted(),
        ]);
    }

    private function handleFormRequest(
        EntityManagerInterface $em,
        Request $request,
        FormInterface $form,
        FileUploader $fileUploader,
        ArticleWordsFilter $filter = null,
        EventDispatcherInterface $dispatcher = null,
        bool $articleFilter = false
    ) {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Article $article */
            $article = $form->getData();

            /** @var UploadedFile|null $image */
            $image = $form->get('image')->getData();
            if ($image) {
                $article->setImageFilename($fileUploader->uploadFile($image, $article->getImageFilename()));
            }

            if ($articleFilter) {
                $words = ['стакан', 'жук', 'точка'];
                $title = $article->getTitle();
                $body = $article->getBody();
                $article->setTitle($filter->filter($title, $words));
                $article->setBody($filter->filter($body, $words));
            }

            $em->persist($article);
            $em->flush();

            if ($dispatcher !== null) {
                $dispatcher->dispatch(new ArticleCreatedEvent($article));
            }

            return $article;
        }

        return null;
    }
}
