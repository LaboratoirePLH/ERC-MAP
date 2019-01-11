<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ElementController extends AbstractController
{
    /**
     * @Route("/element", name="element")
     */
    public function index()
    {
        return $this->render('element/index.html.twig', [
            'controller_name' => 'ElementController',
        ]);
    }
}
