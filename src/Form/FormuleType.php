<?php

namespace App\Form;

use App\Entity\Formule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class FormuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, [
                'required' => false
            ])
            ->add('formule', HiddenType::class, [
                'required' => true
            ])
            ->add('positionFormule', HiddenType::class, [
                'required' => true
            ])
            ->add('createurId', HiddenType::class, [
                'required' => false,
                'mapped'   => false
            ])
            ->add('createur', HiddenType::class, [
                'required' => false,
                'mapped'   => false
            ])
            ->add('puissancesDivines', IntegerType::class, [
                'label'      => 'formule.fields.puissances_divines',
                'attr'       => ['min' => 0],
                'empty_data' => 1,
                'required'   => false
            ])
        ;

        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event){
            $form = $event->getForm();
            $entity = $event->getData();
            if($entity){
                $form->get('createur')->setData($entity->getCreateur()->getPrenomNom());
                $form->get('createurId')->setData($entity->getCreateur()->getId());
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formule::class,
        ]);
    }
}
