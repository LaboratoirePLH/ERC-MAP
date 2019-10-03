<?php

namespace App\Form;

use App\Entity\Biblio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class BiblioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("estCorpus", CheckboxType::class, [
                'label'      => 'biblio.fields.corpus',
                'required'   => false,
                'label_attr' => [
                    'class' => 'dependent_field_corpus_main'
                ]
            ])
            ->add('titreAbrege', CKEditorType::class, [
                'config_name' => 'styling_and_font',
                'attr'        => ['class' => 'semitic_keyboard'],
                'label'       => 'biblio.fields.titre_abrege',
                'required'    => false
            ])
            ->add('titreComplet', CKEditorType::class, array(
                'config_name' => 'styling_and_font',
                'attr'        => ['class' => 'semitic_keyboard'],
                'label'       => 'biblio.fields.titre_complet',
                'required'    => false
            ))
            ->add('annee', IntegerType::class, [
                'label'    => 'biblio.fields.annee',
                'label_attr' => [
                    'class' => 'dependent_field_corpus dependent_field_inverse'
                ],
                'required' => false
            ])
            ->add('auteurBiblio', TextType::class, [
                'label'    => 'biblio.fields.auteur',
                'label_attr' => [
                    'class' => 'dependent_field_corpus dependent_field_inverse'
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Biblio::class,
        ]);
        $resolver->setRequired('translations');
        $resolver->setDefined('locale');
    }
}
