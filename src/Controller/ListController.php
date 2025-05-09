<?php

namespace App\Controller;

use App\Entity\Attestation;
use App\Entity\Biblio;
use App\Entity\Element;
use App\Entity\IndexRecherche;
use App\Entity\Source;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListController extends AbstractController
{
    /**
     * @Route("/list/source", name="json_source_list")
     */
    public function source(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $locale = $request->getLocale();
        $user = $this->getUser();

        $repository = $doctrine->getRepository(Source::class);

        // Check if we have a filter in parameter
        $filter = $request->query->get('filter', null);
        if ($filter !== null) {
            list($criteria, $value) = explode(':', $filter);
            switch ($criteria) {
                case 'element':
                    $sources = $repository->findByElement($value);
                    break;
                default:
                    $sources = $repository->findAll();
            }
        } else {
            $sources = $repository->findAll();
        }
        $languages = [
            'fr' => $translator->trans('languages.fr'),
            'en' => $translator->trans('languages.en'),
        ];
        $datetimeFormat = $translator->trans('locale_datetime');

        // If user is not a contributor, filter to keep only corpus ready entities
        if (!$this->isGranted('ROLE_CONTRIBUTOR')) {
            $valid_ids = $doctrine->getRepository(IndexRecherche::class)->getEntityIds('Source', true);
            $sources = array_values(array_filter($sources, function ($s) use ($valid_ids) {
                return in_array($s->getId(), $valid_ids);
            }));
        }

        $sources = array_values(array_filter($sources, function (Source $source) use ($user) {
            return $user === null
                || $user->getRole() === "admin"
                || ($source->getProjet() !== null && in_array($source->getProjet()->getId(), $user->getIdsProjets()));
        }));

        $data = array_map(function ($source) use ($locale, $user, $datetimeFormat, $languages) {
            $lieu = $source->getInSitu() ? $source->getLieuDecouverte() : $source->getLieuOrigine();
            $region = ($lieu !== null && $lieu->getGrandeRegion() !== null) ? $lieu->getGrandeRegion()->getNom($locale) : '';

            return [
                'id'        => $source->getId(),
                'projet'    => $source->getProjet() !== null ? $source->getProjet()->getNom($locale) : "",
                'version'   => $source->getVersion(),
                'categorie' => ($source->getCategorieSource() !== null) ? $source->getCategorieSource()->getNom($locale) : "",
                'types'     => $source->getTypeSources()->map(function ($ts) use ($locale) {
                    return $ts->getNom($locale);
                })->toArray(),
                'langues' => $source->getLangues()->map(function ($l) use ($locale) {
                    return $l->getNom($locale);
                })->toArray(),
                'region'        => $region,
                'datation'      => $source->getEstDatee() ? implode(' / ', [$source->getDatation()->getPostQuem() ?? '?', $source->getDatation()->getAnteQuem() ?? '?']) : '',
                'titre_abrege'  => ($source->getEditionPrincipaleBiblio() !== null && $source->getEditionPrincipaleBiblio()->getBiblio() !== null)
                    ? $source->getEditionPrincipaleBiblio()->getBiblio()->getTitreAbrege() : '',
                'reference'     => $source->getEditionPrincipaleBiblio() !== null ? $source->getEditionPrincipaleBiblio()->getReferenceSource() : '',
                'date_creation' => [
                    'display'   => $source->getDateCreation()->format($datetimeFormat),
                    'timestamp' => $source->getDateCreation()->getTimestamp(),
                ],
                'date_modification' => [
                    'display'   => $source->getDateModification()->format($datetimeFormat),
                    'timestamp' => $source->getDateModification()->getTimestamp(),
                ],
                'createur_id' => $source->getCreateur()->getId(),
                'createur' => [
                    'value'   => $source->getCreateur()->getPrenomNom(),
                    'display' => $source->getCreateur()->getInitials()
                ],
                'editeur'  => [
                    'value'   => $source->getDernierEditeur()->getPrenomNom(),
                    'display' => $source->getDernierEditeur()->getInitials()
                ],
                'traduire' => array_values(array_filter([
                    $source->getTraduireFr() ? $languages['fr'] : null,
                    $source->getTraduireEn() ? $languages['en'] : null
                ])),
                'verrou' => ($this->isGranted('ROLE_USER')
                    && $source->getVerrou() !== null
                    && !$source->getVerrou()->isWritable($user))
                    ? $source->getVerrou()->toArray($datetimeFormat)
                    : false
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
    public function attestation(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $locale = $request->getLocale();
        $user = $this->getUser();

        $repository = $doctrine->getRepository(Attestation::class);

        // Check if we have a filter in parameter
        $filter = $request->query->get('filter', null);
        if ($filter !== null) {
            list($criteria, $value) = explode(':', $filter);
            switch ($criteria) {
                case 'source':
                    $attestations = $repository->findBySource($value);
                    break;
                case 'element':
                    $attestations = $repository->findByElement($value);
                    break;
                default:
                    $attestations = $repository->findAll();
            }
        } else {
            $attestations = $repository->findAll();
        }

        // If user is not a contributor, filter to keep only corpus ready entities
        if (!$this->isGranted('ROLE_CONTRIBUTOR')) {
            $valid_ids = $doctrine->getRepository(IndexRecherche::class)->getEntityIds('Attestation', true);
            $attestations = array_values(array_filter($attestations, function ($a) use ($valid_ids) {
                return in_array($a->getId(), $valid_ids);
            }));
        }

        $languages = [
            'fr' => $translator->trans('languages.fr'),
            'en' => $translator->trans('languages.en'),
        ];
        $datetimeFormat = $translator->trans('locale_datetime');

        $attestations = array_values(array_filter($attestations, function (Attestation $attestation) use ($user) {
            return $user === null
                || $user->getRole() === "admin"
                || ($attestation->getSource()->getProjet() !== null && in_array($attestation->getSource()->getProjet()->getId(), $user->getIdsProjets()));
        }));

        $data = array_map(function ($attestation) use ($locale, $user, $datetimeFormat, $languages) {
            $translitteration = strlen($attestation->getTranslitteration()) > 0 ? $attestation->getTranslitteration() : $attestation->getExtraitAvecRestitution();
            $etat_fiche = [
                'id'      => $attestation->getEtatFiche() === null ? 0 : $attestation->getEtatFiche()->getId(),
                'display' => $attestation->getEtatFiche() === null ? '' : $attestation->getEtatFiche()->getNom($locale)
            ];
            $source = $attestation->getSource();

            return [
                'id'               => $attestation->getId(),
                'source'           => $source->getId(),
                'projet'           => $source->getProjet() !== null ? $source->getProjet()->getNom($locale) : "",
                'version'          => $attestation->getVersion(),
                'titre_abrege'     => $source->getEditionPrincipaleBiblio()->getBiblio()->getTitreAbrege(),
                'reference'        => $source->getEditionPrincipaleBiblio()->getReferenceSource(),
                'passage'          => $attestation->getPassage(),
                'translitteration' => [
                    "display" => \App\Utils\StringHelper::ellipsis($translitteration, 200),
                    "full"    => $translitteration
                ],
                'etat_fiche'       => $etat_fiche,
                'date_creation'    => [
                    'display'   => $attestation->getDateCreation()->format($datetimeFormat),
                    'timestamp' => $attestation->getDateCreation()->getTimestamp(),
                ],
                'date_modification' => [
                    'display'   => $attestation->getDateModification()->format($datetimeFormat),
                    'timestamp' => $attestation->getDateModification()->getTimestamp(),
                ],
                'createur_id' => $attestation->getCreateur()->getId(),
                'createur' => [
                    'value'   => $attestation->getCreateur()->getPrenomNom(),
                    'display' => $attestation->getCreateur()->getInitials()
                ],
                'editeur'  => [
                    'value'   => $attestation->getDernierEditeur()->getPrenomNom(),
                    'display' => $attestation->getDernierEditeur()->getInitials()
                ],
                'traduire' => array_values(array_filter([
                    $attestation->getTraduireFr() ? $languages['fr'] : null,
                    $attestation->getTraduireEn() ? $languages['en'] : null
                ])),
                'verrou' => ($this->isGranted('ROLE_USER')
                    && $source->getVerrou() !== null
                    && !$source->getVerrou()->isWritable($user))
                    ? $source->getVerrou()->toArray($datetimeFormat)
                    : false
            ];
        }, $attestations);

        $result = [
            'success' => true,
            'data'    => $data
        ];
        $response = new Response(json_encode($result, JSON_INVALID_UTF8_IGNORE));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/list/element", name="json_element_list")
     */
    public function element(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $locale = $request->getLocale();
        $user = $this->getUser();

        $repository = $doctrine->getRepository(Element::class);

        // Check if we have a filter in parameter
        $filter = $request->query->get('filter', null);
        if ($filter !== null) {
            list($criteria, $value) = explode(':', $filter);
            switch ($criteria) {
                case 'attestation':
                    $elements = $repository->findByAttestation($value);
                    break;
                default:
                    $elements = $repository->findAll();
            }
        } else {
            $elements = $repository->findAll();
        }

        $data = array_map(function ($element) use ($locale, $user, $translator) {
            return [
                'id'               => $element->getId(),
                'version'          => $element->getVersion(),
                'etat_absolu'      => $element->getEtatAbsolu(),
                'nature'           => ($element->getNatureElement() != null) ? $element->getNatureElement()->getNom($locale) : '',
                'traductions'      => $element->getTraductions()->map(function ($tr) use ($locale) {
                    return $tr->getNom($locale);
                })->toArray(),
                'categories'       => $element->getCategories()->map(function ($c) use ($locale) {
                    return $c->getNom($locale);
                })->toArray(),
                'type'             => $translator->trans('element.types.' . $element->getType()),
                'type_checked'     => [
                    'display' => $element->getTypeChecked() ? $translator->trans('element.type_checked.yes') : $translator->trans('element.type_checked.no'),
                    'value'   => $element->getTypeChecked()
                ],
                'date_creation'    => [
                    'display'   => $element->getDateCreation()->format($translator->trans('locale_datetime')),
                    'timestamp' => $element->getDateCreation()->getTimestamp(),
                ],
                'date_modification' => [
                    'display'   => $element->getDateModification()->format($translator->trans('locale_datetime')),
                    'timestamp' => $element->getDateModification()->getTimestamp(),
                ],
                'createur_id' => $element->getCreateur()->getId(),
                'createur' => [
                    'value'   => $element->getCreateur()->getPrenomNom(),
                    'display' => $element->getCreateur()->getInitials()
                ],
                'editeur'  => [
                    'value'   => $element->getDernierEditeur()->getPrenomNom(),
                    'display' => $element->getDernierEditeur()->getInitials()
                ],
                'traduire' => array_values(array_filter([
                    $element->getTraduireFr() ? $translator->trans('languages.fr') : null,
                    $element->getTraduireEn() ? $translator->trans('languages.en') : null
                ])),
                'verrou' => ($this->isGranted('ROLE_USER') && $element->getVerrou() !== null && !$element->getVerrou()->isWritable($user)) ? $element->getVerrou()->toArray($translator->trans('locale_datetime')) : false
            ];
        }, $elements);

        return new JsonResponse([
            'success' => true,
            'data'    => $data
        ]);
    }

    /**
     * @Route("/list/bibliography", name="json_bibliography_list")
     */
    public function bibliography(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $locale = $request->getLocale();
        $user = $this->getUser();

        $bibliographies = $doctrine
            ->getRepository(Biblio::class)
            ->findAll();


        $valid_ids = $doctrine->getRepository(IndexRecherche::class)->getEntityIds('Source', true);


        $data = array_map(function ($bibliography) use ($user, $translator, $valid_ids) {
            $sourceBiblios = $bibliography->getSourceBiblios();

            // If user is not a contributor, filter to keep only corpus ready sources
            if (!$this->isGranted('ROLE_CONTRIBUTOR')) {
                $sourceBiblios = $sourceBiblios->filter(function ($sb) use ($valid_ids) {
                    return in_array($sb->getSource()->getId(), $valid_ids);
                });
            }

            return [
                'id'            => $bibliography->getId(),
                'type'          => $translator->trans($bibliography->getEstCorpus() ? 'biblio.fields.corpus' : 'biblio.fields.bibliographique'),
                'auteur'        => $bibliography->getAuteurBiblio() ?? '',
                'annee'         => $bibliography->getAnnee() ?? '',
                'titre_abrege'  => $bibliography->getTitreAbrege(),
                'titre_complet' => $bibliography->getTitreComplet(),
                'stats'         => [
                    'sources'  => count($sourceBiblios),
                    'elements' => count($bibliography->getElementBiblios()),
                ],
                'verrou' => ($this->isGranted('ROLE_USER') && $bibliography->getVerrou() !== null && !$bibliography->getVerrou()->isWritable($user)) ? $bibliography->getVerrou()->toArray($translator->trans('locale_datetime')) : false
            ];
        }, $bibliographies);

        return new JsonResponse([
            'success' => true,
            'data'    => $data
        ]);
    }
}
