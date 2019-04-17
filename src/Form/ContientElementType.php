<?php

namespace App\Form;

use App\Entity\CategorieElement;
use App\Entity\ContientElement;
use App\Entity\Element;
use App\Entity\GenreElement;
use App\Entity\NombreElement;

use App\Form\Type\SelectOrCreateType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ContientElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];
        $builder
            ->add('positionElement', IntegerType::class, [
                'label'    => 'element.fields.position',
                'required' => true,
                'attr'     => ['min' => 1, 'step' => 1, 'required' => true],
                'label_attr' => ['class' => 'required']
            ])
            ->add('element', SelectOrCreateType::class, [
                'label'                   => 'attestation.fields.element_invariant',
                'locale'                  => $options['locale'],
                'translations'            => $options['translations'],
                'field_name'              => 'element',
                'object_class'            => Element::class,
                'creation_form_class'     => ElementMiniType::class,
                'selection_choice_label'  => 'affichage',
                'selection_query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.id', 'DESC');
                }
            ])
            ->add('enContexte', CKEditorType::class, [
                'config_name' => 'styling_and_font',
                'attr'        => ['class' => 'semitic_keyboard'],
                'label'       => 'element.fields.en_contexte',
                'required'    => false
            ])
            ->add('suffixe', CheckboxType::class, [
                'label'      => 'element.fields.suffixe',
                'required'   => false,
            ])
            ->add('etatMorphologique', CKEditorType::class, [
                'config_name' => 'styling_and_font',
                'attr'        => ['class' => 'semitic_keyboard'],
                'label'    => 'element.fields.etat_morphologique',
                'required' => false
            ])
            ->add('genreElement', EntityType::class, [
                'label'        => 'element.fields.genre',
                'required'     => false,
                'multiple'     => false,
                'expanded'     => false,
                'class'        => GenreElement::class,
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
            ->add('nombreElement', EntityType::class, [
                'label'        => 'element.fields.nombre',
                'required'     => false,
                'multiple'     => false,
                'expanded'     => false,
                'class'        => NombreElement::class,
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
            ->add('categorieElement', EntityType::class, [
                'label'        => 'element.fields.categorie_contextuelle',
                'required'     => false,
                'multiple'     => false,
                'expanded'     => false,
                'class'        => CategorieElement::class,
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContientElement::class,
        ]);
        $resolver->setRequired('locale');
        $resolver->setRequired('translations');
    }
}
