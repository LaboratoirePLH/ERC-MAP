<?php

namespace App\Controller;

use App\Entity\Biblio;
use App\Form\BiblioType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class BibliographyController extends AbstractController
{
    /**
     * @Route("/bibliography", name="bibliography_list")
     */
    public function index()
    {
        $biblios = $this->getDoctrine()
                        ->getRepository(Biblio::class)
                        ->findAll();
        return $this->render('bibliography/index.html.twig', [
            'controller_name' => 'BibliographyController',
            'biblios'         => $biblios
        ]);
    }

    /**
     * @Route("/bibliography/create", name="bibliography_create")
     */
    public function create(Request $request, TranslatorInterface $translator)
    {
        $biblio = new Biblio();

        $form   = $this->get('form.factory')->create(BiblioType::class, $biblio, [
            'action'       => 'create',
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
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
        ]);
    }

    /**
     * @Route("/bibliography/{id}", name="bibliography_show")
     */
    public function show($id)
    {
        $bibliography = $this->getDoctrine()
                       ->getRepository(Biblio::class)
                       ->find($id);
        if(is_null($bibliography)){
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('biblio.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('bibliography_list');
        }

        return $this->render('bibliography/show.html.twig', [
            'controller_name' => 'BibliographyController',
            'bibliography'    => $bibliography,
            'locale'          => $request->getLocale()
        ]);
    }

    /**
     * @Route("/bibliography/{id}/edit", name="bibliography_edit")
     */
    public function edit($id, Request $request, TranslatorInterface $translator)
    {
        $biblio = $this->getDoctrine()
                       ->getRepository(Biblio::class)
                       ->find($id);
        if(is_null($biblio)){
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('biblio.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('bibliography_list');
        }

        $form   = $this->get('form.factory')->create(BiblioType::class, $biblio, [
            'action'       => 'edit',
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($biblio);
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
            'action'          => 'edit',
            'locale'          => $request->getLocale(),
            'form'            => $form->createView(),
        ]);
    }

    /**
     * @Route("/bibliography/{id}/delete", name="bibliography_delete")
     */
    public function delete($id, Request $request)
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete_biblio_'.$id, $submittedToken)) {
            $repository = $this->getDoctrine()->getRepository(Biblio::class);
            $biblio = $repository->find($id);
            if($biblio instanceof Biblio){
                $em = $this->getDoctrine()->getManager();
                $em->remove($biblio);
                $em->flush();

                $request->getSession()->getFlashBag()->add('error', 'biblio.messages.deleted');
            } else {
                $request->getSession()->getFlashBag()->add('error', 'generic.messages.deletion_failed_missing');
            }
        } else {
            $request->getSession()->getFlashBag()->add('error', 'generic.messages.deletion_failed_csrf');
        }
        return $this->redirectToRoute('bibliography_list');
    }
}
