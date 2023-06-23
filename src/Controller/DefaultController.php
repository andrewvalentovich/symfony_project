<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
    * @Route("/", name="app_homepage")
    */
    public function homepage(ArticleRepository $articleRepository, CommentRepository $commentRepository)
    {
        $articles = $articleRepository->getPublishedLatest();
        $comments = $commentRepository->findLastOrderByCreatedAt(3);

        return $this->render('default/homepage.html.twig', [
            "articles"      => $articles,
            "comments"      => $comments,
        ]);
    }
}