<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Page;
use App\Form\PageDeleteFormType;
use App\Form\PageFormType;
use App\Form\CommentFormType;
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('page');
        }

        return $this->render('@page/form/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/page/{id}", name="page_view", requirements={"id"="\d+"})
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function viewAction(int $id, Request $request)
    {
        $repoPages = $this->getDoctrine()->getRepository(Page::class);
        $page = $repoPages->find($id);

        if (!$page) {
            throw $this->createNotFoundException('Page is not found!');
        }

        $commentForm = $this->createForm(CommentFormType::class);
        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted()) {
            /** @var Comment $comment */
            $comment = $commentForm->getData();
            $comment->addPage($page);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('page_view', [
                'id' => $page->getId()
            ]);
        }

        return $this->render('@page/view.html.twig', [
            'page' => $page,
            'comment_form' => $commentForm->createView(),
        ]);
    }

    /**
     * @Route("/page/{id}/edit", name="page_edit", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editAction(Request $request, int $id)
    {
        $repoPages = $this->getDoctrine()->getRepository(Page::class);
        $page = $repoPages->find($id);

        if(!$page) {
            throw $this->createNotFoundException('Page is not found!');
        }

        $form = $this->createForm(PageFormType::class, $page);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('page');
        }

        return $this->render('@page/form/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("page/{id}/delete", name="page_delete")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function removeAction(Request $request, int $id)
    {
        $repoPages = $this->getDoctrine()->getRepository(Page::class);
        $page = $repoPages->find($id);

        if(!$page) {
            throw $this->createNotFoundException('Page is not found!');
        }

        $form = $this->createForm(PageDeleteFormType::class, $page);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($page);
            $em->flush();

            return $this->redirectToRoute('page');
        }

        return $this->render('@page/form/delete.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
