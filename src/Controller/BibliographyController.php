<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BibliographyController extends AbstractController
{
    /**
     * @Route("/bibliography", name="bibliography")
     */
    public function index()
    {
        return $this->render('bibliography/index.html.twig', [
            'controller_name' => 'BibliographyController',
        ]);
    }
}
