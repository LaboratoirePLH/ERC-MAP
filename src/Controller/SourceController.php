<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SourceController extends AbstractController
{
    /**
     * @Route("/source", name="source")
     */
    public function index()
    {
        return $this->render('source/index.html.twig', [
            'controller_name' => 'SourceController',
        ]);
    }
}
