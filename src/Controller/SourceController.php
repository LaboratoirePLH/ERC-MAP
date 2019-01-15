<?php

namespace App\Controller;

use \App\Entity\Source;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SourceController extends AbstractController
{
    /**
     * @Route("/source", name="source_list")
     */
    public function index()
    {
        $sources = $this->getDoctrine()
                        ->getRepository(Source::class)
                        ->getSimpleList();

        return $this->render('source/index.html.twig', [
            'controller_name' => 'SourceController',
            'sources' => $sources
        ]);
    }

    /**
     * @Route("/source/create", name="source_create")
     */
    public function create(){
        return $this->render('source/edit.html.twig', [
            'controller_name' => 'SourceController',
            'action' => 'create'
        ]);
    }

    /**
     * @Route("/source/{id}/edit", name="source_edit")
     */
    public function edit($id){
        $source = $this->getDoctrine()
                       ->getRepository(Source::class)
                       ->getRecord($id);

        return $this->render('source/edit.html.twig', [
            'controller_name' => 'SourceController',
            'action' => 'edit',
            'source' => $source
        ]);
    }

    /**
     * @Route("/source/{id}/delete", name="source_delete")
     */
    public function delete($id){
        echo "ok";
    }
}
