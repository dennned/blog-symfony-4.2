<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        return $this->render('page/list.html.twig', [
            'pages' => $pages,
        ]);
    }
}
