<?php

namespace App\Form;

use App\Entity\Attestation;
use App\Entity\CategorieOccasion;
use App\Entity\EtatFiche;
use App\Entity\Pratique;
use App\Entity\Occasion;

use App\Form\Type\DependentSelectType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class AttestationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];

        $builder
            ->add('traduireFr', CheckboxType::class, [
                'label'    => 'languages.fr',
                'required' => false
            ])
            ->add('traduireEn', CheckboxType::class, [
                'label'    => 'languages.en',
                'required' => false
            ])
            ->add("etatFiche", EntityType::class, [
                'label'         => 'attestation.fields.etat_fiche',
                'required'      => true,
                'class'         => EtatFiche::class,
                'choice_label'  => 'nom'.ucfirst($locale),
                'attr'          => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('sourceId', IntegerType::class, [
                'label'    => 'attestation.fields.source_id',
                'mapped'   => false,
                'disabled' => true,
                'required' => false,
                'data'     => $options['source']->getId()
            ])
            ->add('sourceShortTitle', TextType::class, [
                'label'    => 'source_biblio.fields.edition_principale',
                'mapped'   => false,
                'disabled' => true,
                'required' => false,
                'data'     => $options['source']->getEditionPrincipaleBiblio()->getBiblio()->getTitreAbrege()
            ])
            ->add('sourceBiblio', TextType::class, [
                'label'    => 'source.fields.source_biblio',
                'mapped'   => false,
                'disabled' => true,
                'required' => false,
                'data'     => $options['source']->getEditionPrincipaleBiblio()->getReferenceSource()
            ])
            ->add('passage', TextType::class, [
                'label'       => 'attestation.fields.passage',
                'required'    => true
            ])
            ->add('prose', CheckboxType::class, [
                'label'    => 'attestation.fields.prose',
                'required' => false
            ])
            ->add('poesie', CheckboxType::class, [
                'label'    => 'attestation.fields.poesie',
                'required' => false
            ])
            ->add('extraitSansRestitution', CKEditorType::class, [
                'config_name' => 'text_styling_only',
                'label'       => 'attestation.fields.extrait_sans_restitution',
                'required'    => false
            ])
            ->add('extraitAvecRestitution', CKEditorType::class, [
                'config_name' => 'text_styling_only',
                'label'       => 'attestation.fields.extrait_avec_restitution',
                'required'    => false
            ])
            ->add('translitteration', CKEditorType::class, [
                'config_name' => 'text_styling_only',
                'label'       => 'attestation.fields.translitteration',
                'required'    => false
            ])
            ->add('fiabiliteAttestation', ChoiceType::class, [
                'label'       => 'generic.fields.fiabilite',
                'expanded'    => false,
                'multiple'    => false,
                'required'    => false,
                'placeholder' => false,
                'choices'     => [
                    'attestation.fiabilite.niveau_1' => 1,
                    'attestation.fiabilite.niveau_2' => 2,
                    'attestation.fiabilite.niveau_3' => 3
                ]
            ])
            ->add('typeCategorieOccasion', DependentSelectType::class, [
                'locale'         => $options['locale'],
                'translations'   => $options['translations'],
                'name'           => 'categorieOccasion',
                'secondary_name' => 'occasion',
                'category_field' => 'categorieOccasion',
                'field_options'  => [
                    'label'        => 'attestation.fields.categorie_occasion',
                    'required'     => false,
                    'class'        => CategorieOccasion::class,
                    'choice_label' => 'nom'.ucfirst($locale),
                    'attr'         => [
                        'class' => 'autocomplete',
                        'data-placeholder' => $options['translations']['autocomplete.select_element']
                    ],
                    'query_builder' => function (EntityRepository $er) use ($locale) {
                        return $er->createQueryBuilder('e')
                            ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                    }
                ],
                'secondary_field_options' => [
                    'label'        => 'attestation.fields.occasion',
                    'required'     => false,
                    'class'        => Occasion::class,
                    'choice_label' => 'nom'.ucfirst($locale),
                    'attr'         => [
                        'class' => 'autocomplete',
                        'data-placeholder' => $options['translations']['autocomplete.select_element']
                    ]
                ]
            ])
            ->add('pratiques', EntityType::class, [
                'label'        => 'attestation.fields.pratiques',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Pratique::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('attestationMateriels', CollectionType::class, [
                'label'         => false,
                'entry_type'    => AttestationMaterielType::class,
                'entry_options' => array_intersect_key($options, array_flip(["translations","locale"])),
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])
            ->add('agents', CollectionType::class, [
                'label'         => false,
                'entry_type'    => AgentType::class,
                'entry_options' => array_intersect_key($options, array_flip(["translations","locale"])),
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])

            ->add('estDatee', CheckboxType::class, [
                'label'      => 'generic.fields.est_datee',
                'label_attr' => [
                    'class' => 'dependent_field_estdatee_main'
                ],
                'required' => false,
            ])
            ->add('datation', DatationType::class)
            ->add('estLocalisee', CheckboxType::class, [
                'label'      => 'generic.fields.est_localisee',
                'label_attr' => [
                    'class' => 'dependent_field_estlocalisee_main'
                ],
                'required' => false,
            ])
            ->add('localisation', LocalisationType::class, [
                'label'           => 'generic.fields.localisation',
                'required'        => false,
                'region_required' => false,
                'attr'            => ['class' => 'localisation_form'],
                'locale'          => $options['locale'],
                'translations'    => $options['translations'],
            ])

            ->add('commentaireFr', CKEditorType::class, array(
                'config_name' => 'text_styling_only',
                'label'       => 'generic.fields.commentaire_fr',
                'attr'        => ['rows' => 2],
                'required'    => false
            ))
            ->add('commentaireEn', CKEditorType::class, array(
                'config_name' => 'text_styling_only',
                'label'       => 'generic.fields.commentaire_en',
                'attr'        => ['rows' => 2],
                'required'    => false
            ))
            ->add('contientElements', CollectionType::class, [
                'label'         => false,
                'entry_type'    => ContientElementType::class,
                'entry_options' => array_intersect_key($options, array_flip(["translations","locale"])),
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Attestation::class
        ]);
        $resolver->setRequired('source');
        $resolver->setRequired('translations');
        $resolver->setDefined('locale');
    }
}
