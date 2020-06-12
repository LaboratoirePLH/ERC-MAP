<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\CategorieMateriau;
use App\Entity\CategorieSource;
use App\Entity\CategorieSupport;
use App\Entity\Langue;
use App\Entity\Localisation;
use App\Entity\Materiau;
use App\Entity\Projet;
use App\Entity\Source;
use App\Entity\Titre;
use App\Entity\TypeSource;
use App\Entity\TypeSupport;

use App\Form\Type\DependentSelectType;
use App\Form\Type\SelectOrCreateType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class SourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];
        $user = $options['user'];

        $builder
            ->add('traduireFr', CheckboxType::class, [
                'label'    => 'languages.fr',
                'required' => false
            ])
            ->add('traduireEn', CheckboxType::class, [
                'label'    => 'languages.en',
                'required' => false
            ])
            ->add('urlTexte', UrlType::class, [
                'label'    => 'source.fields.url_texte',
                'required' => false
            ])
            ->add('urlImage', UrlType::class, [
                'label'      => 'source.fields.url_image',
                'label_attr' => [
                    'class' => 'dependent_field_iconography'
                ],
                'required' => false
            ])
            ->add('iconographie', CheckboxType::class, [
                'label'      => 'source.fields.iconographie',
                'label_attr' => [
                    'class' => 'dependent_field_iconography_main'
                ],
                'required' => false
            ])
            ->add('estDatee', CheckboxType::class, [
                'label'      => 'generic.fields.est_datee',
                'label_attr' => [
                    'class' => 'dependent_field_estdatee_main'
                ],
                'required' => false
            ])
            ->add('commentaireFr', Type\QuillType::class, array(
                'attr'        => ['class' => 'wysiwyg-editor', 'rows' => 2],
                'label'       => 'generic.fields.commentaire_fr',
                'required'    => false
            ))
            ->add('commentaireEn', Type\QuillType::class, array(
                'attr'        => ['class' => 'wysiwyg-editor', 'rows' => 2],
                'label'       => 'generic.fields.commentaire_en',
                'required'    => false
            ))
            ->add('typeCategorieSource', DependentSelectType::class, [
                'locale'         => $options['locale'],
                'translations'   => $options['translations'],
                'name'           => 'categorieSource',
                'secondary_name' => 'typeSources',
                'category_field' => 'categorieSource',
                'field_options'  => [
                    'label'        => 'source.fields.categorie_source',
                    'required'     => true,
                    'class'        => CategorieSource::class,
                    'choice_label' => 'nom' . ucfirst($locale),
                    'attr'         => [
                        'class' => 'autocomplete',
                        'data-placeholder' => $options['translations']['autocomplete.select_element']
                    ],
                    'query_builder' => function (EntityRepository $er) use ($locale) {
                        return $er->createQueryBuilder('e')
                            ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                    }
                ],
                'secondary_field_options' => [
                    'label'        => 'source.fields.types_source',
                    'required'     => false,
                    'multiple'     => true,
                    'class'        => TypeSource::class,
                    'choice_label' => 'nom' . ucfirst($locale),
                    'attr'         => [
                        'class' => 'autocomplete autocomplete-max-3',
                        'data-placeholder' => $options['translations']['autocomplete.select_element']
                    ]
                ]
            ])
            ->add('typeCategorieMateriau', DependentSelectType::class, [
                'locale'         => $options['locale'],
                'translations'   => $options['translations'],
                'name'           => 'categorieMateriau',
                'secondary_name' => 'materiau',
                'category_field' => 'categorieMateriau',
                'field_options'  => [
                    'label'        => 'source.fields.categorie_materiau',
                    'required'     => false,
                    'class'        => CategorieMateriau::class,
                    'choice_label' => 'nom' . ucfirst($locale),
                    'attr'         => [
                        'class' => 'autocomplete',
                        'data-placeholder' => $options['translations']['autocomplete.select_element']
                    ],
                    'query_builder' => function (EntityRepository $er) use ($locale) {
                        return $er->createQueryBuilder('e')
                            ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                    }
                ],
                'secondary_field_options' => [
                    'label'        => 'source.fields.materiau',
                    'required'     => false,
                    'class'        => Materiau::class,
                    'choice_label' => 'nom' . ucfirst($locale),
                    'attr'         => [
                        'class' => 'autocomplete',
                        'data-placeholder' => $options['translations']['autocomplete.select_element']
                    ]
                ]
            ])
            ->add('typeCategorieSupport', DependentSelectType::class, [
                'locale'         => $options['locale'],
                'translations'   => $options['translations'],
                'name'           => 'categorieSupport',
                'secondary_name' => 'typeSupport',
                'category_field' => 'categorieSupport',
                'field_options'  => [
                    'label'        => 'source.fields.categorie_support',
                    'required'     => false,
                    'class'        => CategorieSupport::class,
                    'choice_label' => 'nom' . ucfirst($locale),
                    'attr'         => [
                        'class' => 'autocomplete',
                        'data-placeholder' => $options['translations']['autocomplete.select_element']
                    ],
                    'query_builder' => function (EntityRepository $er) use ($locale) {
                        return $er->createQueryBuilder('e')
                            ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                    }
                ],
                'secondary_field_options' => [
                    'label'        => 'source.fields.support',
                    'required'     => false,
                    'class'        => TypeSupport::class,
                    'choice_label' => 'nom' . ucfirst($locale),
                    'attr'         => [
                        'class' => 'autocomplete',
                        'data-placeholder' => $options['translations']['autocomplete.select_element']
                    ]
                ]
            ])
            ->add("projet", EntityType::class, [
                'label'         => 'source.fields.projet',
                'required'      => true,
                'class'         => Projet::class,
                'choice_label'  => 'nom' . ucfirst($locale),
                'disabled'      => ($options['formAction'] !== "create"),
                'attr'          => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale, $user) {
                    return $er->createQueryBuilder('e')
                        ->where(":user MEMBER OF e.chercheurs")
                        ->setParameter("user", $user)
                        ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                }
            ])
            ->add('auteurs', EntityType::class, [
                'label'        => 'source.fields.auteurs',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Auteur::class,
                'choice_label' => 'nom' . ucfirst($locale),
                'label_attr' => [
                    'class' => 'dependent_field_auteurs'
                ],
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                }
            ])
            ->add('langues', EntityType::class, [
                'label'        => 'source.fields.langues',
                'required'     => true,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Langue::class,
                'choice_label' => 'nom' . ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                }
            ])
            ->add('titrePrincipal', SelectOrCreateType::class, [
                'label'                   => 'source.fields.titre_principal',
                'label_attr' => [
                    'class' => 'dependent_field_auteurs_main'
                ],
                'required'                => false,
                'locale'                  => $options['locale'],
                'translations'            => $options['translations'],
                'field_name'              => 'titrePrincipal',
                'object_class'            => Titre::class,
                'creation_form_class'     => TitreType::class,
                'selection_choice_label'  => 'affichage' . ucfirst($locale),
                'allow_none'              => true,
                'default_decision'        => 'select',
                'formAction'              => $options['formAction'],
                'isClone'                 => $options['isClone'],
                'selection_query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                }
            ])
            ->add('datation', DatationType::class)
            ->add('lieuDecouverte', SelectOrCreateType::class, [
                'label'                    => 'source.fields.lieu_decouverte',
                'required'                 => false,
                'locale'                   => $options['locale'],
                'translations'             => $options['translations'],
                'field_name'               => 'lieuDecouverte',
                'object_class'             => Localisation::class,
                'creation_form_class'      => LocalisationType::class,
                'creation_form_css_class'  => 'localisation_form',
                'selection_form_css_class' => 'localisation_selection',
                'selection_choice_label'   => 'affichage' . ucfirst($locale),
                'allow_none'               => false,
                'default_decision'         => 'select',
                'formAction'               => $options['formAction'],
                'isClone'                  => $options['isClone'],
                'selection_query_builder'  => function (EntityRepository $er) use ($locale) {
                    $nameField = 'nom' . ucfirst($locale);
                    $qb = $er->createQueryBuilder('e');
                    return $qb
                        ->leftJoin('e.grandeRegion', 'gr')
                        ->leftJoin('e.sousRegion', 'sr')
                        ->addOrderBy("unaccent(gr.$nameField)", 'ASC')
                        ->addOrderBy("unaccent(sr.$nameField)", 'ASC')
                        ->addOrderBy("e.nomVille", 'ASC')
                        ->addOrderBy("e.nomSite", 'ASC')
                        ->addOrderBy("e.id", 'ASC');
                }
            ])
            ->add('lieuOrigine', SelectOrCreateType::class, [
                'label'      => 'source.fields.lieu_origine',
                'label_attr' => [
                    'class' => 'dependent_field_insitu dependent_field_inverse'
                ],
                'required'                 => false,
                'locale'                   => $options['locale'],
                'translations'             => $options['translations'],
                'field_name'               => 'lieuDecouverte',
                'object_class'             => Localisation::class,
                'creation_form_class'      => LocalisationType::class,
                'creation_form_css_class'  => 'localisation_form',
                'selection_form_css_class' => 'localisation_selection',
                'selection_choice_label'   => 'affichage' . ucfirst($locale),
                'allow_none'               => true,
                'none_label'               => 'generic.fields.indetermine',
                'default_decision'         => 'select',
                'formAction'               => $options['formAction'],
                'isClone'                  => $options['isClone'],
                'selection_query_builder'  => function (EntityRepository $er) use ($locale) {
                    $nameField = 'nom' . ucfirst($locale);
                    $qb = $er->createQueryBuilder('e');
                    return $qb
                        ->leftJoin('e.grandeRegion', 'gr')
                        ->leftJoin('e.sousRegion', 'sr')
                        ->addOrderBy("unaccent(gr.$nameField)", 'ASC')
                        ->addOrderBy("unaccent(sr.$nameField)", 'ASC')
                        ->addOrderBy("e.nomVille", 'ASC')
                        ->addOrderBy("e.nomSite", 'ASC')
                        ->addOrderBy("e.id", 'ASC');
                }
            ])
            ->add('inSitu', CheckboxType::class, [
                'label'      => 'source.fields.in_situ',
                'label_attr' => [
                    'class' => 'dependent_field_insitu_main'
                ],
                'required' => false
            ])
            ->add('sourceBiblios', CollectionType::class, [
                'label'         => false,
                'entry_type'    => SourceBiblioType::class,
                'entry_options' => array_intersect_key($options, array_flip(["translations", "locale"])),
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])
            ->add('attestations', CollectionType::class, [
                'label'         => false,
                'entry_type'    => AttestationSourceType::class,
                'allow_add'     => true,
                'allow_delete'  => false,
                'required'      => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Source::class,
        ]);
        $resolver->setRequired('locale');
        $resolver->setRequired('translations');
        $resolver->setRequired('formAction');
        $resolver->setRequired('user');
        $resolver->setDefault('isClone', false);
    }
}
