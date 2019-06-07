<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

use App\Entity\CategorieSource;
use App\Entity\EtatFiche;
use App\Entity\Source;
use App\Entity\SourceBiblio;
use App\Entity\VerrouEntite;
use App\Form\SourceType;

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
        $sources = $this->getDoctrine()
                        ->getRepository(Source::class)
                        ->findAll();

        return $this->render('source/index.html.twig', [
            'controller_name' => 'SourceController',
            'sources'         => $sources,
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'source.list']
            ]
        ]);
    }


    /**
     * @Route("/source/create", name="source_create")
     */
    public function create(Request $request, TranslatorInterface $translator){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $source = new Source();
        $catSource = $this->getDoctrine()
                       ->getRepository(CategorieSource::class)
                       ->findOneBy(['nomEn' => 'Epigraphy']);
        $source->setCategorieSource($catSource);

        $form   = $this->get('form.factory')->create(SourceType::class, $source, [
            'action'       => 'create',
            'user'         => $user,
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $source->setCreateur($user);
            $source->setDernierEditeur($user);
            // Sauvegarde
            $em = $this->getDoctrine()->getManager();
            $em->persist($source);
            foreach($source->getSourceBiblios() as $sb){
                if($sb->getBiblio() !== null){
                    $em->persist($sb->getBiblio());
                    $sb->setSource($source);
                    $em->persist($sb);
                } else {
                    $source->removeSourceBiblio($sb);
                }
            }
            if(!empty($source->getAttestations())) {
                foreach($source->getAttestations() as $a){
                    if(!empty($a->getPassage())){
                        // Persist only valid data
                        $a->setSource($source);
                        $a->setCreateur($user);
                        $a->setDernierEditeur($user);
                        $etatFiche = $this->getDoctrine()
                            ->getRepository(EtatFiche::class)
                            ->find(1);
                        $a->setEtatFiche($etatFiche);
                        $em->persist($a);
                    } else {
                        $source->removeAttestation($a);
                    }
                }
            }
            if($source->getInSitu() === true){
                $source->setLieuOrigine(null);
            }
            if($source->getEstDatee() !== true){
                $source->setDatation(null);
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
    public function show($id, Request $request, TranslatorInterface $translator){
        $source = $this->getDoctrine()
                       ->getRepository(Source::class)
                       ->find($id);
        if(is_null($source)){
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
    public function edit($id, Request $request, TranslatorInterface $translator){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $source = $this->getDoctrine()
                       ->getRepository(Source::class)
                       ->find($id);

        if(is_null($source)){
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('source.messages.missing', ['%id%' => $id])
            );
            return $this->redirectToRoute('source_list');
        }
        if($source->getVerrou() === null){
            $verrou = $this->getDoctrine()->getRepository(VerrouEntite::class)->create($source, $user, $this->dureeVerrou);
        }
        else if(!$source->getVerrou()->isWritable($user))
        {
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

        $form   = $this->get('form.factory')->create(SourceType::class, $source, [
            'action'       => 'edit',
            'user'         => $user,
            'locale'       => $request->getLocale(),
            'translations' => [
                'autocomplete.select_element'  => $translator->trans('autocomplete.select_element'),
                'autocomplete.select_multiple' => $translator->trans('autocomplete.select_multiple')
            ]
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $source->setDernierEditeur($user);
            // Sauvegarde
            $em = $this->getDoctrine()->getManager();
            foreach($source->getSourceBiblios() as $sb){
                if($sb->getBiblio() !== null){
                    if(!$em->contains($sb->getBiblio())){
                        $em->persist($sb->getBiblio());
                    }
                    $sb->setSource($source);
                    if(!$em->contains($sb)){
                        $em->persist($sb);
                    }
                }
                else {
                    $source->removeSourceBiblio($sb);
                }
            }
            if(!empty($source->getAttestations())) {
                foreach($source->getAttestations() as $a){
                    // Don't persist/remove already persisted entities
                    if(!$em->contains($a)){
                        // If it's not persisted, we check the contents of the "passage" field
                        // to determine if it's valid or not
                        if(!empty($a->getPassage())){
                            $a->setSource($source);
                            $a->setCreateur($user);
                            $a->setDernierEditeur($user);
                            $etatFiche = $this->getDoctrine()
                                ->getRepository(EtatFiche::class)
                                ->find(1);
                            $a->setEtatFiche($etatFiche);
                            $em->persist($a);
                        }
                        else {

                        }
                    }
                }
            }
            if($source->getInSitu() === true){
                $source->setLieuOrigine(null);
            }
            if($source->getEstDatee() !== true){
                $source->setDatation(null);
            }
            $this->getDoctrine()->getRepository(VerrouEntite::class)->remove($source->getVerrou());
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
    public function canceledit($id, Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $source = $this->getDoctrine()
                       ->getRepository(Source::class)
                       ->find($id);
        $verrou = $this->getDoctrine()->getRepository(VerrouEntite::class)->fetch($source);
        if($verrou !== null && $verrou->isWritable($user)){
            $this->getDoctrine()->getRepository(VerrouEntite::class)->remove($verrou);
        }
        return $this->redirectToRoute('source_list');
    }

    /**
     * @Route("/source/{id}/delete", name="source_delete")
     */
    public function delete($id, Request $request, TranslatorInterface $translator){
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete_source_'.$id, $submittedToken)) {
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $repository = $this->getDoctrine()->getRepository(Source::class);
            $source = $repository->find($id);
            if($source instanceof Source){
                $verrou = $this->getDoctrine()->getRepository(VerrouEntite::class)->fetch($source);
                if(!$verrou || !$verrou->isWritable($user))
                {
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
                    return $this->redirectToRoute('source_list');
                }

                $em = $this->getDoctrine()->getManager();
                $em->remove($source);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'source.messages.deleted');
                return $this->redirectToRoute('source_list');
            } else {
                $request->getSession()->getFlashBag()->add('error', 'generic.messages.deletion_failed_missing');
                return $this->redirectToRoute('source_list');
            }
        } else {
            $request->getSession()->getFlashBag()->add('error', 'generic.messages.deletion_failed_csrf');
            return $this->redirectToRoute('source_list');
        }
    }
}
