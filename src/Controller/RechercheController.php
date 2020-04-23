<?php

namespace App\Controller;

use App\Entity\RechercheEnregistree;
use App\Search\Criteria;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RechercheController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(Request $request, TranslatorInterface $translator, Criteria $searchCriteria)
    {
        $locale = $request->getLocale();

        $populate_mode = $request->request->get('populate_mode', '');
        $populate_criteria = urldecode($request->request->get('populate_criteria', ''));

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $queries = $this->getDoctrine()
            ->getRepository(RechercheEnregistree::class)
            ->findAllByChercheur($user);

        return $this->render('search/index.html.twig', [
            'controller_name' => 'RechercheController',
            'locale'          => $locale,
            'populate'        => [
                'mode'     => $populate_mode,
                'criteria' => $populate_criteria,
            ],
            'saved_queries' => $queries,
            'criteria_list' => \App\Search\CriteriaList::get($translator),
            'breadcrumbs'   => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'search.title']
            ]
        ]);
    }

    /**
     * @Route("/search/criteria/{criteriaName}", name="search_criteria")
     */
    public function criteria($criteriaName, Request $request, Criteria $searchCriteria)
    {
        $locale = $request->getLocale();

        $data = $searchCriteria->getData($criteriaName, $locale);

        return new JsonResponse([
            'success' => true,
            'data' => $data
        ], 200);
    }

    /**
     * @Route("/search/simple", name="search_simple")
     */
    public function simpleSearch(Request $request, TranslatorInterface $translator, Criteria $searchCriteria)
    {
        $searchMode = 'simple';
        $queryName  = $request->request->get('queryName', '');
        $search     = $request->request->get('search_value', '');
        if (!strlen($search)) {
            return $this->_emptySearchResponse($request, $searchMode);
        }

        $results = $this->getDoctrine()
            ->getRepository(\App\Entity\IndexRecherche::class)
            ->simpleSearch($search, $request->getLocale());

        return $this->render('search/results_mixed.html.twig', [
            'controller_name' => 'RechercheController',
            'locale'          => $request->getLocale(),
            'results'         => $results,
            'mode'            => $searchMode,
            'criteria'        => [$search],
            'criteriaDisplay' => $this->_prepareCriteriaDisplay($searchMode, [$search], $request->getLocale(), $translator, $searchCriteria),
            'queryName'       => $queryName,
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
    public function guidedSearch(Request $request, TranslatorInterface $translator, Criteria $searchCriteria)
    {
        $searchMode  = 'guided';
        $queryName   = $request->request->get('queryName', '');
        $criteriaRaw = $request->request->all();
        $criteria    = $searchCriteria->validateGuidedCriteria($criteriaRaw);

        if ($criteria === false) {
            return $this->_emptySearchResponse($request, $searchMode);
        }

        $results = $this->getDoctrine()
            ->getRepository(\App\Entity\IndexRecherche::class)
            ->search($searchMode, $criteria, $request->getLocale());

        return $this->render('search/results_mixed.html.twig', [
            'controller_name' => 'RechercheController',
            'locale'          => $request->getLocale(),
            'results'         => $results,
            'mode'            => $searchMode,
            'criteria'        => $criteria,
            'criteriaDisplay' => $this->_prepareCriteriaDisplay($searchMode, $criteria, $request->getLocale(), $translator, $searchCriteria),
            'queryName'       => $queryName,
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'search.title', 'url' => $this->generateUrl('search')],
                ['label' => 'search.results']
            ]
        ]);
    }

    /**
     * @Route("/search/advanced", name="search_advanced")
     */
    public function advancedSearch(Request $request, TranslatorInterface $translator, Criteria $searchCriteria)
    {
        $searchMode  = 'advanced';
        $queryName   = $request->request->get('queryName', '');
        $criteriaRaw = $request->request->all();
        $criteria    = $searchCriteria->validateAdvancedCriteria($criteriaRaw);

        if ($criteria === false) {
            return $this->_emptySearchResponse($request, $searchMode);
        }

        $resultsType = $criteria['resultsType'];

        $results = $this->getDoctrine()
            ->getRepository(\App\Entity\IndexRecherche::class)
            ->search($searchMode, $criteria, $request->getLocale());

        return $this->render("search/results_{$resultsType}.html.twig", [
            'controller_name' => 'RechercheController',
            'locale'          => $request->getLocale(),
            'results'         => $results,
            'mode'            => $searchMode,
            'resultsType'     => $resultsType,
            'criteria'        => $criteria,
            'criteriaDisplay' => $this->_prepareCriteriaDisplay($searchMode, $criteria, $request->getLocale(), $translator, $searchCriteria),
            'queryName'       => $queryName,
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'search.title', 'url' => $this->generateUrl('search')],
                ['label' => 'search.results']
            ]
        ]);
    }

    /**
     * @Route("/search/elements", name="search_elements")
     */
    public function elementsSearch(Request $request, TranslatorInterface $translator, Criteria $searchCriteria)
    {
        $searchMode  = 'elements';
        $queryName   = $request->request->get('queryName', '');
        $criteriaRaw = $request->request->all();
        $criteria    = $searchCriteria->validateElementsCriteria($criteriaRaw);

        if ($criteria === false) {
            return $this->_emptySearchResponse($request, $searchMode);
        }

        $resultsType = "attestation";

        $results = $this->getDoctrine()
            ->getRepository(\App\Entity\IndexRecherche::class)
            ->search($searchMode, $criteria, $request->getLocale());

        return $this->render("search/results_{$resultsType}.html.twig", [
            'controller_name' => 'RechercheController',
            'locale'          => $request->getLocale(),
            'results'         => $results,
            'mode'            => $searchMode,
            'criteria'        => $criteria,
            'criteriaDisplay' => $this->_prepareCriteriaDisplay($searchMode, $criteria, $request->getLocale(), $translator, $searchCriteria),
            'queryName'       => $queryName,
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'search.title', 'url' => $this->generateUrl('search')],
                ['label' => 'search.results']
            ]
        ]);
    }

    /**
     * @Route("/search/save", name="search_save")
     */
    public function searchSave(Request $request, TranslatorInterface $translator)
    {
        $submittedToken = $request->request->get('token');
        $query_name = $request->request->get('query_name', '');
        $query_mode = $request->request->get('query_mode', '');
        $query_criteria = urldecode($request->request->get('query_criteria', '{}'));

        if (!$this->isCsrfTokenValid('query_save', $submittedToken)) {
            return new JsonResponse([
                'success' => false,
                'message' => $translator->trans('generic.messages.failed_csrf')
            ], 400);
        }
        if (!strlen($query_name)) {
            return new JsonResponse([
                'success' => false,
                'message' => $translator->trans('search.messages.invalid_empty_name')
            ], 400);
        }
        if (!in_array($query_mode, ['simple', 'guided', 'advanced', 'elements'])) {
            return new JsonResponse([
                'success' => false,
                'message' => $translator->trans('search.messages.invalid_query_mode', [
                    '%mode%' => $query_mode
                ])
            ], 400);
        }
        if (empty(json_decode($query_criteria, true))) {
            return new JsonResponse([
                'success' => false,
                'message' => $translator->trans('search.messages.invalid_empty_criteria')
            ], 400);
        }

        $query = new RechercheEnregistree;
        $query->setNom($query_name);
        $query->setMode($query_mode);
        $query->setCriteria($query_criteria);
        $query->setCreateur(
            $this->get('security.token_storage')->getToken()->getUser()
        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($query);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'message' => $translator->trans('search.messages.query_saved')
        ], 201);
    }

    /**
     * @Route("/search/{id}/delete", name="search_delete")
     */
    public function searchDelete($id, Request $request)
    {
        $submittedToken = $request->request->get('token');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($this->isCsrfTokenValid('delete_query_' . $id, $submittedToken)) {
            $repository = $this->getDoctrine()->getRepository(RechercheEnregistree::class);
            $query = $repository->find($id);
            if ($query instanceof RechercheEnregistree) {
                if ($query->getCreateur()->getId() === $user->getId()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($query);
                    $em->flush();
                    $request->getSession()->getFlashBag()->add('success', 'search.messages.query_deleted');
                } else {
                    $request->getSession()->getFlashBag()->add('error', 'generic.messages.error_unauthorized');
                }
            } else {
                $request->getSession()->getFlashBag()->add('error', 'generic.messages.deletion_failed_missing');
            }
        } else {
            $request->getSession()->getFlashBag()->add('error', 'generic.messages.deletion_failed_csrf');
        }
        return $this->redirectToRoute('search');
    }

    /**
     * @Route("/search/clear_cache", name="search_clear_cache")
     */
    public function clearCache(Request $request, TranslatorInterface $translator, TagAwareCacheInterface $mapCache)
    {
        $mapCache->invalidateTags(['Recherche']);
        // Message de confirmation
        $request->getSession()->getFlashBag()->add(
            'success',
            $translator->trans('search.messages.cache_cleared')
        );
        return $this->redirectToRoute('search');
    }

    /**
     * @Route("/search/rebuild_index", name="search_reindex")
     */
    public function rebuildIndex(Request $request, TranslatorInterface $translator)
    {
        set_time_limit(3600);
        $start = microtime(true);

        $records = $this->getDoctrine()
            ->getRepository(\App\Entity\IndexRecherche::class)
            ->fullRebuild();

        $end = microtime(true);
        $totalTime = round($end - $start);
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

    private function _emptySearchResponse(Request $request, string $mode)
    {
        $request->getSession()->getFlashBag()->add(
            'error',
            'search.messages.no_empty_search'
        );
        return $this->redirect(
            $this->get('router')->generate('search', ['_fragment' => $mode])
        );
    }

    private function _prepareCriteriaDisplay($mode, $criteria, $locale, TranslatorInterface $translator, Criteria $searchCriteria)
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        if ($mode == "simple") {
            return $criteria;
        }
        if ($mode == "guided") {
            $response = [];
            foreach ($criteria as $key => $value) {
                if ($key !== 'names_mode' && $key !== 'languages_mode') {
                    $response['search.criteria_labels.' . $key] = $searchCriteria->getDisplay($key, $value, $locale);
                }
            }
            if (($criteria['names_mode'] ?? 'one') === 'all'
                && array_key_exists('names', $criteria)
            ) {
                $response['search.criteria_labels.names_all'] = $response['search.criteria_labels.names'];
                unset($response['search.criteria_labels.names']);
            }
            if (($criteria['languages_mode'] ?? 'one') === 'all'
                && array_key_exists('languages', $criteria)
            ) {
                $response['search.criteria_labels.languages_all'] = $response['search.criteria_labels.languages'];
                unset($response['search.criteria_labels.languages']);
            }
            foreach ($response as $key => $value) {
                $response[$translator->trans($key)] = $value;
                unset($response[$key]);
            }
            return $response;
        }
        if ($mode == "advanced") {
            $response = [];
            foreach ($criteria as $key => $value) {
                if ($key == "resultsType") {
                    continue;
                }

                // Find translated key
                $criteriaSettings = \App\Search\CriteriaList::getCriteria($key, $translator);

                $criteriaLabel = $criteriaSettings['label'];

                switch ($criteriaSettings['type']) {
                    case 'text':
                        $criteriaValues = $value;
                        break;
                    case 'range':
                        $criteriaValues = array_map(function ($v) use ($criteriaSettings) {
                            return \App\Utils\StringHelper::operatorToString($v['operator']) . " " . $criteriaSettings['datalist'][$v['value']];
                        }, $value);
                        break;
                    case 'operation':
                        $criteriaValues = array_map(function ($v) use ($criteriaSettings) {
                            return \App\Utils\StringHelper::operatorToString($v['operator']) . " " . $v['value'];
                        }, $value);
                        break;
                    case 'prosepoetry':
                        $criteriaValues = array_map(function ($v) use ($translator) {
                            return array_map(function ($v_value) use ($translator) {
                                return $translator->trans('attestation.fields.' . $v_value);
                            }, $v);
                        }, $value);
                        break;
                    case 'datation':
                        $criteriaValues = array_map(function ($v) use ($key, $searchCriteria, $locale) {
                            return $searchCriteria->getDisplay($key, $v, $locale);
                        }, $value);
                        break;
                    case 'select':
                        $criteriaValues = array_map(function ($v) use ($key, $searchCriteria, $locale) {
                            $values = $searchCriteria->getDisplay($key, $v['values'], $locale);
                            if ("all" === ($v['mode'] ?? null)) {
                                array_unshift($values, 'all');
                            }
                            return $values;
                        }, $value);
                        break;
                    default:
                        throw new \InvalidArgumentException("Criteria $key (with value '" . json_encode($value) . "') is not of an accepted type ('{$criteriaSettings['type']}')");
                }

                $response[$criteriaLabel] = $criteriaValues;
            }
            return $response;
        }
        if ($mode == "elements") {
            $response = [];
            foreach ($criteria as $key => $value) {
                switch ($key) {
                    case 'element_count':
                        $response['attestation.sections.elements'] = \App\Utils\StringHelper::operatorToString($value['operator']) . " " . $value['value'];
                        break;
                    case 'divine_powers_count':
                        $response['formule.fields.puissances_divines'] = \App\Utils\StringHelper::operatorToString($value['operator']) . " " . $value['value'];
                        break;
                    case 'formule':
                        $response['search.criteria_labels.formule'] = $value;
                        break;
                    case 'languages_mode':
                        break;
                    default:
                        $response['search.criteria_labels.' . $key] = $searchCriteria->getDisplay($key, $value, $locale);
                }
            }
            if (($criteria['languages_mode'] ?? 'one') === 'all'
                && array_key_exists('languages', $criteria)
            ) {
                $response['search.criteria_labels.languages_all'] = $response['search.criteria_labels.languages'];
                unset($response['search.criteria_labels.languages']);
            }
            foreach ($response as $key => $value) {
                $response[$translator->trans($key)] = $value;
                unset($response[$key]);
            }
            return $response;
        }
    }
}
