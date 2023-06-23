<?php



namespace App\Controller;
use App\Homework\ArticleContentProvider;
use App\Homework\ArticleProvider;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{slug}", name="app_article_page")
     */
    public function article_page($slug, ArticleRepository $repository)
    {
        return $this->render('article/article.html.twig', [
            'article'       =>      $repository->findOneBy(array('slug' => $slug))
        ]);
    }

    /**
     * @Route("/api/v1/article_content/", name="app_article_content", methods={"POST"})
     *
     * @param Request $request
     * @param ArticleContentProvider $articleContentProvider
     *
     * @return JsonResponse
     */
    public function articleContent(Request $request, ArticleContentProvider $articleContentProvider):JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $text = $articleContentProvider->get($data['paragraphs'], $data['word'], $data['wordCount']);
        return new JsonResponse(['text' => $text]);
    }
}