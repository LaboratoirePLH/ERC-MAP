<?php

namespace App\Controller;

use App\Entity\Source;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListController extends AbstractController
{
    /**
     * @Route("/list/source", name="json_source_list")
     */
    public function source(Request $request)
    {
        $locale = $request->getLocale();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $sources = $this->getDoctrine()
            ->getRepository(Source::class)
            ->findAll();

        $data = array_map(function ($source) use ($locale, $user) {
            $lieu = $source->getInSitu() ? $source->getLieuDecouverte() : $source->getLieuOrigine();

            return [
                'id' => $source->getId(),
                'projet' => $source->getProjet()->getNom($locale),
                'version' => $source->getVersion(),
                'categorie' => $source->getCategorieSource()->getNom($locale),
                'types' => $source->getTypeSources()->map(function ($ts) use ($locale) {
                    return $ts->getNom($locale);
                }),
                'langues' => $source->getLangues()->map(function ($l) use ($locale) {
                    return $l->getNom($locale);
                }),
                'region' => $lieu !== null ? $lieu->getGrandeRegion()->getNom($locale) : '',
                'datation' => $source->getEstDatee() ? implode(' / ', [$source->getDatation()->getPostQuem() ?? '?', $source->getDatation()->getAnteQuem() ?? '?']) : '',
                'titre_abrege' => $source->getEditionPrincipaleBiblio()->getBiblio()->getTitreAbrege(),
                'reference' => $source->getEditionPrincipaleBiblio()->getReferenceSource(),
                'date_creation' => $source->getDateCreation(),
                'date_modification' => $source->getDateModification(),
                'createur' => $source->getCreateur()->getPrenomNom(),
                'editeur' => $source->getDernierEditeur()->getPrenomNom(),
                'traduire_fr' => $source->getTraduireFr(),
                'traduire_en' => $source->getTraduireEn(),
                'verrou' => ($source->getVerrou() !== null && !$source->getVerrou()->isWritable($user)) ? $source->getVerrou()->toArray() : false
            ];
        }, $sources);

        return new JsonResponse($data);
    }
}
