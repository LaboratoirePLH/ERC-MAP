<?php

namespace App\Form;

use App\Entity\ActiviteAgent;
use App\Entity\Agent;
use App\Entity\Agentivite;
use App\Entity\Genre;
use App\Entity\Nature;
use App\Entity\StatutAffiche;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class AgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];
        $builder
            ->add('designation', CKEditorType::class, [
                'label'       => 'agent.fields.designation',
                'config_name' => 'text_styling_only',
                'required'    => false
            ])
            ->add('agentivites', EntityType::class, [
                'label'        => 'agent.fields.agentivite',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Agentivite::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('natures', EntityType::class, [
                'label'        => 'agent.fields.nature',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Nature::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('genres', EntityType::class, [
                'label'        => 'agent.fields.genre',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Genre::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('statutAffiches', EntityType::class, [
                'label'        => 'agent.fields.statut_affiche',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => StatutAffiche::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('activites', EntityType::class, [
                'label'        => 'agent.fields.activite',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => ActiviteAgent::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('estLocalisee', CheckboxType::class, [
                'label'      => 'generic.fields.est_localisee',
                'label_attr' => [
                    'class' => 'dependent_field_estlocalisee_main'
                ],
                'required' => false,
            ])
            ->add('localisation', LocalisationType::class, [
                'label'           => 'generic.fields.localisation',
                'required'        => false,
                'region_required' => false,
                'attr'            => ['class' => 'localisation_form dependent_field_estlocalisee'],
                'locale'          => $options['locale'],
                'translations'    => $options['translations'],
            ])
            ->add('commentaireFr', CKEditorType::class, array(
                'config_name' => 'text_styling_only',
                'label'       => 'generic.fields.commentaire_fr',
                'attr'        => ['rows' => 2],
                'required'    => false
            ))
            ->add('commentaireEn', CKEditorType::class, array(
                'config_name' => 'text_styling_only',
                'label'       => 'generic.fields.commentaire_en',
                'attr'        => ['rows' => 2],
                'required'    => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
        $resolver->setRequired('translations');
        $resolver->setDefined('locale');
    }
}
