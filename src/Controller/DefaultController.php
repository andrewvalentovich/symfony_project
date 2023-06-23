<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
    * @Route("/", name="app_homepage")
    */
    public function homepage(ArticleRepository $repository)
    {
        $articles = $repository->getPublishedLatest();

        return $this->render('default/homepage.html.twig', [
            "articles"      => $articles,
        ]);
    }
}