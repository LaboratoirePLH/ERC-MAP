<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TranslatedNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomFr', TextareaType::class, [
                'label'    => 'languages.fr',
                'required' => false,
                'attr'     => ['class' => 'no-return-textarea']
            ])
            ->add('nomEn', TextareaType::class, [
                'label'    => 'languages.en',
                'required' => false,
                'attr'     => ['class' => 'no-return-textarea']
            ]);
    }

    public function getBlockPrefix()
    {
        return "translatedname";
    }
}
