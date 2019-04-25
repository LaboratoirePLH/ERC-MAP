<?php

namespace App\Form;

use App\Entity\Attestation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;


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
            ->add('extraitAvecRestitution', CKEditorType::class, [
                'config_name' => 'styling_and_font',
                'attr'        => ['class' => 'semitic_keyboard'],
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
