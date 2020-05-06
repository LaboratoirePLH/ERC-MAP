<?php

namespace App\Search;

use Symfony\Contracts\Translation\TranslatorInterface;

class CriteriaList
{
    public static function get(TranslatorInterface $translator)
    {
        return [
            [
                'key'      => 'names',
                'label'    => $translator->trans('search.criteria_labels.names'),
                'type'     => 'select',
                'semitic'  => true,
                'children' => [
                    [
                        'key'     => 'inContext',
                        'label'   => $translator->trans('search.criteria_labels.element_en_contexte'),
                        'type'    => 'text',
                        'semitic' => true,
                    ],
                    [
                        'key'     => 'morphologicalForms',
                        'label'   => $translator->trans('element.fields.etat_morphologique'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'elementGenders',
                        'label'   => $translator->trans('element.fields.genre'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'elementNumbers',
                        'label'   => $translator->trans('element.fields.nombre'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'elementNatures',
                        'label'   => $translator->trans('element.fields.nature'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'elementInvariantCategories',
                        'label'   => $translator->trans('generic.fields.categorie'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'elementTranslation',
                        'label'   => $translator->trans('generic.fields.translation'),
                        'type'    => 'text',
                        'semitic' => false,
                    ],
                    [
                        'key'      => 'attestationFiability',
                        'label'    => $translator->trans('search.criteria_labels.fiabilite_lecture'),
                        'type'     => 'range',
                        'datalist' => [
                            1 => $translator->trans('attestation.fiabilite.niveau_1'),
                            2 => $translator->trans('attestation.fiabilite.niveau_2'),
                            3 => $translator->trans('attestation.fiabilite.niveau_3'),
                        ]
                    ],
                    [
                        'key'     => 'translitteration',
                        'label'   => $translator->trans('attestation.fields.translitteration'),
                        'type'    => 'text',
                        'semitic' => false,
                    ],
                ]
            ],
            [
                'key'      => 'languages',
                'label'    => $translator->trans('source.fields.langues'),
                'type'     => 'select',
                'semitic'  => false,
                'children' => [
                    [
                        'key'   => 'prosePoetry',
                        'label' => $translator->trans('attestation.fields.prose_poesie'),
                        'type'  => 'prosepoetry'
                    ],
                ]
            ],
            [
                'key'      => 'datation',
                'label'    => $translator->trans('generic.fields.datation'),
                'type'     => 'datation',
                'children' => [
                    [
                        'key'      => 'datationPrecision',
                        'label'    => $translator->trans('datation.fields.precision'),
                        'type'     => 'range',
                        'datalist' => [
                            1 => $translator->trans('datation.precision.niveau_1'),
                            2 => $translator->trans('datation.precision.niveau_2'),
                            3 => $translator->trans('datation.precision.niveau_3'),
                            4 => $translator->trans('datation.precision.niveau_4'),
                            5 => $translator->trans('datation.precision.niveau_5'),
                        ]
                    ]
                ]
            ],
            [
                'key'      => 'locations',
                'label'    => $translator->trans('generic.fields.localisation'),
                'type'     => 'select',
                'semitic'  => false,
                'children' => [
                    // [
                    //     'key'     => 'regions',
                    //     'label'   => $translator->trans('localisation.fields.region'),
                    //     'type'    => 'select',
                    //     'semitic' => false,
                    // ],
                    [
                        'key'     => 'politicalEntities',
                        'label'   => $translator->trans('localisation.fields.entite_politique'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'citiesSites',
                        'label'   => $translator->trans('localisation.fields.ville') . ' / ' . $translator->trans('localisation.fields.site'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    // [
                    //     'key'     => 'cities',
                    //     'label'   => $translator->trans('localisation.fields.ville'),
                    //     'type'    => 'select',
                    //     'semitic' => false,
                    // ],
                    // [
                    //     'key'     => 'sites',
                    //     'label'   => $translator->trans('localisation.fields.site'),
                    //     'type'    => 'select',
                    //     'semitic' => false,
                    // ],
                    [
                        'key'      => 'locationPrecision',
                        'label'    => $translator->trans('localisation.fields.precision'),
                        'type'     => 'range',
                        'datalist' => [
                            1 => $translator->trans('localisation.precision.niveau_1'),
                            2 => $translator->trans('localisation.precision.niveau_2'),
                            3 => $translator->trans('localisation.precision.niveau_3'),
                            4 => $translator->trans('localisation.precision.niveau_4'),
                        ]
                    ],
                    [
                        'key'     => 'topographies',
                        'label'   => $translator->trans('localisation.fields.topographie'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'functions',
                        'label'   => $translator->trans('localisation.fields.fonction'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                ]
            ],
            [
                'key'      => 'source',
                'label'    => $translator->trans('source.name'),
                'type'     => null,
                'children' => [
                    [
                        'key'     => 'sourceTypes',
                        'label'   => $translator->trans('source.fields.type_source'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'sourceMaterials',
                        'label'   => $translator->trans('source.fields.materiau'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'sourceMediums',
                        'label'   => $translator->trans('source.fields.support'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'authors',
                        'label'   => $translator->trans('biblio.fields.auteur'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                ]
            ],
            [
                'key'      => 'agent',
                'label'    => $translator->trans('agent.name'),
                'type'     => null,
                'children' => [
                    [
                        'key'     => 'agentivities',
                        'label'   => $translator->trans('agent.fields.agentivite'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'agentGenders',
                        'label'   => $translator->trans('agent.fields.genre'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'agentNatures',
                        'label'   => $translator->trans('agent.fields.nature'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'agentStatuses',
                        'label'   => $translator->trans('agent.fields.statut_affiche'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'agentActivities',
                        'label'   => $translator->trans('agent.fields.activite'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'agentDesignation',
                        'label'   => $translator->trans('agent.fields.designation'),
                        'type'    => 'text',
                        'semitic' => true,
                    ],
                ]
            ],
            [
                'key'      => 'attestation',
                'label'    => $translator->trans('search.criteria_labels.contexte_attestation'),
                'type'     => null,
                'children' => [
                    [
                        'key'     => 'testimonyOccasions',
                        'label'   => $translator->trans('attestation.fields.occasion'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'testimonyActs',
                        'label'   => $translator->trans('attestation.fields.pratiques'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'     => 'testimonyMaterials',
                        'label'   => $translator->trans('attestation.fields.materiel'),
                        'type'    => 'select',
                        'semitic' => false,
                    ],
                    [
                        'key'   => 'elementCount',
                        'label' => $translator->trans('attestation.fields.compte_element'),
                        'type'  => 'operation',
                    ],
                    [
                        'key'   => 'divinePowersCount',
                        'label' => $translator->trans('formule.fields.compte_puissances_divines'),
                        'type'  => 'operation',
                    ],
                ]
            ],
            [
                'key'      => 'others',
                'label'    => $translator->trans('generic.fields.divers'),
                'type'     => null,
                'children' => [
                    [
                        'key'     => 'comments',
                        'label'   => $translator->trans('generic.fields.commentaire'),
                        'type'    => 'text',
                        'semitic' => true,
                    ],
                ]
            ]
        ];
    }

    public static function getCriteria(string $key, TranslatorInterface $translator): array
    {
        $all = self::get($translator);
        foreach ($all as $category) {
            if ($category['key'] === $key) {
                unset($category['children']);
                return $category;
            } else {
                foreach ($category['children'] as $child) {
                    if ($child['key'] === $key) {
                        return $child;
                    }
                }
            }
        }
        return [];
    }
}
