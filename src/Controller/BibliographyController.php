<?php

namespace App\Controller;

use App\Entity\Biblio;
use App\Entity\IndexRecherche;
use App\Entity\VerrouEntite;
use App\Form\BiblioType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class BibliographyController extends AbstractController
{
    /**
     * @var int
     */
    private $dureeVerrou;

    public function __construct(int $dureeVerrou)
    {
        $this->dureeVerrou = $dureeVerrou;
    }

    /**
     * @Route("/bibliography", name="bibliography_list")
     */
    public function index(ManagerRegistry $doctrine)
    {
        $biblios = $doctrine
            ->getRepository(Biblio::class)
            ->findAll();
        return $this->render('bibliography/index.html.twig', [
            'controller_name' => 'BibliographyController',
            'biblios'         => $biblios,
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'biblio.list']
            ]
        ]);
    }

    /**
     * @Route("/bibliography/create", name="bibliography_create")
     */
    public function create(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $biblio = new Biblio();

        $form   = $this->createForm(BiblioType::class, $biblio, [
            'action'       => 'create',
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($biblio);
            $em->flush();

            // Message de confirmation
            $request->getSession()->getFlashBag()->add('success', 'biblio.messages.created');
            if ($request->request->has("saveclose")) {
                return $this->redirectToRoute('bibliography_list');
            }
            return $this->redirectToRoute('bibliography_edit', ['id' => $biblio->getId()]);
        }

        return $this->render('bibliography/edit.html.twig', [
            'controller_name' => 'BibliographyController',
            'action'          => 'create',
            'locale'          => $request->getLocale(),
            'form'            => $form->createView(),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'biblio.list', 'url' => $this->generateUrl('bibliography_list')],
                ['label' => 'biblio.create']
            ]
        ]);
    }

    /**
     * @Route("/bibliography/{id}", name="bibliography_show")
     */
    public function show($id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $bibliography = $doctrine
            ->getRepository(Biblio::class)
            ->find(intval($id));
        if (is_null($bibliography)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('biblio.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('bibliography_list');
        }

        $valid_sources = $doctrine->getRepository(IndexRecherche::class)->getEntityIds('Source', !$this->isGranted('ROLE_CONTRIBUTOR'));

        return $this->render('bibliography/show.html.twig', [
            'controller_name' => 'BibliographyController',
            'bibliography'    => $bibliography,
            'valid_sources'   => $valid_sources,
            'locale'          => $request->getLocale(),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'biblio.list', 'url' => $this->generateUrl('bibliography_list')],
                ['label' => $translator->trans('biblio.view', ['%id%' => $id])]
            ]
        ]);
    }

    /**
     * @Route("/bibliography/{id}/edit", name="bibliography_edit")
     */
    public function edit($id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $user = $this->getUser();
        $biblio = $doctrine
            ->getRepository(Biblio::class)
            ->find(intval($id));
        if (is_null($biblio)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('biblio.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('bibliography_list');
        }
        if ($biblio->getVerrou() === null) {
            $verrou = $doctrine->getRepository(VerrouEntite::class)->create($biblio, $user, $this->dureeVerrou);
        } else if (!$biblio->getVerrou()->isWritable($user)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('generic.messages.error_locked', [
                    '%type%' => $translator->trans('biblio.list'),
                    '%id%' => $id,
                    '%user%' => $biblio->getVerrou()->getCreateur()->getPrenomNom(),
                    '%time%' => $biblio->getVerrou()->getDateFin()->format(
                        $translator->trans('locale_datetime')
                    )
                ])
            );
            return $this->redirectToRoute('bibliography_list');
        }

        $form   = $this->createForm(BiblioType::class, $biblio, [
            'action'       => 'edit',
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($biblio);
            $doctrine->getRepository(VerrouEntite::class)->remove($biblio->getVerrou());
            $em->flush();

            // Message de confirmation
            $request->getSession()->getFlashBag()->add('success', 'biblio.messages.edited');
            if ($request->request->has("saveclose")) {
                return $this->redirectToRoute('bibliography_list');
            }
            return $this->redirectToRoute('bibliography_edit', ['id' => $biblio->getId()]);
        }

        return $this->render('bibliography/edit.html.twig', [
            'controller_name' => 'BibliographyController',
            'biblio'          => $biblio,
            'action'          => 'edit',
            'locale'          => $request->getLocale(),
            'form'            => $form->createView(),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'biblio.list', 'url' => $this->generateUrl('bibliography_list')],
                ['label' => $translator->trans('biblio.edit', ['%id%' => $id])]
            ]
        ]);
    }

    /**
     * @Route("/biblio/{id}/canceledit", name="biblio_canceledit")
     */
    public function canceledit($id, Request $request, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $user = $this->getUser();
        $biblio = $doctrine
            ->getRepository(Biblio::class)
            ->find(intval($id));
        $verrou = $doctrine->getRepository(VerrouEntite::class)->fetch($biblio);
        if ($verrou !== null && $verrou->isWritable($user)) {
            $doctrine->getRepository(VerrouEntite::class)->remove($verrou);
        }
        return $this->redirectToRoute('bibliography_list');
    }

    /**
     * @Route("/bibliography/{id}/delete", name="bibliography_delete")
     */
    public function delete($id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $submittedToken = $request->request->get('token');
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('delete_biblio', $submittedToken)) {
            $repository = $doctrine->getRepository(Biblio::class);
            $biblio = $repository->find(intval($id));
            if ($biblio instanceof Biblio) {
                if ($this->isGranted('ROLE_ADMIN')) {
                    $verrou = $biblio->getVerrou();
                    if (!$verrou || $verrou->isWritable($user)) {
                        $em = $doctrine->getManager();
                        $em->remove($biblio);
                        $em->flush();
                        $request->getSession()->getFlashBag()->add('success', 'biblio.messages.deleted');
                    } else {
                        $request->getSession()->getFlashBag()->add(
                            'error',
                            $translator->trans('generic.messages.error_locked', [
                                '%type%' => $translator->trans('biblio.list'),
                                '%id%'   => $id,
                                '%user%' => $verrou->getCreateur()->getPrenomNom(),
                                '%time%' => $verrou->getDateFin()->format(
                                    $translator->trans('locale_datetime')
                                )
                            ])
                        );
                    }
                } else {
                    $request->getSession()->getFlashBag()->add('error', 'generic.messages.error_unauthorized');
                }
            } else {
                $request->getSession()->getFlashBag()->add('error', 'generic.messages.deletion_failed_missing');
            }
        } else {
            $request->getSession()->getFlashBag()->add('error', 'generic.messages.deletion_failed_csrf');
        }
        return $this->redirectToRoute('bibliography_list');
    }
}
