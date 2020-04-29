<?php

namespace App\EasyAdmin\Filter;

use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\FilterType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormuleAttestation extends FilterType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'min' => '1'
            ],
        ]);
    }

    public function getParent()
    {
        return IntegerType::class;
    }

    public function filter(QueryBuilder $queryBuilder, FormInterface $form, array $metadata)
    {
        if (null !== $form->getData()) {
            $queryBuilder
                ->leftJoin('entity.attestation', 'attestation')
                ->andWhere('attestation.id = :id')
                ->setParameter('id', $form->getData());
        }
    }
}
