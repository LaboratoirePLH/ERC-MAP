<?php

namespace App\Controller;

use App\Entity\Attestation;
// use App\Form\BiblioType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class AttestationController extends AbstractController
{
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
            'attestations'         => $attestations
        ]);
    }

    /**
     * @Route("/attestation/create", name="attestation_create")
     */
    public function create(Request $request, TranslatorInterface $translator)
    {
        $attestation = new Attestation();

        $form   = $this->get('form.factory')->create(AttestationType::class, $attestation, [
            'action'       => 'create',
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($attestation);
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
        ]);
    }

    /**
     * @Route("/attestation/{id}", name="attestation_show")
     */
    public function show($id)
    {
        $attestation = $this->getDoctrine()
                       ->getRepository(Attestation::class)
                       ->find($id);
        if(is_null($attestation)){
            $request->getSession()->getFlashBag()->add('error', 'attestation.messages.missing');
            return $this->redirectToRoute('attestation_list');
        }

        return $this->render('attestation/show.html.twig', [
            'controller_name' => 'AttestationController',
            'attestation'    => $attestation,
            'locale'          => $request->getLocale()
        ]);
    }

    /**
     * @Route("/attestation/{id}/edit", name="attestation_edit")
     */
    public function edit($id, Request $request, TranslatorInterface $translator)
    {
        $attestation = $this->getDoctrine()
                       ->getRepository(Attestation::class)
                       ->find($id);

        $form   = $this->get('form.factory')->create(AttestationType::class, $attestation, [
            'action'       => 'edit',
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($attestation);
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
            'locale'          => $request->getLocale(),
            'form'            => $form->createView(),
        ]);
    }

    /**
     * @Route("/attestation/{id}/delete", name="attestation_delete")
     */
    public function delete($id, Request $request)
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete_attestation_'.$id, $submittedToken)) {
            $repository = $this->getDoctrine()->getRepository(Attestation::class);
            $attestation = $repository->find($id);
            if($attestation instanceof Attestation){
                $em = $this->getDoctrine()->getManager();
                $em->remove($attestation);
                $em->flush();

                $request->getSession()->getFlashBag()->add('error', 'attestation.messages.deleted');
            } else {
                $request->getSession()->getFlashBag()->add('error', 'generic.messages.deletion_failed_missing');
            }
        } else {
            $request->getSession()->getFlashBag()->add('error', 'generic.messages.deletion_failed_csrf');
        }
        return $this->redirectToRoute('attestation_list');
    }
}
