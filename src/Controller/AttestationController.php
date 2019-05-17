<?php

namespace App\Controller;

use App\Entity\Attestation;
use App\Entity\EtatFiche;
use App\Entity\Source;
use App\Entity\VerrouEntite;
use App\Form\AttestationType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

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
        $attestations = $this->getDoctrine()
                        ->getRepository(Attestation::class)
                        ->findAll();
        return $this->render('attestation/index.html.twig', [
            'controller_name' => 'AttestationController',
            'action'          => 'list',
            'attestations'    => $attestations,
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'attestation.list']
            ]
        ]);
    }

    /**
     * @Route("/attestation/source/{source_id}", name="attestation_source")
     */
    public function indexSource($source_id, Request $request, TranslatorInterface $translator)
    {
        $source = $this->getDoctrine()
                        ->getRepository(Source::class)
                        ->find($source_id);
        if(is_null($source)){
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('source.messages.missing', ['%id%' => $source_id])
            );
            return $this->redirectToRoute('source_list');
        }

        return $this->render('attestation/index.html.twig', [
            'controller_name' => 'AttestationController',
            'action'          => 'list',
            'attestations'    => $source->getAttestations(),
            'source'          => $source,
            'title'           => $translator->trans('attestation.list_for_source', ['%id%' => $source_id]),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'source.list', 'url' => $this->generateUrl('source_list')],
                ['label' => $translator->trans('attestation.list_for_source', ['%id%' => $source_id])]
            ]
        ]);
    }

    /**
     * @Route("/attestation/create", name="attestation_create")
     */
    public function create(Request $request, TranslatorInterface $translator)
    {
        $sources = $this->getDoctrine()
                        ->getRepository(Source::class)
                        ->getSimpleList();

        return $this->render('source/index.html.twig', [
            'controller_name' => 'SourceController',
            'action'          => 'select',
            'selectionRoute'  => 'attestation_create_source',
            'title'           => 'attestation.choose_source',
            'sources'         => $sources,
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'source.list', 'url' => $this->generateUrl('source_list')],
                ['label' => 'attestation.create'],
                ['label' => 'source.select']
            ]
        ]);
    }

    /**
     * @Route("/attestation/create/{source_id}", name="attestation_create_source")
     */
    public function createForSource($source_id, Request $request, TranslatorInterface $translator)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $source = $this->getDoctrine()
        ->getRepository(Source::class)
        ->find($source_id);
        if(is_null($source)){
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('source.messages.missing', ['%id%' => $source_id])
            );
            return $this->redirectToRoute('attestation_list');
        }

        $verrou = $this->getDoctrine()->getRepository(VerrouEntite::class)->create($source, $user, $this->dureeVerrou);
        if(!$verrou->isWritable($user))
        {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('generic.messages.error_locked', [
                    '%type%' => $translator->trans('source.name'),
                    '%id%' => $source_id,
                    '%user%' => $verrou->getCreateur()->getPrenomNom(),
                    '%time%' => $verrou->getDateFin()->format(
                        $translator->trans('locale_datetime')
                    )
                ])
            );
            return $this->redirectToRoute('attestation_create');
        }

        $attestation = new Attestation();
        $attestation->setSource($source);
        $etatFiche = $this->getDoctrine()
                        ->getRepository(EtatFiche::class)
                        ->find(1);
        $attestation->setEtatFiche($etatFiche);

        $form   = $this->get('form.factory')->create(AttestationType::class, $attestation, [
            'source'       => $source,
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($attestation);

            $attestation->setCreateur($user);
            $attestation->setDernierEditeur($user);
            // Sauvegarde
            $em = $this->getDoctrine()->getManager();
            $em->persist($attestation);
            foreach($attestation->getAttestationMateriels() as $am){
                if($am->getMateriel() !== null){
                    $am->setAttestation($attestation);
                    $em->persist($am);
                } else {
                    $attestation->removeAttestationMateriel($am);
                }
            }
            foreach($attestation->getAgents() as $a){
                if($a->getDesignation() !== null){
                    $a->setAttestation($attestation);
                    $em->persist($a);
                } else {
                    $attestation->removeAgent($a);
                }
            }
            foreach($attestation->getContientElements() as $ce){
                if($ce->getElement() !== null){
                    if(!$em->contains($ce->getElement())){
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
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'source.list', 'url' => $this->generateUrl('source_list')],
                ['label' => $translator->trans('attestation.create_for_source', ['%id%' => $source_id])]
            ]
        ]);
    }

    /**
     * @Route("/attestation/{id}", name="attestation_show")
     */
    public function show($id, Request $request, TranslatorInterface $translator)
    {
        $attestation = $this->getDoctrine()
                       ->getRepository(Attestation::class)
                       ->find($id);
        if(is_null($attestation)){
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('attestation.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('attestation_list');
        }

        return $this->render('attestation/show.html.twig', [
            'controller_name' => 'AttestationController',
            'attestation'    => $attestation,
            'locale'          => $request->getLocale(),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'attestation.list', 'url' => $this->generateUrl('attestation_list')],
                ['label' => $translator->trans('attestation.view', ['%id%' => $id])]
            ]
        ]);
    }

    /**
     * @Route("/attestation/{id}/edit", name="attestation_edit")
     */
    public function edit($id, Request $request, TranslatorInterface $translator)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $attestation = $this->getDoctrine()
                       ->getRepository(Attestation::class)
                       ->find($id);

        if(is_null($attestation)){
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('attestation.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('attestation_list');
        }
        $verrou = $this->getDoctrine()->getRepository(VerrouEntite::class)->create($attestation, $user, $this->dureeVerrou);
        if(!$verrou->isWritable($user))
        {
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
            return $this->redirectToRoute('attestation_list');
        }

        $form   = $this->get('form.factory')->create(AttestationType::class, $attestation, [
            'source'       => $attestation->getSource(),
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $attestation->setDernierEditeur($user);
            foreach($attestation->getAttestationMateriels() as $am){
                if($am->getMateriel() !== null){
                    $am->setAttestation($attestation);
                    if(!$em->contains($am)){
                        $em->persist($am);
                    }
                } else {
                    $attestation->removeAttestationMateriel($am);
                    if($em->contains($am)){
                        $em->remove($am);
                    }
                }
            }
            foreach($attestation->getAgents() as $a){
                if($a->getDesignation() !== null){
                    $a->setAttestation($attestation);
                    if(!$em->contains($a)){
                        $em->persist($a);
                    }
                } else {
                    $attestation->removeAgent($a);
                    if($em->contains($a)){
                        $em->remove($a);
                    }
                }
            }
            foreach($attestation->getContientElements() as $ce){
                if($ce->getElement() !== null){
                    if(!$em->contains($ce->getElement())){
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

            foreach($attestation->getFormules() as $f){
                if(!empty($f->getFormule())){
                    if(!$em->contains($f)){
                        $f->setCreateur($user);
                        $f->setAttestation($attestation);
                        $em->persist($f);
                    }
                } else {
                    $attestation->removeFormule($f);
                }
            }
            $this->getDoctrine()->getRepository(VerrouEntite::class)->remove($verrou);
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
    public function canceledit($id, Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $attestation = $this->getDoctrine()
                            ->getRepository(Attestation::class)
                            ->find($id);
        $verrou = $this->getDoctrine()->getRepository(VerrouEntite::class)->fetch($attestation);
        if($verrou !== null && $verrou->isWritable($user)){
            $this->getDoctrine()->getRepository(VerrouEntite::class)->remove($verrou);
        }
        return $this->redirectToRoute('attestation_list');
    }

    /**
     * @Route("/attestation/{id}/delete", name="attestation_delete")
     */
    public function delete($id, Request $request, TranslatorInterface $translator)
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete_attestation_'.$id, $submittedToken)) {
            $repository = $this->getDoctrine()->getRepository(Attestation::class);
            $attestation = $repository->find($id);
            if($attestation instanceof Attestation){
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $verrou = $this->getDoctrine()->getRepository(VerrouEntite::class)->fetch($attestation);
                if(!$verrou || !$verrou->isWritable($user)) {
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
                } else {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($attestation);
                    $em->flush();
                    $request->getSession()->getFlashBag()->add('success', 'attestation.messages.deleted');
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
