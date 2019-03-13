<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class TranslatedNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomFr', TextType::class, [
                'label'    => 'languages.fr',
                'required' => false
            ])
            ->add('nomEn', TextType::class, [
                'label'    => 'languages.en',
                'required' => false
            ])
        ;
    }

    public function getBlockPrefix(){
        return "translatedname";
    }
}
