<?php

namespace App\Controller\Api;

use App\Entity\Article;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/api/v1/user", name="api_user")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index(LoggerInterface $apiLogger, Request $request): Response
    {
        $user = $this->getUser();

        $apiLogger->info("Logger info", [
            'username'  =>  $user->getUserIdentifier(),
            'token'     =>  $request->attributes->get("_auth_token"),
            'route'     =>  $request->attributes->get("_route"),
            'url'       =>  $request->getUri()
        ]);

        return $this->json($user, 200, [], ['groups' => ['main']]);
    }

    /**
     * @Route("/api/v1/user/{id}", name="api_user_id")
     * @IsGranted("VOTER_ARTICLE_API", subject="article")
     */
    public function find(Article $article)
    {
        $response = $this->json($article, 200, [], ['groups' => ['api']]);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $response;
    }
}
