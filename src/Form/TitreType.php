<?php

namespace App\Form;

use App\Entity\Titre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TitreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomFr', TextType::class, [
                'label' => 'generic.fields.nom_fr',
                'required' => false
            ])
            ->add('nomEn', TextType::class, [
                'label' => 'generic.fields.nom_en',
                'required' => false
            ])
            ->add('auteurs', EntityType::class, [
                'label' => 'source.fields.auteurs',
                'required' => false,
                'multiple' => true,
                'expanded' => false,
                'class' => Auteur::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         =>  [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Titre::class,
        ]);
    }
}
