<?php

namespace App\Controller;

use \App\Entity\Source;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SourceController extends AbstractController
{
    /**
     * @Route("/source", name="source")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Source::class);

        $sources = $repository->getSimpleList();

        return $this->render('source/index.html.twig', [
            'controller_name' => 'SourceController',
            'sources' => $sources
        ]);
    }

    /**
     * @Route("/source/{id}", name="source_edit")
     */
    public function edit(Source $source){
        return $this->render('');
    }
}
