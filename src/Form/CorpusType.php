<?php

namespace App\Form;

use App\Entity\Corpus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use FOS\CKEditorBundle\Form\Type\CKEditorType;

class CorpusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', CKEditorType::class, array(
                'config_name' => 'text_styling_only',
                'label'       => 'corpus.fields.nom',
                'attr'        => ['rows' => 2],
                'required'    => false
            ))
            ->add('nomComplet', CKEditorType::class, array(
                'config_name' => 'text_styling_only',
                'label'       => 'corpus.fields.nom_complet',
                'attr'        => ['rows' => 2],
                'required'    => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Corpus::class,
        ]);
    }
}
