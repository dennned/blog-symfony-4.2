<?php

namespace App\Controller;

use App\Entity\Term;
use App\Form\TermDeleteFormType;
use App\Form\TermFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class TermController extends AbstractController
{
    /**
     * @Route("/term", name="term")
     */
    public function listAction()
    {
        $repoTerms = $this->getDoctrine()->getRepository(Term::class);
        $terms = $repoTerms->findAll();

        return $this->render('@term/list.html.twig', [
            'terms' => $terms,
        ]);
    }

    /**
     * @Route("/term/add", name="term_add")
     *
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $term = new Term();
        $form = $this->createForm(TermFormType::class, $term);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($term);
            $em->flush();

            return $this->redirectToRoute('term');
        }

        return $this->render('@term/form/add.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/term/{id}", name="term_view", requirements={"term"="\d+"})
     *
     * @param int $id
     * @return Response
     */
    public function viewAction(int $id)
    {
        $repoTerm = $this->getDoctrine()->getRepository(Term::class);
        $term = $repoTerm->find($id);

        if(!$term) {
            throw $this->createNotFoundException('Term is not found');
        }

        return $this->render('@term/view.html.twig', [
            'term' => $term
        ]);
    }

    /**
     * @Route("/term/{id}/edit", name="term_edit", requirements={"term"="\d+"})
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, int $id)
    {
        $termRepo = $this->getDoctrine()->getRepository(Term::class);
        $term = $termRepo->find($id);

        if(!$term) {
            throw $this->createNotFoundException('Term is not found');
        }

        $form = $this->createForm(TermFormType::class, $term);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($term);
            $em->flush();

            return $this->redirectToRoute('term');
        }

        return $this->render('@term/form/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/term/{id}/delete", name="term_delete", requirements={"term"="\d+"})
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function removeAction(Request $request, int $id)
    {
        $termRepo = $this->getDoctrine()->getRepository(Term::class);
        $term = $termRepo->find($id);

        if(!$term) {
            throw $this->createNotFoundException('Term is not found');
        }

        $form = $this->createForm(TermDeleteFormType::class, $term);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($term);
            $em->flush();

            return $this->redirectToRoute('term');
        }

        return $this->render('@term/form/delete.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
