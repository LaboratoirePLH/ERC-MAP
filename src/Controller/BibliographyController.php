<?php

namespace App\Controller;

use App\Entity\Biblio;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class BibliographyController extends AbstractController
{
    /**
     * @Route("/bibliography", name="bibliography")
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
     * @Route("/bibliography/{id}", name="bibliography_show")
     */
    public function show($id){
        $bibliography = $this->getDoctrine()
                       ->getRepository(Biblio::class)
                       ->getRecord($id, true);
        if(is_null($bibliography)){
            $request->getSession()->getFlashBag()->add('error', 'biblio.messages.missing');
            return $this->redirectToRoute('bibliography');
        }

        return $this->render('bibliography/show.html.twig', [
            'controller_name' => 'BibliographyController',
            'bibliography'    => $bibliography,
            'locale'          => $request->getLocale()
        ]);
    }

    /**
     * @Route("/bibliography/create", name="bibliography_create")
     */
    public function create(Request $request, TranslatorInterface $translator){}

    /**
     * @Route("/bibliography/{id}/edit", name="bibliography_edit")
     */
    public function edit($id, Request $request, TranslatorInterface $translator){}

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
        return $this->redirectToRoute('bibliography');
    }
}
