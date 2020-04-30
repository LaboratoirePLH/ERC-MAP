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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class AttestationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];
        $editionPrincipale = $options['source']->getEditionPrincipaleBiblio();

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
                'choice_label'  => 'nom' . ucfirst($locale),
                'attr'          => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom' . ucfirst($locale), 'ASC');
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
                'data'     => html_entity_decode($editionPrincipale ? $editionPrincipale->getBiblio()->getTitreAbrege() : "")
            ])
            ->add('sourceBiblio', TextType::class, [
                'label'    => 'source.fields.source_biblio',
                'mapped'   => false,
                'disabled' => true,
                'required' => false,
                'data'     => $editionPrincipale ? $editionPrincipale->getReferenceSource() : ""
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
            ->add('traductions', CollectionType::class, [
                'label'         => 'generic.fields.translations',
                'entry_type'    => TraductionAttestationType::class,
                'by_reference'  => false,
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])
            ->add('extraitAvecRestitution', Type\QuillType::class, [
                'attr'        => ['class' => 'wysiwyg-editor'],
                'label'       => 'attestation.fields.extrait_avec_restitution',
                'required'    => false
            ])
            ->add('translitteration', Type\QuillType::class, [
                'attr'        => ['class' => 'wysiwyg-editor'],
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
            ->add('pratiques', EntityType::class, [
                'label'        => 'attestation.fields.pratiques',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Pratique::class,
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
            ->add('attestationOccasions', CollectionType::class, [
                'label'         => false,
                'entry_type'    => AttestationOccasionType::class,
                'entry_options' => array_intersect_key($options, array_flip(["translations", "locale"])),
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])
            ->add('attestationMateriels', CollectionType::class, [
                'label'         => false,
                'entry_type'    => AttestationMaterielType::class,
                'entry_options' => array_intersect_key($options, array_flip(["translations", "locale"])),
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])
            ->add('agents', CollectionType::class, [
                'label'         => false,
                'entry_type'    => AgentType::class,
                'entry_options' => array_intersect_key($options, array_flip(["translations", "locale"])),
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
                'attr'            => ['class' => 'localisation_form'],
                'locale'          => $options['locale'],
                'translations'    => $options['translations'],
            ])
            ->add('attestationsLiees', EntityType::class, [
                'label'        => 'attestation.fields.attestations_liees',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Attestation::class,
                'by_reference' => false,
                'choice_label' => function ($attestation) {
                    return $attestation->getAffichage();
                },
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $qb = $er->createQueryBuilder('e');
                    if ($options['attestation']->getId() !== null) {
                        $qb = $qb->where($qb->expr()->neq('e.id', $options['attestation']->getId()));
                    }
                    return $qb->orderBy('e.id', 'ASC');
                }
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
            ->add('contientElements', CollectionType::class, [
                'label'         => false,
                'entry_type'    => ContientElementType::class,
                'entry_options' => array_intersect_key($options, array_flip(["translations", "locale"])),
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])
            ->add('formules', CollectionType::class, [
                'label'         => false,
                'entry_type'    => FormuleType::class,
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
                'entry_options' => [
                    'undeterminedPlaceholder' => $options['translations']['formule.undetermined']
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Attestation::class
        ]);
        $resolver->setRequired('source');
        $resolver->setRequired('attestation');
        $resolver->setRequired('translations');
        $resolver->setDefined('locale');
        $resolver->setDefault('isClone', false);
    }
}
