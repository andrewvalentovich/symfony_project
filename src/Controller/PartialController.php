<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PartialController extends AbstractController
{
    public function lastComments(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findLastOrderByCreatedAt(3);

        return $this->render('partial/last_comments.html.twig', [
            "comments"      => $comments,
        ]);
    }
}
