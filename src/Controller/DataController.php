<?php

namespace App\Controller;

use App\Entity\Localisation;
use App\Entity\Materiau;
use App\Entity\Materiel;
use App\Entity\Occasion;
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
        if(empty($parentValue)){
            return new JsonResponse(['message' => 'Missing parameters'], 400);
        }
        $repo = $this->getDoctrine()->getManager()->getRepository($entityClass);
        $rows = $repo->createQueryBuilder("e")
            ->where("e.$parentField = :id")
            ->setParameter("id", $parentValue)
            ->orderBy('e.nom'.ucfirst($locale), 'ASC')
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
     * @Route("/data/materiel", name="data_materiel")
     */
    public function materiel(Request $request)
    {
        return $this->_fetchDataForCategory(
            Materiel::class,
            'categorieMateriel',
            $request->query->get("parentId"),
            $request->getLocale()
        );
    }

    /**
     * @Route("/data/type_occasion", name="data_type_occasion")
     */
    public function typeOccasion(Request $request)
    {
        return $this->_fetchDataForCategory(
            Occasion::class,
            'categorieOccasion',
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

    /**
     * @Route("/data/city_search", name="city_search")
     */
    public function citySearch(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Localisation::class);
        $query = $repo->createQueryBuilder("e");

        if($request->query->has('pleiades'))
        {
            $query = $query->where("e.pleiadesVille = :number")
                        ->setParameter("number", $request->query->get('pleiades'));
        }
        else if($request->query->has('city'))
        {
            $query = $query->where("lower(e.nomVille) = lower(:nomville)")
                        ->setParameter("nomville", $request->query->get('city'));
        }
        else
        {
            return new JsonResponse(['message' => 'Missing parameters'], 400);
        }
        $result = $query->getQuery()->getResult();
        $data = [];
        foreach($result as $row){
            $data[] = [
                "granderegion" => $row->getGrandeRegion() ? $row->getGrandeRegion()->getId() : "",
                "sousregion" => $row->getSousRegion() ? $row->getSousRegion()->getId() : "",
                "id" => $row->getPleiadesVille() ?? "",
                "nom" => $row->getNomVille() ?? "",
                "latitude" => $row->getLatitude() ?? "",
                "longitude" => $row->getLongitude() ?? ""
            ];
        }
        $data = array_map("unserialize", array_unique(array_map("serialize", $data)));
        if(count($data) == 0){
            return new JsonResponse(['message' => 'Not Found'], 404);
        }
        else if(count($data) > 1){
            return new JsonResponse(['message' => 'Ambiguous Data'], 406);
        }
        else {
            return new JsonResponse(array_pop($data));
        }
    }
}
