<?php

namespace App\Form;

use App\Entity\Datation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class DatationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('postQuem', IntegerType::class, [
                'label'    => 'datation.fields.post_quem',
                'required' => false
            ])
            ->add('anteQuem', IntegerType::class, [
                'label'    => 'datation.fields.ante_quem',
                'required' => false
            ])
            ->add('commentaireFr', CKEditorType::class, array(
                'config_name' => 'styling_and_font',
                'attr'        => ['class' => 'semitic_keyboard', 'rows' => 2],
                'label'       => 'generic.fields.commentaire_fr',
                'required'    => false
            ))
            ->add('commentaireEn', CKEditorType::class, array(
                'config_name' => 'styling_and_font',
                'attr'        => ['class' => 'semitic_keyboard', 'rows' => 2],
                'label'       => 'generic.fields.commentaire_en',
                'required'    => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Datation::class,
        ]);
    }
}
