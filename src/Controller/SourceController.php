<?php

namespace App\Controller;

use App\Entity\CategorieSource;
use App\Entity\Element;
use App\Entity\EtatFiche;
use App\Entity\Source;
use App\Entity\VerrouEntite;
use App\Form\SourceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class SourceController extends AbstractController
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
     * @Route("/source", name="source_list")
     */
    public function index()
    {
        return $this->render('source/index.html.twig', [
            'controller_name' => 'SourceController',
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'source.list']
            ]
        ]);
    }

    /**
     * @Route("/source/element/{element_id}", name="source_element")
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
            return $this->redirectToRoute('source_list');
        }

        return $this->render('source/index.html.twig', [
            'controller_name' => 'SourceController',
            'element'         => $element_id,
            'title'           => $translator->trans('source.list_for_element', ['%id%' => $element_id, '%name%' => $element->getEtatAbsolu()]),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => $translator->trans('source.list_for_element', ['%id%' => $element_id])]
            ]
        ]);
    }

    /**
     * @Route("/source/element/{element_id}/webmapping", name="source_element_webmapping")
     */
    public function indexForElementWebmapping($element_id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $element = $doctrine
            ->getRepository(Element::class)
            ->find(intval($element_id));
        if (is_null($element)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('element.messages.missing', ['%id%' => $element_id])
            );
            return $this->redirectToRoute('source_list');
        }

        return $this->render('source/webmapping.html.twig', [
            'controller_name' => 'SourceController',
            'element'         => $element_id,
            'title'           => $translator->trans('source.webmapping_for_element', ['%id%' => $element_id, '%name%' => $element->getEtatAbsolu()]),
            'webmapping'      => [
                'app_url'     => $this->getParameter('geo.app_url_' . $request->getLocale()),
                'function_id' => $this->getParameter('geo.function_source_' . $request->getLocale())
            ],
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => $translator->trans('source.list_for_element', ['%id%' => $element_id]), 'url' => $this->generateUrl('source_element', ['element_id' => $element_id])],
                ['label' => 'generic.webmapping']
            ]
        ]);
    }

    /**
     * @Route("/source/create", name="source_create")
     */
    public function create(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $user = $this->getUser();

        if (($cloneId = $request->query->get('cloneFrom', null)) !== null) {
            $source = $doctrine
                ->getRepository(Source::class)
                ->find(intval($cloneId));

            if (is_null($source)) {
                $request->getSession()->getFlashBag()->add(
                    'error',
                    $translator->trans('source.messages.missing', ['%id%' => $cloneId])
                );
                return $this->redirectToRoute('source_list');
            }
            $source = clone $source;
            $clone = true;
        } else {
            $source = new Source();
            $catSource = $doctrine
                ->getRepository(CategorieSource::class)
                ->findOneBy(['nomEn' => 'Epigraphy']);
            $source->setCategorieSource($catSource);
            $clone = false;
        }

        $form   = $this->createForm(SourceType::class, $source, [
            'formAction'   => 'create',
            'isClone'      => $clone,
            'user'         => $user,
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $source->setCreateur($user);
            $source->setDernierEditeur($user);
            // Sauvegarde
            $em = $doctrine->getManager();
            $em->persist($source);
            foreach ($source->getSourceBiblios() as $sb) {
                if ($sb->getBiblio() !== null) {
                    $em->persist($sb->getBiblio());
                    $sb->setSource($source);
                    $em->persist($sb);
                } else {
                    $source->removeSourceBiblio($sb);
                }
            }
            if (!empty($source->getAttestations())) {
                foreach ($source->getAttestations() as $a) {
                    if (!empty($a->getPassage())) {
                        // Persist only valid data
                        $a->setSource($source);
                        $a->setCreateur($user);
                        $a->setDernierEditeur($user);
                        $etatFiche = $doctrine
                            ->getRepository(EtatFiche::class)
                            ->find(1);
                        $a->setEtatFiche($etatFiche);
                        $em->persist($a);
                    } else {
                        $source->removeAttestation($a);
                    }
                }
            }
            if ($source->getEstDatee() !== true || $source->getDatation()->isEmpty()) {
                $source->setDatation(null);
                $source->setEstDatee(false);
            }
            $em->flush();

            // Message de confirmation
            $request->getSession()->getFlashBag()->add('success', 'source.messages.created');
            if ($request->request->has("saveclose")) {
                return $this->redirectToRoute('source_list');
            }
            return $this->redirectToRoute('source_edit', ['id' => $source->getId()]);
        }

        return $this->render('source/edit.html.twig', [
            'controller_name' => 'SourceController',
            'action'          => 'create',
            'locale'          => $request->getLocale(),
            'form'            => $form->createView(),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'source.list', 'url' => $this->generateUrl('source_list')],
                ['label' => 'source.create']
            ]
        ]);
    }

    /**
     * @Route("/source/{id}", name="source_show")
     */
    public function show($id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $source = $doctrine
            ->getRepository(Source::class)
            ->find(intval($id));
        if (is_null($source)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('source.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('source_list');
        }

        return $this->render('source/show.html.twig', [
            'controller_name' => 'SourceController',
            'source'          => $source,
            'locale'          => $request->getLocale(),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'source.list', 'url' => $this->generateUrl('source_list')],
                ['label' => $translator->trans('source.view', ['%id%' => $source->getId()])]
            ]
        ]);
    }

    /**
     * @Route("/source/{id}/edit", name="source_edit")
     */
    public function edit($id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $user = $this->getUser();

        $source = $doctrine
            ->getRepository(Source::class)
            ->find(intval($id));

        if (is_null($source)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('source.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('source_list');
        }
        if (!$this->isGranted('ROLE_MODERATOR') && $source->getCreateur()->getId() !== $user->getId()) {
            $request->getSession()->getFlashBag()->add('error', 'generic.messages.error_unauthorized');
            return $this->redirectToRoute('source_list');
        }
        if ($source->getVerrou() === null) {
            $verrou = $doctrine->getRepository(VerrouEntite::class)->create($source, $user, $this->dureeVerrou);
        } else if (!$source->getVerrou()->isWritable($user)) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('generic.messages.error_locked', [
                    '%type%' => $translator->trans('source.name'),
                    '%id%' => $id,
                    '%user%' => $source->getVerrou()->getCreateur()->getPrenomNom(),
                    '%time%' => $source->getVerrou()->getDateFin()->format(
                        $translator->trans('locale_datetime')
                    )
                ])
            );
            return $this->redirectToRoute('source_list');
        }

        $form   = $this->createForm(SourceType::class, $source, [
            'formAction'   => 'edit',
            'user'         => $user,
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $source->setDernierEditeur($user);
            // Sauvegarde
            $em = $doctrine->getManager();
            foreach ($source->getSourceBiblios() as $sb) {
                if ($sb->getBiblio() !== null) {
                    if (!$em->contains($sb->getBiblio())) {
                        $em->persist($sb->getBiblio());
                    }
                    $sb->setSource($source);
                    if (!$em->contains($sb)) {
                        $em->persist($sb);
                    }
                } else {
                    $source->removeSourceBiblio($sb);
                }
            }
            if (!empty($source->getAttestations())) {
                foreach ($source->getAttestations() as $a) {
                    // Don't persist/remove already persisted entities
                    if (!$em->contains($a)) {
                        // If it's not persisted, we check the contents of the "passage" field
                        // to determine if it's valid or not
                        if (!empty($a->getPassage())) {
                            $a->setSource($source);
                            $a->setCreateur($user);
                            $a->setDernierEditeur($user);
                            $etatFiche = $doctrine
                                ->getRepository(EtatFiche::class)
                                ->find(1);
                            $a->setEtatFiche($etatFiche);
                            $em->persist($a);
                        } else {
                        }
                    }
                }
            }
            if ($source->getEstDatee() !== true || $source->getDatation()->isEmpty()) {
                $source->setDatation(null);
                $source->setEstDatee(false);
            }
            $doctrine->getRepository(VerrouEntite::class)->remove($source->getVerrou());
            $em->flush();

            // Message de confirmation
            $request->getSession()->getFlashBag()->add('success', 'source.messages.edited');
            if ($request->request->has("saveclose")) {
                return $this->redirectToRoute('source_list');
            }
            return $this->redirectToRoute('source_edit', ['id' => $source->getId()]);
        }

        return $this->render('source/edit.html.twig', [
            'controller_name' => 'SourceController',
            'action'          => 'edit',
            'source'          => $source,
            'locale'          => $request->getLocale(),
            'form'            => $form->createView(),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'source.list', 'url' => $this->generateUrl('source_list')],
                ['label' => $translator->trans('source.edit', ['%id%' => $source->getId()])]
            ]
        ]);
    }

    /**
     * @Route("/source/{id}/canceledit", name="source_canceledit")
     */
    public function canceledit($id, Request $request, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $user = $this->getUser();
        $source = $doctrine
            ->getRepository(Source::class)
            ->find(intval($id));
        $verrou = $doctrine->getRepository(VerrouEntite::class)->fetch($source);
        if ($verrou !== null && $verrou->isWritable($user)) {
            $doctrine->getRepository(VerrouEntite::class)->remove($verrou);
        }
        return $this->redirectToRoute('source_list');
    }

    /**
     * @Route("/source/{id}/delete", name="source_delete")
     */
    public function delete($id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted("ROLE_CONTRIBUTOR");

        $submittedToken = $request->request->get('token');
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('delete_source', $submittedToken)) {
            $user = $this->getUser();
            $repository = $doctrine->getRepository(Source::class);
            $source = $repository->find(intval($id));
            if ($source instanceof Source) {
                if ($this->isGranted('ROLE_ADMIN')) {
                    $verrou = $source->getVerrou();
                    if (!$verrou || $verrou->isWritable($user)) {
                        $em = $doctrine->getManager();
                        $em->remove($source);
                        $em->flush();
                        $request->getSession()->getFlashBag()->add('success', 'source.messages.deleted');
                    } else {
                        $request->getSession()->getFlashBag()->add(
                            'error',
                            $translator->trans('generic.messages.error_locked', [
                                '%type%' => $translator->trans('source.name'),
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
        return $this->redirectToRoute('source_list');
    }
}
