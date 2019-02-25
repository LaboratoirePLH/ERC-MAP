<?php

namespace App\Controller;

use App\Entity\Materiau;
use App\Entity\SousRegion;
use App\Entity\TypeSource;
use App\Entity\TypeSupport;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    private function _fetchDataForCategory($entityClass, $parentField, $parentValue, $locale)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository($entityClass);
        $rows = $repo->createQueryBuilder("e")
            ->where("e.$parentField = :id")
            ->setParameter("id", $parentValue)
            ->getQuery()
            ->getResult();

        $responseArray = array();
        foreach($rows as $row){
            $responseArray[] = array(
                "id" => $row->getId(),
                "name" => $row->getNom($locale)
            );
        }

        // Return array with structure of the neighborhoods of the providen city id
        return new JsonResponse($responseArray);
    }

    /**
     * @Route("/data/materiau", name="data_materiau")
     */
    public function materiau(Request $request)
    {
        return $this->_fetchDataForCategory(
            Materiau::class,
            'categorieMateriau',
            $request->query->get("parentId"),
            $request->getLocale()
        );
    }

    /**
     * @Route("/data/type_source", name="data_type_source")
     */
    public function typeSource(Request $request)
    {
        return $this->_fetchDataForCategory(
            TypeSource::class,
            'categorieSource',
            $request->query->get("parentId"),
            $request->getLocale()
        );
    }

    /**
     * @Route("/data/type_support", name="data_type_support")
     */
    public function typeSupport(Request $request)
    {
        return $this->_fetchDataForCategory(
            TypeSupport::class,
            'categorieSupport',
            $request->query->get("parentId"),
            $request->getLocale()
        );
    }

    /**
     * @Route("/data/sous_region", name="data_sous_region")
     */
    public function sousRegion(Request $request)
    {
        return $this->_fetchDataForCategory(
            SousRegion::class,
            'grandeRegion',
            $request->query->get("parentId"),
            $request->getLocale()
        );
    }
}
