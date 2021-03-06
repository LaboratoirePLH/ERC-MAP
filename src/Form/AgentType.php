<?php

namespace App\Form;

use App\Entity\ActiviteAgent;
use App\Entity\Agent;
use App\Entity\Agentivite;
use App\Entity\Genre;
use App\Entity\Localisation;
use App\Entity\Nature;
use App\Entity\StatutAffiche;
use App\Form\Type\SelectOrCreateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class AgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];
        $builder
            ->add('designation', Type\QuillType::class, [
                'label'       => 'agent.fields.designation',
                'attr'        => ['class' => 'wysiwyg-editor'],
                'required'    => false
            ])
            ->add('agentivites', EntityType::class, [
                'label'        => 'agent.fields.agentivite',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Agentivite::class,
                'choice_label' => 'nom' . ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                }
            ])
            ->add('natures', EntityType::class, [
                'label'        => 'agent.fields.nature',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Nature::class,
                'choice_label' => 'nom' . ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                }
            ])
            ->add('genres', EntityType::class, [
                'label'        => 'agent.fields.genre',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Genre::class,
                'choice_label' => 'nom' . ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                }
            ])
            ->add('statutAffiches', EntityType::class, [
                'label'        => 'agent.fields.statut_affiche',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => StatutAffiche::class,
                'choice_label' => 'nom' . ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                }
            ])
            ->add('activites', EntityType::class, [
                'label'        => 'agent.fields.activite',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => ActiviteAgent::class,
                'choice_label' => 'nom' . ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom' . ucfirst($locale), 'ASC');
                }
            ])
            ->add('localisation', SelectOrCreateType::class, [
                'label'                    => 'generic.fields.localisation',
                'required'                 => false,
                'locale'                   => $options['locale'],
                'translations'             => $options['translations'],
                'field_name'               => 'localisation',
                'object_class'             => Localisation::class,
                'creation_form_class'      => LocalisationType::class,
                'creation_form_css_class'  => 'localisation_form',
                'selection_form_css_class' => 'localisation_selection',
                'selection_choice_label'   => 'affichage' . ucfirst($locale),
                'allow_none'               => true,
                'formAction'               => $options['formAction'],
                'isClone'                  => $options['isClone'],
                'selection_query_builder'  => function (EntityRepository $er) use ($locale) {
                    $nameField = 'nom' . ucfirst($locale);
                    $qb = $er->createQueryBuilder('e');
                    return $qb
                        ->leftJoin('e.grandeRegion', 'gr')
                        ->leftJoin('e.sousRegion', 'sr')
                        ->addOrderBy("unaccent(gr.$nameField)", 'ASC')
                        ->addOrderBy("unaccent(sr.$nameField)", 'ASC')
                        ->addOrderBy("e.nomVille", 'ASC')
                        ->addOrderBy("e.nomSite", 'ASC')
                        ->addOrderBy("e.id", 'ASC');
                }
            ])
            ->add('commentaireFr', Type\QuillType::class, array(
                'attr'        => ['class' => 'wysiwyg-editor', 'rows' => 2],
                'label'       => 'generic.fields.commentaire_fr',
                'required'    => false
            ))
            ->add('commentaireEn', Type\QuillType::class, array(
                'attr'        => ['class' => 'wysiwyg-editor', 'rows' => 2],
                'label'       => 'generic.fields.commentaire_en',
                'required'    => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
        $resolver->setRequired('translations');
        $resolver->setRequired('formAction');
        $resolver->setDefined('locale');
        $resolver->setDefault('isClone', false);
    }
}
