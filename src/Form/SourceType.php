<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Langue;
use App\Entity\Materiau;
use App\Entity\Source;
use App\Entity\Titre;
use App\Entity\TypeSource;
use App\Entity\TypeSupport;

use App\Form\Type\SelectOrCreateType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];

        $builder
            ->add('traduireFr', CheckboxType::class, [
                'label'      => 'languages.fr',
                'required' => false
            ])
            ->add('traduireEn', CheckboxType::class, [
                'label'      => 'languages.en',
                'required' => false
            ])
            ->add('citation', CheckboxType::class, [
                'label'      => 'source.fields.citation',
                'label_attr' => [
                    'class' => 'dependent_field_quote_main'
                ],
                'required' => false
            ])
            ->add('urlTexte', UrlType::class, [
                'label'    => 'source.fields.url_texte',
                'required' => false
            ])
            ->add('urlImage', UrlType::class, [
                'label'    => 'source.fields.url_image',
                'label_attr' => [
                    'class' => 'dependent_field_iconography'
                ],
                'required' => false
            ])
            ->add('inSitu', CheckboxType::class, [
                'label'    => 'source.fields.in_situ',
                'label_attr' => [
                    'class' => 'dependent_field_insitu_main'
                ],
                'required' => false
            ])
            ->add('iconographie', CheckboxType::class, [
                'label'    => 'source.fields.iconographie',
                'label_attr' => [
                    'class' => 'dependent_field_iconography_main'
                ],
                'required' => false
            ])
            ->add('commentaireFr', TextareaType::class, [
                'label'    => 'generic.fields.commentaire_fr',
                'required' => false
            ])
            ->add('commentaireEn', TextareaType::class, [
                'label'    => 'generic.fields.commentaire_en',
                'required' => false
            ])
            ->add('typeSource', EntityType::class, [
                'label'        => 'source.fields.type_source',
                'required'     => false,
                'class'        => TypeSource::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'group_by'      => 'categorieSource.nom'.ucfirst($locale),
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->leftJoin('e.categorieSource', 'c')
                        ->addSelect('c')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('materiau', EntityType::class, [
                'label'        => 'source.fields.materiau',
                'required'     => false,
                'class'        => Materiau::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'group_by'     => 'categorieMateriau.nom'.ucfirst($locale),
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->leftJoin('e.categorieMateriau', 'c')
                        ->addSelect('c')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('typeSupport', EntityType::class, [
                'label'        => 'source.fields.support',
                'required'     => false,
                'class'        => TypeSupport::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'group_by'     => 'categorieSupport.nom'.ucfirst($locale),
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->leftJoin('e.categorieSupport', 'c')
                        ->addSelect('c')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('auteurs', EntityType::class, [
                'label'        => 'source.fields.auteurs',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Auteur::class,
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
            ->add('langues', EntityType::class, [
                'label'        => 'source.fields.langues',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Langue::class,
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
            ->add('titrePrincipal', SelectOrCreateType::class, [
                'label'                   => 'source.fields.titre_principal',
                'locale'                  => $options['locale'],
                'translations'            => $options['translations'],
                'field_name'              => 'titrePrincipal',
                'object_class'            => Titre::class,
                'creation_form_class'     => TitreType::class,
                'selection_choice_label'  => 'nom'.ucfirst($locale),
                'selection_query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('titresCites', EntityType::class, [
                'label'      => 'source.fields.titres_cites',
                'label_attr' => [
                    'class' => 'dependent_field_quote'
                ],
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Titre::class,
                'choice_label' => 'affichage'.ucfirst($locale),
                'attr'         => [
                    'class'            => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('datation', DatationType::class)
            ->add('sourceBiblios', CollectionType::class, [
                'label'         => false,
                'entry_type'    => SourceBiblioType::class,
                'entry_options' => array_intersect_key($options, array_flip(["translations","locale"])),
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])
            ->add('save',      SubmitType::class, [
                'label' => 'generic.save'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Source::class,
        ]);
        $resolver->setRequired('locale');
        $resolver->setRequired('translations');
    }
}
