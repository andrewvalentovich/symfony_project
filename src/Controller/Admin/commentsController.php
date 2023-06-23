<?php

namespace App\Controller\Admin;

use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class commentsController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="app_admin_comments")
     * @IsGranted("ROLE_ADMIN_COMMENT")
     */

    public function index(Request $request, CommentRepository $commentRepository, PaginatorInterface $paginator)
    {
        $countView = ($request->query->get('countView') == 'noValue') ? 20 : $request->query->get('countView');

        $pagination = $paginator->paginate(
            $commentRepository->findAllWithSearchAndSoftDelNoQuery(
                $request->query->get('q'),
                $request->query->has('showDeleted')
            ), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $countView /*limit per page*/
        );


        return $this->render('admin/comments/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
