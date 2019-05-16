<?php

namespace App\Form;

use App\Entity\GrandeRegion;
use App\Entity\Localisation;
use App\Entity\SousRegion;
use App\Entity\EntitePolitique;
use App\Entity\QTopographie;
use App\Entity\QFonction;

use App\Form\Type\CitySearchType;
use App\Form\Type\DependentSelectType;
use App\Form\Type\PleiadesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;


class LocalisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];
        $builder
            ->add('id', HiddenType::class, [
                'required' => false
            ])
            ->add('reel', CheckboxType::class, [
                'label'    => 'generic.fields.localisation_reelle',
                'attr'     => ['class' => 'real_location_checkbox'],
                'required' => false,
            ])
            ->add('entitePolitique', EntityType::class, [
                'label'        => 'localisation.fields.entite_politique',
                'required'     => false,
                'multiple'     => false,
                'expanded'     => false,
                'class'        => EntitePolitique::class,
                'choice_label' => 'affichage'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('grandeSousRegion', DependentSelectType::class, [
                'label'          => null,
                'locale'         => $options['locale'],
                'translations'   => $options['translations'],
                'name'           => 'grandeRegion',
                'secondary_name' => 'sousRegion',
                'category_field' => 'grandeRegion',
                'attr'           => ['class' => 'grandeSousRegion'],
                'field_options'  => [
                    'label'        => 'localisation.fields.grande_region',
                    'label_attr'   => [
                        'id' => 'localisation_ville_granderegion',
                        'class' => 'citysearch_field' . ($options['region_required'] ? ' required' : '')
                    ],
                    'required'     => $options['region_required'],
                    'class'        => GrandeRegion::class,
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
                    'label'        => 'localisation.fields.sous_region',
                    'label_attr'   => ['id' => 'localisation_ville_sousregion', 'class' => 'citysearch_field'],
                    'required'     => false,
                    'class'        => SousRegion::class,
                    'choice_label' => 'nom'.ucfirst($locale),
                    'help'         => 'generic.help.regional_data',
                    'attr'         => [
                        'class' => 'autocomplete',
                        'data-placeholder' => $options['translations']['autocomplete.select_element']
                    ]
                ]
            ])
            ->add('pleiadesVille', PleiadesType::class, [
                'label_attr'   => ['id' => 'localisation_ville_id', 'class' => 'citysearch_field'],
                'attr'         => ['min' => 0, 'step' => 1],
                'label'        => 'localisation.fields.pleiades_ville',
                'search_label' => 'localisation.search_pleiades',
                'view_label'   => 'localisation.view_pleiades',
                'clear_label'  => 'generic.clear',
                'required'     => false
            ])
            ->add('nomVille', CitySearchType::class, [
                'label_attr'   => ['id' => 'localisation_ville_nom', 'class' => 'pleiades_field'],
                'label'        => 'localisation.fields.nom_ville',
                'search_label' => 'generic.search_database',
                'required'     => false
            ])
            ->add('latitude', NumberType::class, [
                'label_attr' => ['id' => 'localisation_ville_latitude', 'class' => 'pleiades_field citysearch_field'],
                'label'      => 'localisation.fields.latitude',
                'scale'      => 7,
                'required'   => false,
            ])
            ->add('longitude', NumberType::class, [
                'label_attr' => ['id' => 'localisation_ville_longitude', 'class' => 'pleiades_field citysearch_field'],
                'label'      => 'localisation.fields.longitude',
                'scale'      => 7,
                'required'   => false,
            ])
            ->add('pleiadesSite', PleiadesType::class, [
                'label_attr'   => ['id' => 'localisation_site_id'],
                'attr'         => ['min' => 0, 'step' => 1],
                'label'        => 'localisation.fields.pleiades_site',
                'search_label' => 'localisation.search_pleiades',
                'view_label'   => 'localisation.view_pleiades',
                'clear_label'  => 'generic.clear',
                'required'     => false
            ])
            ->add('nomSite', TextType::class, [
                'label_attr' => ['id' => 'localisation_site_nom', 'class' => 'pleiades_field'],
                'label'      => 'localisation.fields.nom_site',
                'required'   => false
            ])
            ->add('topographies', EntityType::class, [
                'label'        => 'localisation.fields.topographie',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => QTopographie::class,
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
            ->add('fonctions', EntityType::class, [
                'label'        => 'localisation.fields.fonction',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => QFonction::class,
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Localisation::class,
            'region_required' => false
        ]);
        $resolver->setRequired(['translations', 'locale']);
    }
}
