<?php

namespace App\Form;

use App\Entity\TraductionAttestation;
use App\Form\Type\TranslatedNameType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraductionAttestationType extends TranslatedNameType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TraductionAttestation::class,
        ]);
    }
}
