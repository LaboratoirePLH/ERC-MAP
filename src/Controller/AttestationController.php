<?php

namespace App\Controller;

use App\Entity\Attestation;
use App\Entity\Element;
use App\Entity\EtatFiche;
use App\Entity\IndexRecherche;
use App\Entity\Source;
use App\Entity\VerrouEntite;
use App\Form\AttestationType;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class AttestationController extends AbstractController
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
     * @Route("/attestation", name="attestation_list")
     */
    public function index()
    {
        return $this->render('attestation/index.html.twig', [
            'controller_name' => 'AttestationController',
            'action'          => 'list',
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'attestation.list']
            ]
        ]);
    }

    /**
     * @Route("/attestation/source/{source_id}", name="attestation_source")
     */
    public function indexSource($source_id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $source = $doctrine
            ->getRepository(Source::class)
            ->find(intval($source_id));
        if (is_null($source)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('source.messages.missing', ['%id%' => $source_id])
            );
            return $this->redirectToRoute('source_list');
        }

        return $this->render('attestation/index.html.twig', [
            'controller_name' => 'AttestationController',
            'action'          => 'list',
            'source'          => $source_id,
            'title'           => $translator->trans('attestation.list_for_source', ['%id%' => $source_id]),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'source.list', 'url' => $this->generateUrl('source_list')],
                ['label' => $translator->trans('attestation.list_for_source', ['%id%' => $source_id])]
            ]
        ]);
    }

    /**
     * @Route("/attestation/element/{element_id}", name="attestation_element")
     */
    public function indexForElement($element_id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $element = $doctrine
            ->getRepository(Element::class)
            ->find(intval($element_id));
        if (is_null($element)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('element.messages.missing', ['%id%' => $element_id])
            );
            return $this->redirectToRoute('attestation_list');
        }

        return $this->render('attestation/index.html.twig', [
            'controller_name' => 'AttestationController',
            'action'          => 'list',
            'element'         => $element_id,
            'title'           => $translator->trans('attestation.list_for_element', ['%id%' => $element_id]),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => $translator->trans('attestation.list_for_element', ['%id%' => $element_id])]
            ]
        ]);
    }

    /**
     * @Route("/attestation/create", name="attestation_create")
     */
    public function create(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $data = [
            'controller_name' => 'AttestationController',
            'action'          => 'select',
            'selectionRoute'  => 'attestation_create_source',
            'title'           => 'attestation.choose_source',
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'source.list', 'url' => $this->generateUrl('source_list')],
                ['label' => 'attestation.create'],
                ['label' => 'source.select']
            ]
        ];

        if (($cloneId = $request->query->get('cloneFrom', null)) !== null) {
            $data['cloneFrom'] = $cloneId;
        }

        return $this->render('source/index.html.twig', $data);
    }

    /**
     * @Route("/attestation/create/{source_id}", name="attestation_create_source")
     */
    public function createForSource($source_id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $user = $this->getUser();

        $source = $doctrine
            ->getRepository(Source::class)
            ->find(intval($source_id));
        if (is_null($source)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('source.messages.missing', ['%id%' => $source_id])
            );
            return $this->redirectToRoute('attestation_list');
        }

        if (($cloneId = $request->query->get('cloneFrom', null)) !== null) {
            $attestation = $doctrine
                ->getRepository(Attestation::class)
                ->find(intval($cloneId));

            if (is_null($attestation)) {
                $request->getSession()->getFlashBag()->add(
                    'error',
                    $translator->trans('attestation.messages.missing', ['%id%' => $cloneId])
                );
                return $this->redirectToRoute('attestation_list');
            }
            $attestation = clone $attestation;
            $clone = true;
        } else {

            $attestation = new Attestation();
            $etatFiche = $doctrine
                ->getRepository(EtatFiche::class)
                ->find(1);
            $attestation->setEtatFiche($etatFiche);

            $clone = false;
        }

        $attestation->setSource($source);

        if ($source->getVerrou() === null) {
            $verrou = $doctrine->getRepository(VerrouEntite::class)->create($source, $user, $this->dureeVerrou);
        } else if (!$source->getVerrou()->isWritable($user)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('generic.messages.error_locked', [
                    '%type%' => $translator->trans('source.name'),
                    '%id%'   => $source_id,
                    '%user%' => $source->getVerrou()->getCreateur()->getPrenomNom(),
                    '%time%' => $source->getVerrou()->getDateFin()->format(
                        $translator->trans('locale_datetime')
                    )
                ])
            );
            return $this->redirectToRoute('attestation_create');
        }

        $form   = $this->createForm(AttestationType::class, $attestation, [
            'formAction'   => 'create',
            'source'       => $source,
            'attestation'  => $attestation,
            'isClone'      => $clone,
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple'),
                'formule.undetermined'         => $translator->trans('formule.messages.undetermined')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $attestation->setCreateur($user);
            $attestation->setDernierEditeur($user);
            // Sauvegarde
            $em = $doctrine->getManager();
            $em->persist($attestation);
            foreach ($attestation->getAttestationMateriels() as $am) {
                if ($am->getMateriel() !== null || $am->getCategorieMateriel() !== null) {
                    $am->setAttestation($attestation);
                    $em->persist($am);
                } else {
                    $attestation->removeAttestationMateriel($am);
                }
            }
            foreach ($attestation->getAttestationOccasions() as $ao) {
                if ($ao->getOccasion() !== null || $ao->getCategorieOccasion() !== null) {
                    $ao->setAttestation($attestation);
                    $em->persist($ao);
                } else {
                    $attestation->removeAttestationOccasion($ao);
                }
            }
            foreach ($attestation->getAgents() as $a) {
                if (!$a->isBlank()) {
                    $a->setAttestation($attestation);
                    $em->persist($a);
                } else {
                    $attestation->removeAgent($a);
                    $em->remove($a);
                }
            }
            foreach ($attestation->getContientElements() as $ce) {
                if ($ce->getElement() !== null) {
                    if (!$em->contains($ce->getElement())) {
                        $ce->getElement()->setCreateur($user);
                        $ce->getElement()->setDernierEditeur($user);
                        $em->persist($ce->getElement());
                    }
                    $ce->setAttestation($attestation);
                    $em->persist($ce);
                } else {
                    $attestation->removeContientElement($ce);
                }
            }

            if ($attestation->getEstDatee() !== true || $attestation->getDatation()->isEmpty()) {
                $attestation->setDatation(null);
                $attestation->setEstDatee(false);
            }

            $doctrine->getRepository(VerrouEntite::class)->remove($source->getVerrou());
            $em->flush();

            // Message de confirmation
            $request->getSession()->getFlashBag()->add('success', 'attestation.messages.created');
            if ($request->request->has("saveclose")) {
                return $this->redirectToRoute('attestation_list');
            }
            return $this->redirectToRoute('attestation_edit', ['id' => $attestation->getId()]);
        }

        return $this->render('attestation/edit.html.twig', [
            'controller_name' => 'AttestationController',
            'action'          => 'create',
            'locale'          => $request->getLocale(),
            'form'            => $form->createView(),
            'source'          => $source,
            'attestation'     => $attestation,
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'source.list', 'url' => $this->generateUrl('source_list')],
                ['label' => $translator->trans('attestation.create_for_source', ['%id%' => $source_id])]
            ]
        ]);
    }

    /**
     * @Route("/attestation/cancelcreate/{source_id}", name="attestation_cancelcreate")
     */
    public function cancelcreate($source_id, Request $request, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $user = $this->getUser();
        $source = $doctrine
            ->getRepository(Source::class)
            ->find(intval($source_id));
        $verrou = $source->getVerrou();
        if ($verrou !== null && $verrou->isWritable($user)) {
            $doctrine->getRepository(VerrouEntite::class)->remove($verrou);
        }
        return $this->redirectToRoute('attestation_list');
    }


    /**
     * @Route("/attestation/{id}", name="attestation_show")
     */
    public function show($id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $attestation = $doctrine
            ->getRepository(Attestation::class)
            ->find(intval($id));
        if (is_null($attestation)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('attestation.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('attestation_list');
        }

        $valid_attestations = $doctrine->getRepository(IndexRecherche::class)->getEntityIds('Attestation', !$this->isGranted('ROLE_CONTRIBUTOR'));

        return $this->render('attestation/show.html.twig', [
            'controller_name'    => 'AttestationController',
            'attestation'        => $attestation,
            'valid_attestations' => $valid_attestations,
            'locale'             => $request->getLocale(),
            'breadcrumbs'        => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'attestation.list', 'url' => $this->generateUrl('attestation_list')],
                ['label' => $translator->trans('attestation.view', ['%id%' => $id])]
            ]
        ]);
    }

    /**
     * @Route("/attestation/{id}/edit", name="attestation_edit")
     */
    public function edit($id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $user = $this->getUser();

        $attestation = $doctrine
            ->getRepository(Attestation::class)
            ->find(intval($id));

        if (is_null($attestation)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('attestation.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('attestation_list');
        }
        if (!$this->isGranted('ROLE_MODERATOR') && $attestation->getCreateur()->getId() !== $user->getId()) {
            $request->getSession()->getFlashBag()->add('error', 'generic.messages.error_unauthorized');
            return $this->redirectToRoute('attestation_list');
        }
        $source = $attestation->getSource();
        if ($source->getVerrou() === null) {
            $verrou = $doctrine->getRepository(VerrouEntite::class)->create($source, $user, $this->dureeVerrou);
        } else if (!$source->getVerrou()->isWritable($user)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('generic.messages.error_locked', [
                    '%type%' => $translator->trans('attestation.name'),
                    '%id%' => $id,
                    '%user%' => $source->getVerrou()->getCreateur()->getPrenomNom(),
                    '%time%' => $source->getVerrou()->getDateFin()->format(
                        $translator->trans('locale_datetime')
                    )
                ])
            );
            return $this->redirectToRoute('attestation_list');
        }

        $contientElements = new ArrayCollection();

        // Create an ArrayCollection of the current ContientElement objects in the database
        foreach ($attestation->getContientElements() as $ce) {
            $contientElements->add($ce);
        }

        $form   = $this->createForm(AttestationType::class, $attestation, [
            'formAction'   => 'edit',
            'source'       => $attestation->getSource(),
            'attestation'  => $attestation,
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple'),
                'formule.undetermined'         => $translator->trans('formule.messages.undetermined')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $doctrine->getManager();
            $attestation->setDernierEditeur($user);
            foreach ($attestation->getAttestationMateriels() as $am) {
                if ($am->getMateriel() !== null || $am->getCategorieMateriel() !== null) {
                    $am->setAttestation($attestation);
                    if (!$em->contains($am)) {
                        $em->persist($am);
                    }
                } else {
                    $attestation->removeAttestationMateriel($am);
                    if ($em->contains($am)) {
                        $em->remove($am);
                    }
                }
            }
            foreach ($attestation->getAttestationOccasions() as $am) {
                if ($am->getOccasion() !== null || $am->getCategorieOccasion() !== null) {
                    $am->setAttestation($attestation);
                    if (!$em->contains($am)) {
                        $em->persist($am);
                    }
                } else {
                    $attestation->removeAttestationOccasion($am);
                    if ($em->contains($am)) {
                        $em->remove($am);
                    }
                }
            }
            foreach ($attestation->getAgents() as $a) {
                if (!$a->isBlank()) {
                    $a->setAttestation($attestation);
                    if (!$em->contains($a)) {
                        $em->persist($a);
                    }
                } else {
                    $attestation->removeAgent($a);
                    if ($em->contains($a)) {
                        $em->remove($a);
                    }
                }
            }
            foreach ($attestation->getContientElements() as $ce) {
                if ($ce->getElement() !== null) {
                    if (!$em->contains($ce->getElement())) {
                        $ce->getElement()->setCreateur($user);
                        $ce->getElement()->setDernierEditeur($user);
                        $em->persist($ce->getElement());
                    }
                    $ce->setAttestation($attestation);
                    $em->persist($ce);
                } else {
                    $attestation->removeContientElement($ce);
                }
            }
            foreach ($contientElements as $ce) {
                if (false === $attestation->getContientElements()->contains($ce)) {
                    $em->remove($ce);
                }
            }
            // Renumber elements
            $cpt = 1;
            foreach ($attestation->getContientElements() as $ce) {
                $ce->setPositionElement($cpt);
                $cpt++;
            }

            foreach ($attestation->getFormules() as $f) {
                if (!empty($f->getFormule())) {
                    if (!$em->contains($f)) {
                        $f->setCreateur($user);
                        $f->setAttestation($attestation);
                        $em->persist($f);
                    }
                } else {
                    $attestation->removeFormule($f);
                }
            }

            if ($attestation->getEstDatee() !== true || $attestation->getDatation()->isEmpty()) {
                $attestation->setDatation(null);
                $attestation->setEstDatee(false);
            }

            $doctrine->getRepository(VerrouEntite::class)->remove($source->getVerrou());
            $em->flush();

            // Message de confirmation
            $request->getSession()->getFlashBag()->add('success', 'attestation.messages.edited');
            if ($request->request->has("saveclose")) {
                return $this->redirectToRoute('attestation_list');
            }
            return $this->redirectToRoute('attestation_edit', ['id' => $attestation->getId()]);
        }

        return $this->render('attestation/edit.html.twig', [
            'controller_name' => 'AttestationController',
            'action'          => 'edit',
            'attestation'     => $attestation,
            'locale'          => $request->getLocale(),
            'form'            => $form->createView(),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'attestation.list', 'url' => $this->generateUrl('attestation_list')],
                ['label' => $translator->trans('attestation.edit', ['%id%' => $id])]
            ]
        ]);
    }

    /**
     * @Route("/attestation/{id}/canceledit", name="attestation_canceledit")
     */
    public function canceledit($id, Request $request, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $user = $this->getUser();
        $attestation = $doctrine
            ->getRepository(Attestation::class)
            ->find(intval($id));
        $verrou = $attestation->getSource()->getVerrou();
        if ($verrou !== null && $verrou->isWritable($user)) {
            $doctrine->getRepository(VerrouEntite::class)->remove($verrou);
        }
        return $this->redirectToRoute('attestation_list');
    }

    /**
     * @Route("/attestation/{id}/delete", name="attestation_delete")
     */
    public function delete($id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $submittedToken = $request->request->get('token');
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('delete_attestation', $submittedToken)) {
            $repository = $doctrine->getRepository(Attestation::class);
            $attestation = $repository->find(intval($id));
            if ($attestation instanceof Attestation) {
                if ($this->isGranted('ROLE_ADMIN')) {
                    $user = $this->getUser();
                    $verrou = $attestation->getSource()->getVerrou();
                    if (!$verrou || $verrou->isWritable($user)) {
                        $em = $doctrine->getManager();
                        $em->remove($attestation);
                        $em->flush();
                        $request->getSession()->getFlashBag()->add('success', 'attestation.messages.deleted');
                    } else {
                        $request->getSession()->getFlashBag()->add(
                            'error',
                            $translator->trans('generic.messages.error_locked', [
                                '%type%' => $translator->trans('attestation.name'),
                                '%id%' => $id,
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
        return $this->redirectToRoute('attestation_list');
    }
}
