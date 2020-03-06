<?php

namespace App\Controller;

use App\Entity\Element;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MaintenanceController extends AbstractController
{
    /**
     * @Route("/maintenance", name="maintenance")
     */
    public function index()
    {
        return $this->render('maintenance/index.html.twig', [
            'controller_name' => 'MaintenanceController',
        ]);
    }

    /**
     * @Route("/maintenance/beta_codes", name="maintenance_beta_codes")
     */
    public function betaCodes()
    {
        $query = $this->getDoctrine()->getManager()->createQuery(
            "SELECT partial e.{id, etatAbsolu, betaCode} FROM \App\Entity\Element e"
        );
        $elements = $query->getArrayResult();
        $betaCodes = array_filter($elements, function($el){
            return preg_match("/[^A-Z, \/]/i", $el['betaCode']);
        });
        return $this->render('maintenance/index.html.twig', [
            'controller_name' => 'MaintenanceController',
            'beta_codes'      => $betaCodes
        ]);
    }
}
