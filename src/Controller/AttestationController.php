<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AttestationController extends AbstractController
{
    /**
     * @Route("/attestation", name="attestation")
     */
    public function index()
    {
        return $this->render('attestation/index.html.twig', [
            'controller_name' => 'AttestationController',
        ]);
    }
}
