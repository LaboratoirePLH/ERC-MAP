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
                'config_name' => 'text_styling_only',
                'label'       => 'generic.fields.commentaire_fr',
                'attr'        => ['rows' => 2],
                'required'    => false
            ))
            ->add('commentaireEn', CKEditorType::class, array(
                'config_name' => 'text_styling_only',
                'label'       => 'generic.fields.commentaire_en',
                'attr'        => ['rows' => 2],
                'required'    => false
            ))
            // ->add('fiabiliteDatation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Datation::class,
        ]);
    }
}
