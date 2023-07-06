<?php

namespace App\Controller;

use App\Entity\RechercheEnregistree;
use App\Entity\RequeteEnregistree;
use App\Search\Criteria;
use App\Search\ExportNodes;
use App\Search\ExportResults;
use Doctrine\Persistence\ManagerRegistry;
use PDO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RechercheController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(Request $request, TranslatorInterface $translator, Criteria $searchCriteria, ManagerRegistry $doctrine)
    {
        $locale = $request->getLocale();

        // Compute index status
        $indexStatus = $doctrine
            ->getRepository(\App\Entity\IndexRecherche::class)
            ->getStatus();

        $populate_mode = $request->request->get('populate_mode', '');
        $populate_criteria = urldecode($request->request->get('populate_criteria', ''));

        $user = $this->getUser();

        $queries = $this->isGranted('ROLE_USER')
            ? ($doctrine
                ->getRepository(RechercheEnregistree::class)
                ->findAllByChercheur($user))
            : [];
        $sql_queries = $doctrine
            ->getRepository(RequeteEnregistree::class)
            ->findAll();

        return $this->render('search/index.html.twig', [
            'controller_name' => 'RechercheController',
            'locale'          => $locale,
            'indexStatus'     => $indexStatus,
            'populate'        => [
                'mode'     => $populate_mode,
                'criteria' => $populate_criteria,
            ],
            'saved_queries' => $queries,
            'sql_queries'   => $sql_queries,
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
    public function simpleSearch(Request $request, Criteria $searchCriteria, ManagerRegistry $doctrine)
    {
        $searchMode = 'simple';
        $queryName  = $request->request->get('queryName', '');
        $search     = $request->request->get('search_value', '');
        if (!strlen($search)) {
            return $this->_emptySearchResponse($request, $searchMode);
        }
        $user = $this->getUser();
        $restrictToProjects = ($user === null || $user->getRole() === "admin") ? null : $user->getIdsProjets();

        $results = $doctrine
            ->getRepository(\App\Entity\IndexRecherche::class)
            ->simpleSearch($search, $request->getLocale(), !$this->isGranted('ROLE_CONTRIBUTOR'), $restrictToProjects);

        if (count($results)) {
            $cacheKey = $this->_cacheSearchResults($searchMode, $results);
        }

        return $this->render('search/results_mixed.html.twig', [
            'controller_name' => 'RechercheController',
            'locale'          => $request->getLocale(),
            'cacheKey'        => $cacheKey ?? false,
            'mode'            => $searchMode,
            'criteria'        => [$search],
            'criteriaDisplay' => $searchCriteria->getCriteriaDisplay($searchMode, [$search], $request->getLocale()),
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
    public function guidedSearch(Request $request, Criteria $searchCriteria, ManagerRegistry $doctrine)
    {
        $searchMode  = 'guided';
        $queryName   = $request->request->get('queryName', '');
        $criteriaRaw = $request->request->all();
        $criteria    = $searchCriteria->validateGuidedCriteria($criteriaRaw);

        if ($criteria === false) {
            return $this->_emptySearchResponse($request, $searchMode);
        }

        $user = $this->getUser();
        $restrictToProjects = ($user === null || $user->getRole() === "admin") ? null : $user->getIdsProjets();

        $results = $doctrine
            ->getRepository(\App\Entity\IndexRecherche::class)
            ->search($searchMode, $criteria, $request->getLocale(), !$this->isGranted('ROLE_CONTRIBUTOR'), $restrictToProjects);

        if (count($results)) {
            $cacheKey = $this->_cacheSearchResults($searchMode, $results);
        }

        return $this->render('search/results_mixed.html.twig', [
            'controller_name' => 'RechercheController',
            'locale'          => $request->getLocale(),
            'cacheKey'        => $cacheKey ?? false,
            'mode'            => $searchMode,
            'criteria'        => $criteria,
            'criteriaDisplay' => $searchCriteria->getCriteriaDisplay($searchMode, $criteria, $request->getLocale()),
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
    public function advancedSearch(Request $request, Criteria $searchCriteria, ManagerRegistry $doctrine)
    {
        $searchMode  = 'advanced';
        $queryName   = $request->request->get('queryName', '');
        $criteriaRaw = $request->request->all();
        $criteria    = $searchCriteria->validateAdvancedCriteria($criteriaRaw);

        if ($criteria === false) {
            return $this->_emptySearchResponse($request, $searchMode);
        }

        $user = $this->getUser();
        $restrictToProjects = ($user === null || $user->getRole() === "admin") ? null : $user->getIdsProjets();

        $resultsType = $criteria['resultsType'];

        $results = $doctrine
            ->getRepository(\App\Entity\IndexRecherche::class)
            ->search($searchMode, $criteria, $request->getLocale(), !$this->isGranted('ROLE_CONTRIBUTOR'), $restrictToProjects);

        if (count($results)) {
            $cacheKey = $this->_cacheSearchResults($searchMode, $results);
        }

        return $this->render("search/results_{$resultsType}.html.twig", [
            'controller_name' => 'RechercheController',
            'locale'          => $request->getLocale(),
            'cacheKey'        => $cacheKey ?? false,
            'mode'            => $searchMode,
            'resultsType'     => $resultsType,
            'criteria'        => $criteria,
            'criteriaDisplay' => $searchCriteria->getCriteriaDisplay($searchMode, $criteria, $request->getLocale()),
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
    public function elementsSearch(Request $request, Criteria $searchCriteria, ManagerRegistry $doctrine)
    {
        $searchMode  = 'elements';
        $queryName   = $request->request->get('queryName', '');
        $criteriaRaw = $request->request->all();
        $criteria    = $searchCriteria->validateElementsCriteria($criteriaRaw);

        if ($criteria === false) {
            return $this->_emptySearchResponse($request, $searchMode);
        }

        $resultsType = "attestation";

        $results = $doctrine
            ->getRepository(\App\Entity\IndexRecherche::class)
            ->search($searchMode, $criteria, $request->getLocale(), !$this->isGranted('ROLE_CONTRIBUTOR'));

        if (count($results)) {
            $cacheKey = $this->_cacheSearchResults($searchMode, $results);
        }

        return $this->render("search/results_{$resultsType}.html.twig", [
            'controller_name' => 'RechercheController',
            'locale'          => $request->getLocale(),
            'cacheKey'        => $cacheKey ?? false,
            'mode'            => $searchMode,
            'resultsType'     => $resultsType,
            'criteria'        => $criteria,
            'criteriaDisplay' => $searchCriteria->getCriteriaDisplay($searchMode, $criteria, $request->getLocale()),
            'queryName'       => $queryName,
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'search.title', 'url' => $this->generateUrl('search')],
                ['label' => 'search.results']
            ]
        ]);
    }

    /**
     * @Route("/search/sql", name="search_sql")
     */
    public function sqlSearch(Request $request, Criteria $searchCriteria, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $searchMode = 'sql';
        $locale     = $request->getLocale();
        $search     = $request->request->get('search_value', '');
        $query_id   = $request->request->get('saved_query', '');

        if (!strlen($search)) {
            return $this->_emptySearchResponse($request, $searchMode);
        }

        // Check for invalid queries
        $queries = explode(';', $search);
        $invalid = array_filter($queries, function ($q) {
            $q = strtolower(trim($q));
            return strlen($q) > 0 && substr($q, 0, 6) !== 'select';
        });
        if (count($invalid) > 0) {
            return $this->_errorSqlResponse($request, $searchMode, $translator->trans('search.messages.invalid_query'), $search);
        }

        $pdo = $doctrine->getConnection();
        $pdo->beginTransaction();

        $stmt = $pdo->prepare($search);
        $errorResponse = null;
        try {
            $stmt->execute();
            $result = $stmt->fetchAll();
            if (count($result) === 0) {
                $errorResponse = $this->_errorSqlResponse($request, $searchMode, 'search.messages.no_results', $search);
            }
        } catch (\Exception $e) {
            $errorResponse = $this->_errorSqlResponse($request, $searchMode, $translator->trans('search.messages.sql_error') . '<pre>' . $e->getMessage() . '</pre>', $search);
        } finally {
            $pdo->rollback();
        }
        if ($errorResponse !== null) {
            return $errorResponse;
        }

        $tmpFile = tmpfile();

        fputcsv($tmpFile, [$translator->trans('misc.export_copyright', ['%date%' => date('Y/m/d')])]);
        fputcsv($tmpFile, array_keys($result[0]));
        foreach ($result as $row) {
            fputcsv($tmpFile, $row);
        }

        $fileName = 'export_sql';
        if ($query_id !== '') {
            $query = $doctrine
                ->getRepository(RequeteEnregistree::class)
                ->find(intval($query_id));
            $fileName = $query->getNom($locale);
        }
        $fileName .= '_' . date('Y-m-d') . '.csv';

        rewind($tmpFile);
        $content = stream_get_contents($tmpFile);
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }

    /**
     * @Route("/search/results", name="json_search_results")
     */
    public function searchResults(Request $request, TranslatorInterface $translator)
    {
        // Cache adapter
        $cache = new FilesystemAdapter('search_results', 3600);

        // Check if we have a key in parameter
        $cacheKey = $request->query->get('cacheKey', null);

        $success = false;
        $data = [];
        if ($cacheKey != null) {
            // If we have a key check that it exists in the cache
            $cacheItem = $cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                // If if exists, checkes that the TTL has not expired
                $data = $cacheItem->get();
                $success = true;
            }
        }

        $result = [
            'success' => $success,
            'data' => $data
        ];

        $response = new Response(json_encode($result, JSON_INVALID_UTF8_IGNORE));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/search/results/webmapping", name="search_results_webmapping")
     */
    public function searchResultsWebmapping(Request $request)
    {
        if (!$request->isMethod('POST')) {
            return $this->redirect($this->generateUrl('search'));
        }

        $type = $request->request->get('type', '');
        $ids = json_decode($request->request->get('ids', '[]'));

        if (!in_array($type, ['source', 'attestation', 'element']) || !count($ids)) {
            return $this->redirect($this->generateUrl('search'));
        }

        return $this->render('search/webmapping.html.twig', [
            'controller_name' => 'SourceController',
            'title'           => 'search.results_webmapping',
            'data'            => compact('type', 'ids'),
            'webmapping'      => [
                'app_url'     => $this->getParameter('geo.app_url_' . $request->getLocale()),
                'function_id' => $this->getParameter('geo.function_' . $type . '_' . $request->getLocale())
            ],
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'search.title', 'url' => $this->generateUrl('search')],
                ['label' => 'search.results_webmapping']
            ]
        ]);
    }

    /**
     * @Route("/search/save", name="search_save")
     */
    public function searchSave(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
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
            $this->getUser()
        );

        $em = $doctrine->getManager();
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
    public function searchDelete($id, Request $request, ManagerRegistry $doctrine)
    {
        $submittedToken = $request->request->get('token');
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('delete_query_' . $id, $submittedToken)) {
            $repository = $doctrine->getRepository(RechercheEnregistree::class);
            $query = $repository->find($id);
            if ($query instanceof RechercheEnregistree) {
                if ($query->getCreateur()->getId() === $user->getId()) {
                    $em = $doctrine->getManager();
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

    public function rebuildIndexSync(Request $request, TranslatorInterface $translator, TagAwareCacheInterface $mapCache, ManagerRegistry $doctrine)
    {
        set_time_limit(3600);
        $start = microtime(true);

        $records = $doctrine
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

    /**
     * @Route("/search/rebuild_index", name="search_reindex")
     */
    public function rebuildIndex(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        // Store request start time, to prevent time_limit being reached
        $start_request = time();
        $MAX_REQUEST_TIME = 5;

        // Cache adapter
        $cache = new FilesystemAdapter('search_index_rebuild', 3600);

        // Repository
        $repo = $doctrine->getRepository(\App\Entity\IndexRecherche::class);

        // Check if we have a key in parameter
        $rebuildKey = $request->query->get('rebuildKey', null);

        $rebuildData = [];
        if ($rebuildKey != null) {
            // If we have a key check that it exists in the cache
            $cacheItem = $cache->getItem($rebuildKey);
            if ($cacheItem->isHit()) {
                // If if exists, checkes that the TTL has not expired
                $rebuildData = $cacheItem->get();
                if (time() - $rebuildData['startTime'] > 7200) {
                    // If TTL has expired, remove item from cache
                    $cache->deleteItem($rebuildKey);
                    $rebuildData = [];
                }
            }
        }


        // If we have no rebuild data, we start a new session
        if (empty($rebuildData)) {
            // List all the records to reindex
            $allRecords = $repo->buildReindexList();

            // Generate a key
            $rebuildKey = uniqid('rebuild_');

            // Generate data array
            $rebuildData = [
                'startTime' => $start_request,
                'rebuildKey' => $rebuildKey,
                'remaining' => $allRecords,
                'totalCount' => count($allRecords),
                'doneCount' => 0,
            ];
        }
        // If we have data, we process as many entries as we cas
        else {
            do {
                // Unstack an entry
                list($entityType, $entityId) = array_shift($rebuildData['remaining']);

                // Rebuild the entry
                $repo->rebuildEntry($entityType, $entityId);

                // Update rebuild data array
                $rebuildData['doneCount']++;
            } while (count($rebuildData['remaining']) > 0 && (time() - $start_request) <= $MAX_REQUEST_TIME);
            if (count($rebuildData['remaining']) === 0) {
                // Delete all index entries that do not exist in the original tables anymore
                $repo->clean();
            }
        }

        // Save data array in cache
        $cacheItem = $cache->getItem($rebuildKey);
        $cacheItem->set($rebuildData);
        $cache->save($cacheItem);

        // Respond with current data
        return new JsonResponse($rebuildData);
    }


    /**
     * @Route("/search/export_nodes", name="search_export_nodes")
     */
    public function exportNodes(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        if (!$request->isMethod('POST')) {
            return null;
        }
        $ids = json_decode($request->request->get('ids', '[]'));
        if (!count($ids)) {
            return null;
        }

        $result = ExportResults::getNodes($ids, $doctrine);

        $tmpFile = tmpfile();
        fputcsv($tmpFile, [$translator->trans('misc.export_copyright', ['%date%' => date('Y/m/d')])]);
        fputcsv($tmpFile, array_keys($result[0]));
        foreach ($result as $row) {
            fputcsv($tmpFile, $row);
        }

        $fileName = 'export_nodes_' . date('Y-m-d') . '.csv';

        rewind($tmpFile);
        $content = stream_get_contents($tmpFile);
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }

    /**
     * @Route("/search/export_links", name="search_export_links")
     */
    public function exportLinks(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        if (!$request->isMethod('POST')) {
            return null;
        }
        $ids = json_decode($request->request->get('ids', '[]'));
        if (!count($ids)) {
            return null;
        }

        $result = ExportResults::getLinks($ids, $doctrine, $request->getLocale());

        $tmpFile = tmpfile();
        fputcsv($tmpFile, [$translator->trans('misc.export_copyright', ['%date%' => date('Y/m/d')])]);
        fputcsv($tmpFile, array_keys($result[0]));
        foreach ($result as $row) {
            fputcsv($tmpFile, $row);
        }

        $fileName = 'export_links_' . date('Y-m-d') . '.csv';

        rewind($tmpFile);
        $content = stream_get_contents($tmpFile);
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }

    private function _emptySearchResponse(Request $request, string $mode)
    {
        return $this->_errorSearchResponse($request, $mode, 'search.messages.no_empty_search');
    }

    private function _errorSqlResponse(Request $request, string $mode, string $error, string $sql)
    {
        $request->getSession()->getFlashBag()->add(
            'sql',
            $sql
        );
        return $this->_errorSearchResponse($request, $mode, $error);
    }

    private function _errorSearchResponse(Request $request, string $mode, string $error)
    {
        $request->getSession()->getFlashBag()->add(
            'error',
            $error
        );
        return $this->redirect($this->generateUrl('search', ['_fragment' => $mode]));
    }

    private function _cacheSearchResults(string $mode, array $results): string
    {
        // Cache adapter
        $cache = new FilesystemAdapter("search_results", 1800);
        // Generate a key
        $cacheKey = uniqid("search_results_{$mode}_");

        // Save data array in cache
        $cacheItem = $cache->getItem($cacheKey);
        $cacheItem->set($results);
        $cache->save($cacheItem);

        return $cacheKey;
    }
}
