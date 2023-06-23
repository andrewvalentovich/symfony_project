<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VotingController extends AbstractController
{
    /**
     * @Route("/articles/{slug}/vote/{type<up|down>}", name="app_voting", methods={"POST"})
     */
    public function vote(Article $article, $slug, $type, EntityManagerInterface $em)
    {
        if ($type === "up") {
            $article->setVoteUp();
        } elseif ($type === "down") {
            $article->setVoteDown();
        }

        $em->flush();

        return $this->json(['votes' => $article->getVoteCount(), 'slug' => $slug]);
    }
}
