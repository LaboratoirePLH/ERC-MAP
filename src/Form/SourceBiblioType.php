<?php

namespace App\Form;

use App\Entity\SourceBiblio;
use App\Entity\Biblio;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SourceBiblioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('editionPrincipale', CheckboxType::class, [
                'label'      => 'source_biblio.fields.edition_principale',
                'label_attr' => ['class' => 'mainsource_field'],
                'required'   => false
            ])
            ->add('biblio_creation', ChoiceType::class, [
                'label'      => 'source_biblio.fields.biblio_creation',
                'label_attr' => [
                    'class' => 'biblio_creation_field'
                ],
                'mapped'   => false,
                'expanded' => true,
                'choices'  => [
                    'generic.choices.new' => 'yes',
                    'generic.choices.existing' => 'no',
                ],
            ])
            ->add('biblio', EntityType::class, [
                'label'      => 'source.fields.source_biblio',
                'label_attr' => [
                    'class' => 'biblio_creation_no'
                ],
                'required'     => false,
                'mapped'       => true,
                'class'        => Biblio::class,
                'choice_label' => 'affichage',
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.auteurBiblio', 'ASC');
                }
            ])
            ->add('biblioNew', BiblioType::class, [
                'label_attr' => [
                    'class' => 'biblio_creation_yes remove_this_label'
                ],
                'translations'  => $options['translations'],
                'required'      => false,
                'mapped'        => false,
                'property_path' => 'biblio'
            ])
            ->add('commentaireBiblioFr', TextareaType::class, [
                'label'    => 'generic.fields.commentaire_fr',
                'required' => false
            ])
            ->add('commentaireBiblioEn', TextareaType::class, [
                'label'    => 'generic.fields.commentaire_en',
                'required' => false
            ])
            ->add('referenceSource', TextType::class, [
                'label'    => 'source_biblio.fields.reference_source',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SourceBiblio::class,
        ]);
        $resolver->setRequired('locale');
        $resolver->setRequired('translations');
    }
}
