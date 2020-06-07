<?php

namespace App\Form;

use App\Entity\CategorieElement;
use App\Entity\Element;
use App\Entity\Localisation;
use App\Entity\NatureElement;

use App\Form\Type\SelectOrCreateType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];
        $builder
            ->add('traduireFr', CheckboxType::class, [
                'label'    => 'languages.fr',
                'required' => false
            ])
            ->add('traduireEn', CheckboxType::class, [
                'label'    => 'languages.en',
                'required' => false
            ])
            ->add('etatAbsolu', TextType::class, [
                'label'    => 'element.fields.etat_absolu',
                'required' => false
            ])
            ->add('etatAbsolu', Type\QuillType::class, [
                'attr'     => ['class' => 'wysiwyg-editor'],
                'label'    => 'element.fields.etat_absolu',
                'required' => false
            ])
            ->add('betaCode', TextType::class, [
                'label'    => 'element.fields.beta_code',
                'required' => false
            ])
            ->add('traductions', CollectionType::class, [
                'label'         => 'generic.fields.translations',
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
                'choice_label' => 'nom' . ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                }
            ])
            ->add('categories', EntityType::class, [
                'label'        => 'element.fields.categories_invariantes',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => CategorieElement::class,
                'choice_label' => 'nom' . ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete autocomplete-max-3',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                }
            ])
            ->add('aReference', CheckboxType::class, [
                'label'      => 'element.fields.a_reference',
                'label_attr' => [
                    'class' => 'dependent_field_areference_main'
                ],
                'required' => false,
            ])
            ->add('theonymesImplicites', CollectionType::class, [
                'label'         => 'element.fields.theonymes_implicites',
                'entry_type'    => SelectOrCreateType::class,
                'entry_options' => [
                    'locale'                  => $options['locale'],
                    'translations'            => $options['translations'],
                    'field_name'              => 'theonymesImplicites',
                    'object_class'            => Element::class,
                    'creation_form_class'     => ElementMiniType::class,
                    'selection_choice_label'  => 'affichage',
                    'selection_query_builder' => function (EntityRepository $er) use ($options) {
                        $qb = $er->createQueryBuilder('e');
                        if ($options['element']->getId() !== null) {
                            $qb = $qb->where($qb->expr()->neq('e.id', $options['element']->getId()));
                        }
                        return $qb->orderBy('e.id', 'DESC');
                    }
                ],
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])
            ->add('theonymesConstruits', CollectionType::class, [
                'label'         => 'element.fields.theonymes_construits',
                'entry_type'    => SelectOrCreateType::class,
                'entry_options' => [
                    'locale'                  => $options['locale'],
                    'translations'            => $options['translations'],
                    'field_name'              => 'theonymesConstruits',
                    'object_class'            => Element::class,
                    'creation_form_class'     => ElementMiniType::class,
                    'selection_choice_label'  => 'affichage',
                    'selection_query_builder' => function (EntityRepository $er) use ($options) {
                        $qb = $er->createQueryBuilder('e');
                        if ($options['element']->getId() !== null) {
                            $qb = $qb->where($qb->expr()->neq('e.id', $options['element']->getId()));
                        }
                        return $qb->orderBy('e.id', 'DESC');
                    }
                ],
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])
            ->add('localisation', SelectOrCreateType::class, [
                'label'                    => false,
                'required'                 => false,
                'locale'                   => $options['locale'],
                'translations'             => $options['translations'],
                'field_name'               => 'localisation',
                'object_class'             => Localisation::class,
                'creation_form_class'      => LocalisationType::class,
                'creation_form_css_class'  => 'localisation_form',
                'selection_form_css_class' => 'localisation_selection',
                'selection_choice_label'   => 'affichage' . ucfirst($locale),
                'allow_none'               => true,
                'formAction'               => $options['formAction'],
                'isClone'                  => false,
                'selection_query_builder'  => function (EntityRepository $er) use ($locale) {
                    $nameField = 'nom' . ucfirst($locale);
                    $qb = $er->createQueryBuilder('e');
                    return $qb
                        ->leftJoin('e.grandeRegion', 'gr')
                        ->leftJoin('e.sousRegion', 'sr')
                        ->addOrderBy("unaccent(gr.$nameField)", 'ASC')
                        ->addOrderBy("unaccent(sr.$nameField)", 'ASC')
                        ->addOrderBy("e.nomVille", 'ASC')
                        ->addOrderBy("e.nomSite", 'ASC')
                        ->addOrderBy("e.id", 'ASC');
                }
            ])
            ->add('elementBiblios', CollectionType::class, [
                'label'         => false,
                'entry_type'    => ElementBiblioType::class,
                'entry_options' => array_intersect_key($options, array_flip(["translations", "locale"])),
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])
            ->add('commentaireFr', Type\QuillType::class, array(
                'attr'     => ['class' => 'wysiwyg-editor', 'rows' => 2],
                'label'    => 'generic.fields.commentaire_fr',
                'required' => false
            ))
            ->add('commentaireEn', Type\QuillType::class, array(
                'attr'     => ['class' => 'wysiwyg-editor', 'rows' => 2],
                'label'    => 'generic.fields.commentaire_en',
                'required' => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Element::class,
        ]);
        $resolver->setRequired('locale');
        $resolver->setRequired('translations');
        $resolver->setRequired('element');
        $resolver->setRequired('formAction');
    }
}
