<?php

namespace App\Form;

use App\Entity\Attestation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class AttestationSourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, [
                'required' => false
            ])
            ->add('passage', TextType::class, [
                'label'       => 'attestation.fields.passage',
                'required'    => true
            ])
            ->add('extraitAvecRestitution', TextareaType::class, [
                'attr'        => ['class' => 'froala'],
                'label'       => 'attestation.fields.extrait_avec_restitution',
                'required'    => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Attestation::class
        ]);
    }
}
