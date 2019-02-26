<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class CitySearchType extends TextType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefined(['search_label']);
        $resolver->setDefault('search_label', 'Search');
        $resolver->setDefault('help', 'generic.help.city_search');
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'search_label' => $options['search_label'],
            'type' => 'text'
        ]);
    }

    public function getBlockPrefix(){
        return "citysearch";
    }
}