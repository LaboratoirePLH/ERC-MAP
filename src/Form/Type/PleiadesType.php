<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class PleiadesType extends IntegerType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefined(['search_label', 'clear_label', 'view_label']);
        $resolver->setDefault('search_label', 'Search');
        $resolver->setDefault('clear_label', 'Clear');
        $resolver->setDefault('view_label', 'View');
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'search_label' => $options['search_label'],
            'clear_label' => $options['clear_label'],
            'view_label' => $options['view_label'],
            'type' => 'number'
        ]);
    }

    public function getBlockPrefix(){
        return "pleiades";
    }
}