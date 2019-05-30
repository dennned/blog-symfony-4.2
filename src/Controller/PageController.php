<?php

namespace App\Controller;

use App\Entity\Page;
use App\Form\PageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/page", name="page")
     */
    public function listAction()
    {
        $repoPages = $this->getDoctrine()->getRepository(Page::class);
        $pages = $repoPages->findAll();

        return $this->render('@page/list.html.twig', [
            'pages' => $pages,
        ]);
    }

    /**
     * @Route("/page/add", name="page_add")
     *
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $page = new Page();
        $form = $this->createForm(PageFormType::class, $page);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($page->getCategory());
        }

        return $this->render('@page/form/add.html.twig', [
            'form' => $form->createView()
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
