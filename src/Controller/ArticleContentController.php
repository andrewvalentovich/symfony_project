<?php

namespace App\Controller;

use App\Homework\ArticleContentProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleContentController extends AbstractController
{

    /**
     * @Route("/articles/article_content/", name="app_article_content_generator", methods={"GET"})
     */

    public function index(ArticleContentProvider $articleContentProvider, Request $request)
    {
        $content = $articleContentProvider->get(
            (int)$request->query->get('paragraphs'),
            $request->query->get('word'),
            (int)$request->query->get('wordCount')
        );


        return $this->render('article_content/index.html.twig', [
            'content' => $content,
        ]);
    }
}
