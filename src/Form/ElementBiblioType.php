<?php

namespace App\Form;

use App\Entity\ElementBiblio;
use App\Entity\Biblio;

use App\Form\Type\SelectOrCreateType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class ElementBiblioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('biblio', SelectOrCreateType::class, [
                'label'                   => 'element.fields.element_biblio',
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
            ->add('referenceElement', TextType::class, [
                'label'    => 'element.fields.reference_element',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ElementBiblio::class,
        ]);
        $resolver->setRequired('locale');
        $resolver->setRequired('translations');
    }
}
