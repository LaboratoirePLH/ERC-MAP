<?php

namespace App\Form;

use App\Entity\Chercheur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChercheurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label'    => 'chercheur.fields.username',
                'disabled' => true,
                'required' => false,
            ])
            ->add('prenomNom', TextType::class, [
                'label'    => 'chercheur.fields.nom',
                'required' => true,
            ])
            ->add('institution', TextType::class, [
                'label'    => 'chercheur.fields.institution',
                'required' => true,
            ])
            ->add('mail', TextType::class, [
                'label'    => 'chercheur.fields.mail',
                'required' => true,
            ])
            ->add('preferenceLangue', ChoiceType::class, [
                'label'    => 'chercheur.fields.langue',
                'required' => true,
                'choices'  => [
                    'languages.fr' => 'fr',
                    'languages.en' => 'en',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Chercheur::class,
        ]);
    }
}
