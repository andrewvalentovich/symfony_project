<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Homework\ArticleWordsFilter;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function create(EntityManagerInterface $em, Request $request, ArticleWordsFilter $filter)
    {
        $form = $this->createForm(ArticleFormType::class);

        $filenameArray = [
            'article-1.jpeg',
            'article-2.jpeg',
            'article-3.jpg'
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Article $article */
            $article = $form->getData();
            $article->setImageFilename($filenameArray[rand(0, 2)]);

            $words = ['стакан', 'жук', 'точка'];

            $title = $article->getTitle();
            $body = $article->getBody();

            $article->setTitle($filter->filter($title, $words));
            $article->setBody($filter->filter($body, $words));

            $em->persist($article);
            $em->flush();

            $this->addFlash('flash_message', 'Статья успешно создана');

            return $this->redirectToRoute('app_admin_articles');
        }


        return $this->render('admin/articles/create.html.twig', [
            'articleForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/articles/{id}/edit", name="app_admin_articles_edit")
     * @IsGranted("VOTER_ARTICLE_EDIT", subject="article")
     */
    public function edit(Article $article)
    {
        return new Response('Страница редактирования статьи '. $article->getTitle());
    }
}
