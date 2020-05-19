<?php

namespace App\EasyAdmin\Filter;

use App\Entity\GrandeRegion;
use App\Entity\SousRegion;
use App\Form\Type\DependentSelectType;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\FilterType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrandeSousRegion extends FilterType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label'        => null,
            'locale'       => 'fr',
            'translations' => [
                'autocomplete.select_element' => 'autocomplete.select_element',
                'autocomplete.select_multiple' => 'autocomplete.select_multiple',
            ],
            'name'           => 'grandeRegion',
            'secondary_name' => 'sousRegion',
            'category_field' => 'grandeRegion',
            'attr'           => ['class' => 'grandeSousRegion'],
            'field_options'  => [
                'label'        => 'localisation.fields.grande_region',
                'class'        => GrandeRegion::class,
                'choice_label' => 'nomFr',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nomFr', 'ASC');
                }
            ],
            'secondary_field_options' => [
                'label'        => 'localisation.fields.sous_region',
                'class'        => SousRegion::class,
                'choice_label' => 'nomFr',
            ]
        ]);
    }

    public function getParent()
    {
        return DependentSelectType::class;
    }

    public function filter(QueryBuilder $queryBuilder, FormInterface $form, array $metadata)
    {
        if (null !== $form->getData()) {
            $formData = $form->getData();

            $queryBuilder
                ->leftJoin('entity.grandeRegion', 'grandeRegion')
                ->andWhere('grandeRegion.id = :gr_id')
                ->setParameter('gr_id', $formData['grandeRegion']->getId());

            if ($formData['sousRegion'] != null) {
                $queryBuilder
                    ->leftJoin('entity.sousRegion', 'sousRegion')
                    ->andWhere('sousRegion.id = :sr_id')
                    ->setParameter('sr_id', $formData['sousRegion']->getId());
            }
        }
    }
}
