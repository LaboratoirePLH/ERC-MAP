<?php

namespace App\Form;

use App\Entity\SousRegion;
use App\Entity\EntitePolitique;
use App\Entity\QTopographie;
use App\Entity\QFonction;

use App\Entity\Localisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class LocalisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];
        $builder
            ->add('sousRegion', EntityType::class, [
                'label'        => 'localisation.fields.region',
                'required'     => false,
                'class'        => SousRegion::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'group_by'     => 'grandeRegion.nom'.ucfirst($locale),
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->leftJoin('e.grandeRegion', 'c')
                        ->addSelect('c')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
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
            // ->add('topographies')
            // ->add('fonctions')
            // ->add('pleiadesVille')
            // ->add('nomVille')
            // ->add('latitude')
            // ->add('longitude')
            // ->add('pleiadesSite')
            // ->add('nomSite')
            // ->add('reel')
            // ->add('geom')
            // ->add('commentaireFr')
            // ->add('commentaireEn')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Localisation::class,
        ]);
        $resolver->setRequired(['translations', 'locale']);
    }
}
