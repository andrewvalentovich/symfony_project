<?php



namespace App\Controller;
use App\Homework\ArticleContentProvider;
use App\Homework\ArticleProvider;
use App\Service\MarkdownParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{id}", name="app_article_page")
     */
    public function article_page(Environment $twig, ArticleProvider $articleProvider, $id, MarkdownParser $markdownParser, ArticleContentProvider $articleContentProvider)
    {

        $wordArray = ['я', 'Андрей', 'ты', 'человек', 'самый', 'умный', 'вид', 'животных'];

        $word = NULL;
        $wordCount = 0;

        if(rand(0, 10) <= 7) {
            $word = $wordArray[rand(0, 7)];
            $wordCount = rand(2, 12);
        }

        $contentText = $articleContentProvider->get(rand(2, 10), $word, $wordCount);
        $contentText = $markdownParser->parse($contentText);

        return $this->render('article/article.html.twig', [
            'article'       =>      $articleProvider->article(),
            'content'       =>      $contentText
        ]);
    }


    /**
     * @Route("/api/v1/article_content/", name="article_content", methods={"POST"})
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