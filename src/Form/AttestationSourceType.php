<?php

namespace App\Form;

use App\Entity\Attestation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;


class AttestationSourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, [
                'required' => false
            ])
            ->add('passage', CKEditorType::class, [
                'config_name' => 'text_styling_only',
                'label'       => 'attestation.fields.passage',
                'required'    => true
            ])
            ->add('extraitAvecRestitution', CKEditorType::class, [
                'config_name' => 'text_styling_only',
                'label'       => 'attestation.fields.extrait_avec_restitution',
                'required'    => true
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
