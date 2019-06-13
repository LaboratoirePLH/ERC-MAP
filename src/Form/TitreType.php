<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Titre;

use App\Form\Type\TranslatedNameType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TitreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];
        $builder
            ->add('translatedName', TranslatedNameType::class, [
                'label'    => 'source.fields.titre',
                'required' => false
            ])
            ->add('auteurs', EntityType::class, [
                'label'        => 'source.fields.auteurs',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Auteur::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Titre::class,
        ]);
        $resolver->setRequired('locale');
        $resolver->setRequired('translations');
    }
}
