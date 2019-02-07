<?php

namespace App\Form;

use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuteurType extends AbstractType
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
            // ->add('titres')
            // ->add('sources')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Auteur::class,
        ]);
    }
}
