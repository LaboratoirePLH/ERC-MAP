<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class SelectOrCreateType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("decision", ChoiceType::class, [
                'label'       => '',
                'attr'        => ['class' => 'form-check-inline pt-1'],
                'mapped'      => false,
                'expanded'    => true,
                'required'    => false,
                'placeholder' => $options['allow_none'] ? $options['none_label'] : false,
                'choices'     => [
                    'generic.choices.new'      => 'create',
                    'generic.choices.existing' => 'select',
                ]
            ])
            ->add("selection", EntityType::class, [
                'required'     => false,
                'mapped'       => false,
                'class'        => $options['object_class'],
                'choice_label' => $options['selection_choice_label'],
                'attr'         => [
                    'class'            => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'query_builder' => $options['selection_query_builder']
            ])
            ->add("creation", $options['creation_form_class'], [
                'attr'          => ['class' => $options['creation_form_css_class']],
                'locale'        => $options['locale'],
                'translations'  => $options['translations'],
                'required'      => false,
                'mapped'        => false,
                'property_path' => $options['field_name']
            ]);

        $builder->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['locale', 'translations', 'field_name', 'object_class', 'creation_form_class', 'selection_query_builder', 'selection_choice_label']);
        $resolver->setDefault('locale', 'en');
        $resolver->setDefault('allow_none', false);
        $resolver->setDefault('none_label', 'generic.choices.none');
        $resolver->setDefault('isClone', false);
        $resolver->setDefault('default_decision', null);
        $resolver->setDefault('formAction', null);
        $resolver->setDefault('creation_form_css_class', '');
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'field_name' => $options['field_name'],
            'uuid' => uniqid($options['field_name'])
        ]);
    }

    public function getBlockPrefix()
    {
        return "selectorcreate";
    }

    public function mapFormsToData($forms, &$data)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $decision = $forms['decision']->getData();
        if ($decision === "create") {
            $data = $forms['creation']->getData();
        } else if ($decision === "select") {
            $data = $forms['selection']->getData();
        }
        if (empty($data)) {
            $data = null;
        }
    }

    public function mapDataToForms($data, $forms)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        // initialize form field values
        // there is no data yet, set decision to default choice
        $formAction = $forms['decision']->getParent()->getConfig()->getOption('formAction');
        $isClone = $forms['decision']->getParent()->getConfig()->getOption('isClone');
        if (null === $data && $formAction === "create" && !$isClone) {
            $forms['decision']->setData(
                $forms['decision']->getParent()->getConfig()->getOption('default_decision')
            );
            return;
        }
        $decision = "select";
        if ($forms['decision']->getParent()->getConfig()->getOption('allow_none') === true && $data === null) {
            $decision = null;
        }
        $forms['decision']->setData($decision);
        $forms['selection']->setData($data);
        $forms['creation']->setData(null);
    }
}
