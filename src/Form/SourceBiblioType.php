<?php

namespace App\Form;

use App\Entity\SourceBiblio;
use App\Entity\Biblio;

use App\Form\Type\SelectOrCreateType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SourceBiblioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('editionPrincipale', CheckboxType::class, [
                'label'      => 'source_biblio.fields.edition_principale',
                'label_attr' => ['class' => 'mainsource_field'],
                'required'   => false,
                'help'       => 'generic.help.edition_principale'
            ])
            ->add('biblio', SelectOrCreateType::class, [
                'label'                   => 'source.fields.source_biblio',
                'locale'                  => $options['locale'],
                'translations'            => $options['translations'],
                'field_name'              => 'biblio',
                'object_class'            => Biblio::class,
                'creation_form_class'     => BiblioType::class,
                'selection_choice_label'  => 'affichage',
                'selection_query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.auteurBiblio', 'ASC');
                }
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
