<?php

namespace App\Form;

use App\Entity\CategorieElement;
use App\Entity\Element;
use App\Entity\NatureElement;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ElementMiniType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];
        $builder
            ->add('etatAbsolu', TextareaType::class, [
                'attr'        => ['class' => 'froala'],
                'label'       => 'element.fields.etat_absolu',
                'required'    => false
            ])
            ->add('betaCode', TextType::class, [
                'label'    => 'element.fields.beta_code',
                'required' => false
            ])
            ->add('traductions', CollectionType::class, [
                'label'         => 'generic.fields.translations',
                'attr'          => ['class' => 'element_traductions'],
                'entry_type'    => TraductionElementType::class,
                'by_reference'  => false,
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])
            ->add('natureElement', EntityType::class, [
                'label'        => 'element.fields.nature',
                'required'     => false,
                'multiple'     => false,
                'expanded'     => false,
                'class'        => NatureElement::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('categories', EntityType::class, [
                'label'        => 'element.fields.categories_invariantes',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => CategorieElement::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete autocomplete-max-3',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Element::class,
        ]);
        $resolver->setRequired('locale');
        $resolver->setRequired('translations');
    }
}
