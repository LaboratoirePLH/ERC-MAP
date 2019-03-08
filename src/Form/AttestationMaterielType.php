<?php

namespace App\Form;

use App\Entity\AttestationMateriel;
use App\Entity\CategorieMateriel;
use App\Entity\Materiel;

use App\Form\Type\DependentSelectType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class AttestationMaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];
        $builder
            ->add('typeCategorieMateriel', DependentSelectType::class, [
                'locale'         => $options['locale'],
                'translations'   => $options['translations'],
                'name'           => 'categorieMateriel',
                'secondary_name' => 'materiel',
                'category_field' => 'categorieMateriel',
                'attr'           => ['class' => 'typeCategorieMateriel'],
                'field_options'  => [
                    'label'        => 'attestation.fields.categorie_materiel',
                    'required'     => false,
                    'class'        => CategorieMateriel::class,
                    'choice_label' => 'nom'.ucfirst($locale),
                    'attr'         => [
                        'class' => 'autocomplete',
                        'data-placeholder' => $options['translations']['autocomplete.select_element']
                    ],
                    'query_builder' => function (EntityRepository $er) use ($locale) {
                        return $er->createQueryBuilder('e')
                            ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                    }
                ],
                'secondary_field_options' => [
                    'label'        => 'attestation.fields.materiel',
                    'required'     => false,
                    'class'        => Materiel::class,
                    'choice_label' => 'nom'.ucfirst($locale),
                    'attr'         => [
                        'class' => 'autocomplete',
                        'data-placeholder' => $options['translations']['autocomplete.select_element']
                    ]
                ]
            ])
            ->add('quantite', IntegerType::class, [
                'label'    => 'generic.fields.quantite',
                'required' => false,
                'attr'     => ['min' => 0, 'step' => 1]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AttestationMateriel::class,
        ]);
        $resolver->setRequired('translations');
        $resolver->setDefined('locale');
    }
}
