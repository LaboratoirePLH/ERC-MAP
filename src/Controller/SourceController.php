<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

use App\Entity\Source;
use App\Form\SourceType;

class SourceController extends AbstractController
{
    /**
     * @Route("/source", name="source_list")
     */
    public function index()
    {
        $sources = $this->getDoctrine()
                        ->getRepository(Source::class)
                        ->getSimpleList();

        return $this->render('source/index.html.twig', [
            'controller_name' => 'SourceController',
            'sources' => $sources
        ]);
    }

    /**
     * @Route("/source/{id}", name="source_show")
     */
    public function show($id){
        echo "ok";
    }

    /**
     * @Route("/source/create", name="source_create")
     */
    public function create(Request $request, TranslatorInterface $translator){
        $source = new Source();
        $form   = $this->get('form.factory')->create(SourceType::class, $source, [
            'locale' => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element' => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            // Association de l'utilisateur
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $source->setCreateur($user);
            $source->setDernierEditeur($user);

            // Sauvegarde
            $em = $this->getDoctrine()->getManager();
            $em->persist($source);
            foreach($source->getSourceBiblios() as $sb){
                $em->persist($sb->getBiblio());
                $sb->setSource($source);
                $em->persist($sb);
            }
            $em->flush();

            // Message de confirmation
            $request->getSession()->getFlashBag()->add('success', 'source.messages.created');

            return $this->redirectToRoute('source_list');
        }

        return $this->render('source/edit.html.twig', [
            'controller_name' => 'SourceController',
            'action' => 'create',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/source/{id}/edit", name="source_edit")
     */
    public function edit($id, Request $request, TranslatorInterface $translator){
        $source = $this->getDoctrine()
                       ->getRepository(Source::class)
                       ->getRecord($id);

        $form   = $this->get('form.factory')->create(SourceType::class, $source, [
            'locale' => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element' => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            // Association de l'utilisateur
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $source->setCreateur($user);
            $source->setDernierEditeur($user);

            // Sauvegarde
            $em = $this->getDoctrine()->getManager();
            foreach($source->getSourceBiblios() as $sb){
                if(!$em->contains($sb->getBiblio())){
                    $em->persist($sb->getBiblio());
                }
                $sb->setSource($source);
                if(!$em->contains($sb)){
                    $em->persist($sb);
                }
            }
            $em->flush();

            // Message de confirmation
            $request->getSession()->getFlashBag()->add('success', 'source.messages.edited');

            return $this->redirectToRoute('source_list');
        }

        return $this->render('source/edit.html.twig', [
            'controller_name' => 'SourceController',
            'action' => 'edit',
            'source' => $source,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/source/{id}/delete", name="source_delete")
     */
    public function delete($id, Request $request){
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-source-'.$id, $submittedToken)) {
            echo "ok pour delete";
        } else {
            $request->getSession()->getFlashBag()->add('error', 'source.messages.deletion_failed');

            return $this->redirectToRoute('source_list');
        }
    }
}
