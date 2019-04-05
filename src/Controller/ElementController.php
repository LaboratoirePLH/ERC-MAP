<?php

namespace App\Controller;

use App\Entity\Element;
use App\Entity\VerrouEntite;
use App\Form\ElementType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class ElementController extends AbstractController
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
     * @Route("/element", name="element_list")
     */
    public function index()
    {
        $elements = $this->getDoctrine()
                        ->getRepository(Element::class)
                        ->findAll();
        return $this->render('element/index.html.twig', [
            'controller_name' => 'ElementController',
            'action'          => 'list',
            'elements'        => $elements,
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'element.list']
            ]
        ]);
    }

    /**
     * @Route("/element/create", name="element_create")
     */
    public function create(Request $request, TranslatorInterface $translator)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $element = new Element();
        $element->setAReference(true);

        $form   = $this->get('form.factory')->create(ElementType::class, $element, [
            'element'      => $element,
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();

            $element->setCreateur($user);
            $element->setDernierEditeur($user);
            // Sauvegarde
            $em = $this->getDoctrine()->getManager();
            $em->persist($element);

            foreach($element->getElementBiblios() as $eb){
                if($eb->getBiblio() !== null){
                    $em->persist($eb->getBiblio());
                    $eb->setElement($element);
                    $em->persist($eb);
                } else {
                    $element->removeElementBiblio($eb);
                }
            }
            foreach($element->getTheonymesImplicites() as $ti){
                if(!$em->contains($ti)){
                    if($ti->getEtatAbsolu() !== null){
                        $ti->setCreateur($user);
                        $ti->setDernierEditeur($user);
                        $em->persist($ti);
                    } else {
                        $element->removeTheonymesImplicite($ti);
                    }
                }
            }
            foreach($element->getTheonymesConstruits() as $tc){
                if(!$em->contains($tc)){
                    if($tc->getEtatAbsolu() !== null){
                        $tc->setCreateur($user);
                        $tc->setDernierEditeur($user);
                        $em->persist($tc);
                    } else {
                        $element->removeTheonymesConstruit($tc);
                    }
                }
            }

            $em->flush();

            // Message de confirmation
            $request->getSession()->getFlashBag()->add('success', 'element.messages.created');
            if ($request->request->has("saveclose")) {
                return $this->redirectToRoute('element_list');
            }
            return $this->redirectToRoute('element_edit', ['id' => $element->getId()]);
        }

        return $this->render('element/edit.html.twig', [
            'controller_name' => 'ElementController',
            'action'          => 'create',
            'locale'          => $request->getLocale(),
            'form'            => $form->createView(),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'element.list', 'url' => $this->generateUrl('element_list')],
                ['label' => 'element.create']
            ]
        ]);
    }

    /**
     * @Route("/element/{id}", name="element_show")
     */
    public function show($id){
        $element = $this->getDoctrine()
                       ->getRepository(Element::class)
                       ->getRecord($id, true);
        if(is_null($element)){
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('element.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('element_list');
        }

        return $this->render('element/show.html.twig', [
            'controller_name' => 'ElementController',
            'element'          => $element,
            'locale'          => $request->getLocale(),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'element.list', 'url' => $this->generateUrl('element_list')],
                ['label' => $translator->trans('element.view', ['%id%' => $element->getId()])]
            ]
        ]);
    }

    /**
     * @Route("/element/{id}/edit", name="element_edit")
     */
    public function edit($id, Request $request, TranslatorInterface $translator){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $element = $this->getDoctrine()
                       ->getRepository(Element::class)
                       ->find($id);
        if(is_null($element)){
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('element.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('element_list');
        }

        $form   = $this->get('form.factory')->create(ElementType::class, $element, [
            'element'      => $element,
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $element->setDernierEditeur($user);
            // Sauvegarde
            $em = $this->getDoctrine()->getManager();
            foreach($element->getElementBiblios() as $sb){
                if($sb->getBiblio() !== null){
                    if(!$em->contains($sb->getBiblio())){
                        $em->persist($sb->getBiblio());
                    }
                    $sb->setElement($element);
                    if(!$em->contains($sb)){
                        $em->persist($sb);
                    }
                }
                else {
                    $element->removeElementBiblio($sb);
                }
            }
            foreach($element->getTheonymesImplicites() as $ti){
                if(!$em->contains($ti)){
                    if($ti->getEtatAbsolu() !== null){
                        $ti->setCreateur($user);
                        $ti->setDernierEditeur($user);
                        $em->persist($ti);
                    } else {
                        $element->removeTheonymesImplicite($ti);
                    }
                }
            }
            foreach($element->getTheonymesConstruits() as $tc){
                if(!$em->contains($tc)){
                    if($tc->getEtatAbsolu() !== null){
                        $tc->setCreateur($user);
                        $tc->setDernierEditeur($user);
                        $em->persist($tc);
                    } else {
                        $element->removeTheonymesConstruit($tc);
                    }
                }
            }

            $em->flush();

            // Message de confirmation
            $request->getSession()->getFlashBag()->add('success', 'element.messages.edited');
            if ($request->request->has("saveclose")) {
                return $this->redirectToRoute('element_list');
            }
            return $this->redirectToRoute('element_edit', ['id' => $element->getId()]);
        }

        return $this->render('element/edit.html.twig', [
            'controller_name' => 'ElementController',
            'action'          => 'edit',
            'element'          => $element,
            'locale'          => $request->getLocale(),
            'form'            => $form->createView(),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'element.list', 'url' => $this->generateUrl('element_list')],
                ['label' => $translator->trans('element.edit', ['%id%' => $element->getId()])]
            ]
        ]);
    }

    /**
     * @Route("/element/{id}/delete", name="element_delete")
     */
    public function delete($id, Request $request){
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete_element_'.$id, $submittedToken)) {
            $repository = $this->getDoctrine()->getRepository(Element::class);
            $element = $repository->find($id);
            if($element instanceof Element){
                $em = $this->getDoctrine()->getManager();
                $em->remove($element);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'element.messages.deleted');
                return $this->redirectToRoute('element_list');
            } else {
                $request->getSession()->getFlashBag()->add('error', 'generic.messages.deletion_failed_missing');
                return $this->redirectToRoute('element_list');
            }
        } else {
            $request->getSession()->getFlashBag()->add('error', 'generic.messages.deletion_failed_csrf');
            return $this->redirectToRoute('element_list');
        }
    }
}
