<?php

namespace App\Controller\Admin;

use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class tagsController extends AbstractController
{
    /**
     * @Route("/admin/tags", name="app_admin_tags")
     * @IsGranted("ROLE_ADMIN_TAG")
     */
    public function index(Request $request, TagRepository $tagRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $tagRepository->findAllWithSoftDelNoResult(
                $request->query->get('q'),
                $request->query->has('showDeleted')
            ), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('countView', 20) /*limit per page*/
        );

        return $this->render('admin/tags/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
