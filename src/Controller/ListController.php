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
    public function source(Request $request, TranslatorInterface $translator)
    {
        $locale = $request->getLocale();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $sources = $this->getDoctrine()
            ->getRepository(Source::class)
            ->findAll();

        $data = array_map(function ($source) use ($locale, $user, $translator) {
            $lieu = $source->getInSitu() ? $source->getLieuDecouverte() : $source->getLieuOrigine();
            $region = ($lieu !== null && $lieu->getGrandeRegion() !== null) ? $lieu->getGrandeRegion()->getNom($locale) : '';

            return [
                'id'        => $source->getId(),
                'projet'    => $source->getProjet()->getNom($locale),
                'version'   => $source->getVersion(),
                'categorie' => $source->getCategorieSource()->getNom($locale),
                'types'     => $source->getTypeSources()->map(function ($ts) use ($locale) {
                    return $ts->getNom($locale);
                })->toArray(),
                'langues' => $source->getLangues()->map(function ($l) use ($locale) {
                    return $l->getNom($locale);
                })->toArray(),
                'region'        => $region,
                'datation'      => $source->getEstDatee() ? implode(' / ', [$source->getDatation()->getPostQuem() ?? '?', $source->getDatation()->getAnteQuem() ?? '?']) : '',
                'titre_abrege'  => $source->getEditionPrincipaleBiblio()->getBiblio()->getTitreAbrege(),
                'reference'     => $source->getEditionPrincipaleBiblio()->getReferenceSource(),
                'date_creation' => [
                    'display'   => $source->getDateCreation()->format($translator->trans('locale_datetime')),
                    'timestamp' => $source->getDateCreation()->getTimestamp(),
                ],
                'date_modification' => [
                    'display'   => $source->getDateModification()->format($translator->trans('locale_datetime')),
                    'timestamp' => $source->getDateModification()->getTimestamp(),
                ],
                'createur_id' => $source->getCreateur()->getId(),
                'createur' => $source->getCreateur()->getPrenomNom(),
                'editeur'  => $source->getDernierEditeur()->getPrenomNom(),
                'traduire' => array_values(array_filter([
                    $source->getTraduireFr() ? $translator->trans('languages.fr') : null,
                    $source->getTraduireEn() ? $translator->trans('languages.en') : null
                ])),
                'verrou' => ($source->getVerrou() !== null && !$source->getVerrou()->isWritable($user)) ? $source->getVerrou()->toArray($translator->trans('locale_datetime')) : false
            ];
        }, $sources);

        return new JsonResponse([
            'success' => true,
            'data'    => $data
        ]);
    }
}
