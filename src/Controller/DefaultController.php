<?php


namespace App\Controller;
use App\Homework\ArticleProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class DefaultController extends AbstractController
{
    /**
    * @Route("/", name="app_homepage")
    */
    public function homepage(Environment $twig, ArticleProvider $articleProvider)
    {
        return $this->render('default/homepage.html.twig', [
            "articles"      => $articleProvider->articles(),
        ]);
    }
}