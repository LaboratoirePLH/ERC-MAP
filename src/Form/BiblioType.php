<?php

namespace App\Form;

use App\Entity\Biblio;
use App\Entity\Corpus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class BiblioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titreAbrege', TextType::class, [
                'label' => 'biblio.fields.titre_abrege',
                'required' => false
            ])
            ->add('titreComplet', CKEditorType::class, array(
                'config_name' => 'text_styling_only',
                'label' => 'biblio.fields.titre_complet',
                'required' => false
            ))
            ->add('annee', IntegerType::class, [
                'label' => 'biblio.fields.annee',
                'required' => false
            ])
            ->add('auteurBiblio', TextType::class, [
                'label' => 'biblio.fields.auteur',
                'required' => false
            ])
            ->add('corpus_creation', ChoiceType::class, [
                'label' => 'biblio.fields.corpus_creation',
                'label_attr' => [
                    'class' => 'corpus_creation_field'
                ],
                'mapped' => false,
                'expanded' => true,
                'choices'  => [
                    'generic.choices.new' => 'yes',
                    'generic.choices.existing' => 'no',
                ],
            ])
            ->add('corpus', EntityType::class, [
                'label' => 'biblio.fields.corpus',
                'label_attr' => [
                    'class' => 'corpus_creation_no'
                ],
                'required' => false,
                'mapped' => true,
                'class'        => Corpus::class,
                'choice_label' => 'nom',
                'attr'         =>  [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom', 'ASC');
                }
            ])
            ->add('corpusNew', CorpusType::class, [
                'label_attr' => [
                    'class' => 'corpus_creation_yes remove_this_label'
                ],
                'required' => false,
                'mapped' => false,
                'property_path' => 'corpus'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Biblio::class,
        ]);
        $resolver->setRequired('translations');
    }
}
