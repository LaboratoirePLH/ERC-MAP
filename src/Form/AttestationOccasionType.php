<?php

namespace App\Form;

use App\Entity\AttestationOccasion;
use App\Entity\Occasion;
use App\Entity\CategorieOccasion;

use App\Form\Type\DependentSelectType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class AttestationOccasionType extends AbstractType // implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];
        $builder
            ->add('typeCategorieOccasion', DependentSelectType::class, [
                'locale'         => $options['locale'],
                'translations'   => $options['translations'],
                'name'           => 'categorieOccasion',
                'secondary_name' => 'occasion',
                'category_field' => 'categorieOccasion',
                'attr'           => ['class' => 'typeCategorieOccasion'],
                'field_options'  => [
                    'label'        => 'attestation.fields.categorie_occasion',
                    'required'     => false,
                    'class'        => CategorieOccasion::class,
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
                    'label'        => 'attestation.fields.occasion',
                    'required'     => true,
                    'class'        => Occasion::class,
                    'choice_label' => 'nom'.ucfirst($locale),
                    'attr'         => [
                        'class' => 'autocomplete',
                        'data-placeholder' => $options['translations']['autocomplete.select_element']
                    ],
                    'query_builder' => function (EntityRepository $er) use ($locale) {
                        return $er->createQueryBuilder('e')
                            ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                    }
                ]
            ])
        ;
        // $builder->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AttestationOccasion::class,
        ]);
        $resolver->setRequired('translations');
        $resolver->setDefined('locale');
    }

    // public function mapFormsToData($forms, &$data)
    // {
    //     /** @var FormInterface[] $forms */
    //     $forms = iterator_to_array($forms);

    //     $formData = $forms['typeCategorieOccasion']->getData();

    //     $data = $formData['occasion'] ?? null;
    // }

    // public function mapDataToForms($data, $forms)
    // {
    //     /** @var FormInterface[] $forms */
    //     $forms = iterator_to_array($forms);

    //     // initialize form field values
    //     // there is no data yet, set decision to default choice
    //     $newData = [
    //         "categorieOccasion" => null,
    //         "occasion" => null,
    //     ];
    //     if ($data !== null) {
    //         $newData['occasion'] = $data;
    //         $newData['categorieOccasion'] = $data->getCategorieOccasion();
    //     }
    //     $forms['typeCategorieOccasion']->setData($newData);
    // }
}
