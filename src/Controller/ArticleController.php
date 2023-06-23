<?php



namespace App\Controller;
use App\Homework\ArticleContentProvider;
use App\Homework\ArticleProvider;
use App\Repository\ArticleRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    /**
     * GetUser()
     */

    /**
     * @var Security
     */
    private $security;
    /**
     * @var LoggerInterface
     */
    private $apiLogger;

    public function __construct(Security $security, LoggerInterface $apiLogger)
    {
        $this->security = $security;
        $this->apiLogger = $apiLogger;
    }

    /**
     * @Route("/article/{slug}", name="app_article_page")
     */
    public function article_page($slug, ArticleRepository $repository)
    {
        $article = $repository->findOneBy(array('slug' => $slug));
        $comments = $article->getComments();

        return $this->render('article/article.html.twig', [
            'article'       =>      $article,
            'comments'      =>      $comments,
        ]);
    }

    /**
     * @Route("/api/v1/article_content/", name="app_article_content", methods={"GET"})
     *
     * @param Request $request
     * @param ArticleContentProvider $articleContentProvider
     *
     * @return JsonResponse
     */
    public function articleContent(Request $request, ArticleContentProvider $articleContentProvider):JsonResponse
    {
        if (! in_array("ROLE_API", $this->security->getUser()->getRoles()) ) {
            $this->apiLogger->warning("Пользователь ".$this->security->getUser()->getUserIdentifier()." не имеет доступа ROLE_API");
        }


        $data = $request->query->all();
        $text = $articleContentProvider->get($data['paragraphs'], $data['word'], $data['wordCount']);
        return new JsonResponse(['text' => $text]);
    }
}