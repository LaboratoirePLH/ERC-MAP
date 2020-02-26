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
    private $uuid;
    private $name;
    private $entityClass;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->name = $options['field_name'];
        $this->uuid = uniqid($this->name);
        $this->entityClass = $options['object_class'];

        $builder
            ->add("decision", ChoiceType::class, [
                'label'      => '',
                'label_attr' => [
                    'id' => "{$this->name}_decision_{$this->uuid}"
                ],
                'attr'     => [
                    'class' => 'form-check-inline pt-1'
                ],
                'mapped'   => false,
                'expanded' => true,
                'required' => false,
                'placeholder' => $options['allow_none'] ? 'generic.choices.none' : false,
                'choices'  => [
                    'generic.choices.new' => 'create',
                    'generic.choices.existing' => 'select',
                ]
            ])
            ->add("selection", EntityType::class, [
                'label_attr' => [
                    'id' => "{$this->name}_selection_{$this->uuid}"
                ],
                'required'     => false,
                'mapped'       => false,
                'class'        => $options['object_class'],
                'choice_label' => $options['selection_choice_label'],
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'query_builder' => $options['selection_query_builder']
            ])
            ->add("creation", $options['creation_form_class'], [
                'label_attr' => [
                    'id' => "{$this->name}_creation_{$this->uuid}"
                ],
                'locale'        => $options['locale'],
                'translations'  => $options['translations'],
                'required'      => false,
                'mapped'        => false,
                'property_path' => $this->name
            ]);

        $builder->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['locale', 'translations', 'field_name', 'object_class', 'creation_form_class', 'selection_query_builder', 'selection_choice_label']);
        $resolver->setDefault('locale', 'en');
        $resolver->setDefault('allow_none', false);
        $resolver->setDefault('isClone', false);
        $resolver->setDefault('default_decision', null);
        $resolver->setDefault('action', null);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'field_name' => $this->name,
            'uuid' => $this->uuid
        ]);
    }

    public function getBlockPrefix(){
        return "selectorcreate";
    }

    public function mapFormsToData($forms, &$data)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $decision = $forms['decision']->getData();
        if($decision === "create"){
            $data = $forms['creation']->getData();
        }
        else if ($decision === "select"){
            $data = $forms['selection']->getData();
        }
        if(empty($data)){
            $data = null;
        }
    }

    public function mapDataToForms($data, $forms)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        // initialize form field values
        // there is no data yet, set decision to default choice
        $action = $forms['decision']->getParent()->getConfig()->getOption('action');
        $isClone = $forms['decision']->getParent()->getConfig()->getOption('isClone');
        if (null === $data && $action === "create" && !$isClone) {
            $forms['decision']->setData(
                $forms['decision']->getParent()->getConfig()->getOption('default_decision')
            );
            return;
        }
        $decision = "select";
        if($forms['decision']->getParent()->getConfig()->getOption('allow_none') === true && $data === null){
            $decision = null;
        }
        $forms['decision']->setData($decision);
        $forms['selection']->setData($data);
        $forms['creation']->setData(null);
    }
}