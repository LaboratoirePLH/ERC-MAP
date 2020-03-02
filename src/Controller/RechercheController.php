<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class RechercheController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(Request $request, TranslatorInterface $translator)
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'RechercheController',
            'locale'          => $request->getLocale(),
            'data'            => $this->_prepareFormData($request->getLocale()),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'search.title']
            ]
        ]);
    }

    /**
     * @Route("/search/simple", name="search_simple")
     */
    public function simpleSearch(Request $request, TranslatorInterface $translator)
    {
        $search = $request->request->get('search_value', '');
        if(!strlen($search)){
            $request->getSession()->getFlashBag()->add(
                'error',
                'search.messages.no_empty_search'
            );
            return $this->redirect(
                $this->get('router')->generate('search', ['_fragment' => 'simple'])
            );
        }
        $results = $this->getDoctrine()
                        ->getRepository(\App\Entity\IndexRecherche::class)
                        ->simpleSearch($search, $request->getLocale());

        return $this->render('search/results.html.twig', [
            'controller_name' => 'RechercheController',
            'locale'          => $request->getLocale(),
            'results'         => $results,
            'mode'            => 'simple',
            'criteria'        => [$search],
            'criteriaDisplay' => $this->_prepareCriteriaDisplay('simple', [$search], $request->getLocale(), $translator),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'search.title', 'url' => $this->generateUrl('search')],
                ['label' => 'search.results']
            ]
        ]);
    }

    /**
     * @Route("/search/guided", name="search_guided")
     */
    public function guidedSearch(Request $request, TranslatorInterface $translator)
    {
        $criteria = array_filter(
            $request->request->all(),
            function($value, $key){
                return in_array(
                    $key,
                    [
                        'names', 'names_mode',
                        'languages', 'languages_mode',
                        'datation', 'locations',
                        'sourceTypes', 'agents'
                    ]
                    ) && !empty($value);
            },
            ARRAY_FILTER_USE_BOTH
        );
        if(array_key_exists('datation', $criteria)
            && $criteria['datation']['post_quem'] == ''
            && $criteria['datation']['ante_quem'] == '')
        {
            unset($criteria['datation']);
        }
        if(!count(array_keys($criteria))){
            $request->getSession()->getFlashBag()->add(
                'error',
                'search.messages.no_empty_search'
            );
            return $this->redirect(
                $this->get('router')->generate('search', ['_fragment' => 'guided'])
            );
        }

        $results = $this->getDoctrine()
                        ->getRepository(\App\Entity\IndexRecherche::class)
                        ->guidedSearch($criteria, $request->getLocale());

        return $this->render('search/results.html.twig', [
            'controller_name' => 'RechercheController',
            'locale'          => $request->getLocale(),
            'results'         => $results,
            'mode'            => 'guided',
            'criteria'        => $criteria,
            'criteriaDisplay' => $this->_prepareCriteriaDisplay('guided', $criteria, $request->getLocale(), $translator),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'search.title', 'url' => $this->generateUrl('search')],
                ['label' => 'search.results']
            ]
        ]);
    }

    /**
     * @Route("/search/rebuild_index", name="search_reindex")
     */
    public function rebuild(Request $request, TranslatorInterface $translator)
    {
        set_time_limit(3600);
        $start = microtime(true);

        $records = $this->getDoctrine()
                        ->getRepository(\App\Entity\IndexRecherche::class)
                        ->fullRebuild();

        $end = microtime(true);
        $totalTime = round($end-$start);
        // Message de confirmation
        $request->getSession()->getFlashBag()->add(
            'success',
            $translator->trans('search.messages.reindex_done', [
                '%records%' => $records,
                '%time%' => $totalTime
            ])
        );
        return $this->redirectToRoute('search');
    }

    private function _prepareCriteriaDisplay($mode, $criteria, $locale, TranslatorInterface $translator) {
        $em = $this->getDoctrine()->getEntityManager();
        $nameField = "nom" . ucfirst(strtolower($locale));

        if($mode == "simple"){
            return $criteria;
        }
        if($mode == "guided"){
            $formData = $this->_prepareFormData($locale);
            $response = [];
            foreach($criteria as $key => $value){
                switch($key){
                    case 'names':
                    case 'languages':
                    case 'locations':
                    case 'sourceTypes':
                        $response['search.criteria_labels.'.$key] = array_values(array_filter(
                            $formData[$key],
                            function($id) use ($value) {
                                return in_array($id, $value);
                            },
                            ARRAY_FILTER_USE_KEY
                        ));
                        break;
                    case 'datation':
                        $v = array_filter([
                            (!is_null($value['post_quem'])
                                ? ($translator->trans('datation.fields.post_quem') . ' : ' . $value['post_quem'])
                                : null
                            ),
                            (!is_null($value['ante_quem'])
                                ? ($translator->trans('datation.fields.ante_quem') . ' : ' . $value['ante_quem'])
                                : null
                            ),
                            ((($value['exact'] ?? null) === 'datation_exact')
                                ? $translator->trans('generic.fields.strict')
                                : null
                            )
                        ]);
                        if(!empty($v)){
                            $response['search.criteria_labels.'.$key] = implode(' ; ', $v);
                        }
                    break;
                    case 'agents':
                        $response['search.criteria_labels.'.$key] = array_values(array_filter(
                            array_merge($formData[$key]['activites'], $formData[$key]['agentivites']),
                            function($id) use ($value) {
                                return in_array($id, $value);
                            },
                            ARRAY_FILTER_USE_KEY
                        ));
                        break;
                    default:
                        break;
                }
            }
            if(($criteria['names_mode'] ?? 'one') === 'all'
                && array_key_exists('names', $criteria)){
                $response['search.criteria_labels.names_all'] = $response['search.criteria_labels.names'];
                unset($response['search.criteria_labels.names']);
            }
            if(($criteria['languages_mode'] ?? 'one') === 'all'
                && array_key_exists('languages', $criteria)){
                $response['search.criteria_labels.languages_all'] = $response['search.criteria_labels.languages'];
                unset($response['search.criteria_labels.languages']);
            }
            return $response;
        }
    }

    private function _prepareFormData($locale){
        $em = $this->getDoctrine()->getEntityManager();
        $nameField = "nom" . ucfirst(strtolower($locale));

        // Get Names
        $query = $em->createQuery("SELECT partial e.{id, etatAbsolu, betaCode}, partial t.{id, {$nameField}}
                                   FROM \App\Entity\Element e LEFT JOIN e.traductions t");
        $els = $query->getArrayResult();
        $elements = [];
        foreach($els as $el){
            $trads = array_column($el['traductions'], $nameField);
            if(!empty($trads)){
                $trads = '(' . implode(' ; ', $trads) . ')';
            } else {
                $trads = '';
            }
            $elements[$el['id']] = implode(' ', array_filter([
                $el['etatAbsolu'],
                $el['betaCode'] ? '['.$el['betaCode'].']' : null,
                $trads
            ]));
        }
        uasort($elements, function($a, $b){
            return \App\Utils\StringHelper::removeAccents(strip_tags($a))
                <=> \App\Utils\StringHelper::removeAccents(strip_tags($b));
        });

        // Get Languages
        $query = $em->createQuery("SELECT partial l.{id, {$nameField}} FROM \App\Entity\Langue l");
        $languages = array_combine(
            array_column($query->getArrayResult(), 'id'),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($languages, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });

        // Get Locations
        $query = $em->createQuery("SELECT partial r.{id, {$nameField}} FROM \App\Entity\GrandeRegion r");
        $grandeRegions = $query->getArrayResult();
        $query = $em->createQuery("SELECT partial r.{id, {$nameField}}, partial gr.{id, {$nameField}} FROM \App\Entity\SousRegion r LEFT JOIN r.grandeRegion gr");
        $sousRegions = $query->getArrayResult();
        $query = $em->createQuery("SELECT partial l.{id, pleiadesVille, nomVille}, partial sr.{id, {$nameField}}, partial gr.{id, {$nameField}}
                                    FROM \App\Entity\Localisation l LEFT JOIN l.sousRegion sr LEFT JOIN l.grandeRegion gr
                                    WHERE l.nomVille IS NOT NULL");
        $lieux = $query->getArrayResult();
        $locations = [];
        foreach($grandeRegions as $gr){
            $id = json_encode([$gr['id']]);
            $locations[$id] = $gr[$nameField];
        }
        foreach($sousRegions as $sr){
            $id = json_encode([$sr['grandeRegion']['id'], $sr['id']]);
            $locations[$id] = $sr['grandeRegion'][$nameField].' > '.$sr[$nameField];
        }
        foreach($lieux as $l){
            // TODO : Manage case where we have 2 nomVille with identical grandeRegion & sousRegion, but no pleiades ID
            $id = json_encode([
                $l['grandeRegion']['id'] ?? 0,
                $l['sousRegion']['id'] ?? 0,
                $l['pleiadesVille']
            ]);
            $value = ($l['grandeRegion'][$nameField] ?? '').' > '.($l['sousRegion'][$nameField] ?? '').' > '.$l['nomVille'];
            $value = str_replace('>  >', '>>', $value);
            $locations[$id] = $value;
        }
        uasort($locations, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });

        // Get Source Types
        $query = $em->createQuery("SELECT partial c.{id, {$nameField}} FROM \App\Entity\CategorieSource c");
        $categorieSource = $query->getArrayResult();
        $query = $em->createQuery("SELECT partial t.{id, {$nameField}}, partial c.{id, {$nameField}} FROM \App\Entity\TypeSource t LEFT JOIN t.categorieSource c");
        $typeSource = $query->getArrayResult();
        $sourceTypes = [];
        foreach($categorieSource as $cs){
            $id = json_encode([$cs['id']]);
            $sourceTypes[$id] = $cs[$nameField];
        }
        foreach($typeSource as $ts){
            $id = json_encode([$ts['categorieSource']['id'], $ts['id']]);
            $sourceTypes[$id] = $ts['categorieSource'][$nameField].' > '.$ts[$nameField];
        }
        uasort($sourceTypes, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });

        // Get Agents
        $query = $em->createQuery("SELECT partial aa.{id, {$nameField}} FROM \App\Entity\ActiviteAgent aa");
        $activites = array_combine(
            array_map(function($a){ return json_encode(['activite', $a]); }, array_column($query->getArrayResult(), 'id')),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($activites, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });

        $query = $em->createQuery("SELECT partial ag.{id, {$nameField}} FROM \App\Entity\Agentivite ag");
        $agentivites = array_combine(
            array_map(function($a){ return json_encode(['agentivite', $a]); }, array_column($query->getArrayResult(), 'id')),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($agentivites, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });

        return [
            'names'       => $elements,
            'languages'   => $languages,
            'locations'   => $locations,
            'sourceTypes' => $sourceTypes,
            'agents'      => [
                'activites' => $activites,
                'agentivites' => $agentivites
            ]
        ];
    }

}
