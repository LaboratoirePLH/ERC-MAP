<?php

namespace App\Controller;

use App\Entity\Element;
use App\Utils\StringHelper;

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
        $betaCodes = [];
        foreach($elements as $el){
            if(is_null($el['betaCode'])){
                continue;
            }
            if(preg_match("/[^A-Z, \/\*]/i", $el['betaCode'])){
                $el['reason'] = 'maintenance.messages.invalid_char';
                $betaCodes[] = $el;
            }

            // Use mb_strlen instead of strlen to compare lengths
            // because strlen("<greek letter>") = 2
            // and mb_strlen("<greek letter>") = 1
            if(mb_strlen(StringHelper::removeAccents(strip_tags($el['betaCode'] ?? '')))
                != mb_strlen(StringHelper::removeAccents(strip_tags($el['etatAbsolu'] ?? ''))))
            {
                $el['reason'] = 'maintenance.messages.invalid_length';
                $betaCodes[] = $el;
            }
        }
        return $this->render('maintenance/index.html.twig', [
            'controller_name' => 'MaintenanceController',
            'beta_codes'      => $betaCodes
        ]);
    }
}
