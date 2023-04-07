<?php

namespace App\Controller;

use App\Entity\Attestation;
use App\Entity\Element;
use App\Entity\IndexRecherche;
use App\Entity\VerrouEntite;
use App\Form\ElementType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

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
        return $this->render('element/index.html.twig', [
            'controller_name' => 'ElementController',
            'action'          => 'list',
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'element.list']
            ]
        ]);
    }

    /**
     * @Route("/element/attestation/{attestation_id}", name="element_attestation")
     */
    public function indexAttestation($attestation_id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $attestation = $doctrine
            ->getRepository(Attestation::class)
            ->find(intval($attestation_id));
        if (is_null($attestation)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('attestation.messages.missing', ['%id%' => $attestation_id])
            );
            return $this->redirectToRoute('attestation_list');
        }

        return $this->render('element/index.html.twig', [
            'controller_name' => 'ElementController',
            'action'          => 'list',
            'attestation'     => $attestation_id,
            'title'           => $translator->trans('element.list_for_attestation', ['%id%' => $attestation_id]),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'attestation.list', 'url' => $this->generateUrl('attestation_list')],
                ['label' => $translator->trans('element.list_for_attestation', ['%id%' => $attestation_id])]
            ]
        ]);
    }

    /**
     * @Route("/element/create", name="element_create")
     */
    public function create(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $user = $this->getUser();

        $element = new Element();

        $form   = $this->createForm(ElementType::class, $element, [
            'formAction'   => 'create',
            'element'      => $element,
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $doctrine->getManager();

            $element->setCreateur($user);
            $element->setDernierEditeur($user);
            // Sauvegarde
            $em = $doctrine->getManager();
            $em->persist($element);

            foreach ($element->getElementBiblios() as $eb) {
                if ($eb->getBiblio() !== null) {
                    $em->persist($eb->getBiblio());
                    $eb->setElement($element);
                    $em->persist($eb);
                } else {
                    $element->removeElementBiblio($eb);
                }
            }
            foreach ($element->getTheonymesImplicites() as $ti) {
                if ($ti !== null && !$em->contains($ti)) {
                    if ($ti->getEtatAbsolu() !== null) {
                        $ti->setCreateur($user);
                        $ti->setDernierEditeur($user);
                        $em->persist($ti);
                    } else {
                        $element->removeTheonymesImplicite($ti);
                    }
                }
            }
            foreach ($element->getTheonymesConstruits() as $tc) {
                if ($tc !== null && !$em->contains($tc)) {
                    if ($tc->getEtatAbsolu() !== null) {
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
            'element'         => $element,
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
    public function show($id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $element = $doctrine
            ->getRepository(Element::class)
            ->find(intval($id));

        if (is_null($element)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('element.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('element_list');
        }

        $valid_attestations = $doctrine->getRepository(IndexRecherche::class)->getEntityIds('Attestation', !$this->isGranted('ROLE_CONTRIBUTOR'));

        return $this->render('element/show.html.twig', [
            'controller_name'    => 'ElementController',
            'element'            => $element,
            'valid_attestations' => $valid_attestations,
            'locale'             => $request->getLocale(),
            'breadcrumbs'        => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'element.list', 'url' => $this->generateUrl('element_list')],
                ['label' => $translator->trans('element.view', ['%id%' => $element->getId()])]
            ]
        ]);
    }

    /**
     * @Route("/element/{id}/edit", name="element_edit")
     */
    public function edit($id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $user = $this->getUser();
        $element = $doctrine
            ->getRepository(Element::class)
            ->find(intval($id));
        if (is_null($element)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('element.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('element_list');
        }
        if (!$this->isGranted('ROLE_MODERATOR') && $element->getCreateur()->getId() !== $user->getId()) {
            $request->getSession()->getFlashBag()->add('error', 'generic.messages.error_unauthorized');
            return $this->redirectToRoute('element_list');
        }
        if ($element->getVerrou() === null) {
            $verrou = $doctrine->getRepository(VerrouEntite::class)->create($element, $user, $this->dureeVerrou);
        } else if (!$element->getVerrou()->isWritable($user)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('generic.messages.error_locked', [
                    '%type%' => $translator->trans('element.name'),
                    '%id%' => $id,
                    '%user%' => $element->getVerrou()->getCreateur()->getPrenomNom(),
                    '%time%' => $element->getVerrou()->getDateFin()->format(
                        $translator->trans('locale_datetime')
                    )
                ])
            );
            return $this->redirectToRoute('element_list');
        }

        $form   = $this->createForm(ElementType::class, $element, [
            'formAction'   => 'edit',
            'element'      => $element,
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $element->setDernierEditeur($user);
            // Sauvegarde
            $em = $doctrine->getManager();
            foreach ($element->getElementBiblios() as $sb) {
                if ($sb->getBiblio() !== null) {
                    if (!$em->contains($sb->getBiblio())) {
                        $em->persist($sb->getBiblio());
                    }
                    $sb->setElement($element);
                    if (!$em->contains($sb)) {
                        $em->persist($sb);
                    }
                } else {
                    $element->removeElementBiblio($sb);
                }
            }
            foreach ($element->getTheonymesImplicites() as $ti) {
                if ($ti !== null && !$em->contains($ti)) {
                    if ($ti->getEtatAbsolu() !== null) {
                        $ti->setCreateur($user);
                        $ti->setDernierEditeur($user);
                        $em->persist($ti);
                    } else {
                        $element->removeTheonymesImplicite($ti);
                    }
                }
            }
            foreach ($element->getTheonymesConstruits() as $tc) {
                if ($tc !== null && !$em->contains($tc)) {
                    if ($tc->getEtatAbsolu() !== null) {
                        $tc->setCreateur($user);
                        $tc->setDernierEditeur($user);
                        $em->persist($tc);
                    } else {
                        $element->removeTheonymesConstruit($tc);
                    }
                }
            }
            $doctrine->getRepository(VerrouEntite::class)->remove($element->getVerrou());
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
     * @Route("/element/{id}/canceledit", name="element_canceledit")
     */
    public function canceledit($id, Request $request, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $user = $this->getUser();
        $element = $doctrine
            ->getRepository(Element::class)
            ->find(intval($id));
        $verrou = $doctrine->getRepository(VerrouEntite::class)->fetch($element);
        if ($verrou !== null && $verrou->isWritable($user)) {
            $doctrine->getRepository(VerrouEntite::class)->remove($verrou);
        }
        return $this->redirectToRoute('element_list');
    }

    /**
     * @Route("/element/{id}/delete", name="element_delete")
     */
    public function delete($id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $submittedToken = $request->request->get('token');
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('delete_element', $submittedToken)) {
            $repository = $doctrine->getRepository(Element::class);
            $element = $repository->find(intval($id));
            if ($element instanceof Element) {
                if ($this->isGranted('ROLE_ADMIN')) {
                    $verrou = $element->getVerrou();
                    if (!$verrou || $verrou->isWritable($user)) {
                        $em = $doctrine->getManager();
                        $em->remove($element);
                        $em->flush();
                        $request->getSession()->getFlashBag()->add('success', 'element.messages.deleted');
                    } else {
                        $request->getSession()->getFlashBag()->add(
                            'error',
                            $translator->trans('generic.messages.error_locked', [
                                '%type%' => $translator->trans('element.name'),
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
        return $this->redirectToRoute('element_list');
    }
}
