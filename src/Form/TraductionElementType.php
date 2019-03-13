<?php

namespace App\Form;

use App\Entity\TraductionElement;
use App\Form\Type\TranslatedNameType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraductionElementType extends TranslatedNameType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TraductionElement::class,
        ]);
    }
}
