<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/page", name="page")
     */
    public function index()
    {
        $repoPages = $this->getDoctrine()->getRepository(Page::class);
        $pages = $repoPages->findAll();

        return $this->render('@page/list.html.twig', [
            'pages' => $pages,
        ]);
    }

    /**
     * @Route("/page/{id}", name="page_view")
     *
     * @param int $id
     * @return Response
     */
    public function viewAction(int $id)
    {
        $repoPages = $this->getDoctrine()->getRepository(Page::class);
        $page = $repoPages->find($id);

        if (!$page) {
            throw $this->createNotFoundException('Page is not found!');
        }
        return $this->render('@page/view.html.twig', [
            'page' => $page,
        ]);
    }
}
