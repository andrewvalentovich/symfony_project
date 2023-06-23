<?php

namespace App\Controller\Api;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
