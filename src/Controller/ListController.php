<?php

namespace App\Controller;

use App\Entity\Source;
use App\Entity\Attestation;

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

    /**
     * @Route("/list/attestation", name="json_attestation_list")
     */
    public function attestation(Request $request, TranslatorInterface $translator)
    {
        $locale = $request->getLocale();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $repository = $this->getDoctrine()->getRepository(Attestation::class);

        // Check if we have a filter in parameter
        $filter = $request->query->get('filter', null);
        if ($filter !== null) {
            list($criteria, $value) = explode(':', $filter);
            switch ($criteria) {
                case 'source':
                    $attestations = $repository->findBySource($value);
                    break;
                default:
                    $attestations = $repository->findAll();
            }
        } else {
            $attestations = $repository->findAll();
        }

        $data = array_map(function ($attestation) use ($locale, $user, $translator) {
            $translitteration = strlen($attestation->getTranslitteration()) > 0 ? $attestation->getTranslitteration() : $attestation->getExtraitAvecRestitution();
            $etat_fiche = [
                'id'      => $attestation->getEtatFiche() === null ? 0 : $attestation->getEtatFiche()->getId(),
                'display' => $attestation->getEtatFiche() === null ? '' : $attestation->getEtatFiche()->getNom($locale)
            ];

            return [
                'id'               => $attestation->getId(),
                'source'           => $attestation->getSource()->getId(),
                'projet'           => $attestation->getSource()->getProjet()->getNom($locale),
                'version'          => $attestation->getVersion(),
                'titre_abrege'     => $attestation->getSource()->getEditionPrincipaleBiblio()->getBiblio()->getTitreAbrege(),
                'reference'        => $attestation->getSource()->getEditionPrincipaleBiblio()->getReferenceSource(),
                'passage'          => $attestation->getPassage(),
                'translitteration' => [
                    "display" => \App\Utils\StringHelper::ellipsis($translitteration, 200),
                    "full"    => $translitteration
                ],
                'etat_fiche'       => $etat_fiche,
                'date_creation'    => [
                    'display'   => $attestation->getDateCreation()->format($translator->trans('locale_datetime')),
                    'timestamp' => $attestation->getDateCreation()->getTimestamp(),
                ],
                'date_modification' => [
                    'display'   => $attestation->getDateModification()->format($translator->trans('locale_datetime')),
                    'timestamp' => $attestation->getDateModification()->getTimestamp(),
                ],
                'createur_id' => $attestation->getCreateur()->getId(),
                'createur' => $attestation->getCreateur()->getPrenomNom(),
                'editeur'  => $attestation->getDernierEditeur()->getPrenomNom(),
                'traduire' => array_values(array_filter([
                    $attestation->getTraduireFr() ? $translator->trans('languages.fr') : null,
                    $attestation->getTraduireEn() ? $translator->trans('languages.en') : null
                ])),
                'verrou' => ($attestation->getVerrou() !== null && !$attestation->getVerrou()->isWritable($user)) ? $attestation->getVerrou()->toArray($translator->trans('locale_datetime')) : false
            ];
        }, $attestations);

        return new JsonResponse([
            'success' => true,
            'data'    => $data
        ]);
    }
}
