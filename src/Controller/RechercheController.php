<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class RechercheController extends AbstractController
{
    /**
     * @Route("/search/", name="search")
     */
    public function index(Request $request, TranslatorInterface $translator)
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'RechercheController',
            'locale'          => $request->getLocale(),
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
        $search = $request->request->get('search_value');
        // TODO : If search is empty, go back to form
        $results = $this->getDoctrine()
                        ->getRepository(\App\Entity\IndexRecherche::class)
                        ->simpleSearch($search, $request->getLocale());

        return $this->render('search/results.html.twig', [
            'controller_name' => 'RechercheController',
            'locale'          => $request->getLocale(),
            'results'         => $results,
            'mode'            => 'simple',
            'criteria'        => [$search],
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

}
