<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/admin/articles/create", name="app_admin_articles_create")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function create()
    {
        return new Response('Здесь будет страница создания статьи');
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
