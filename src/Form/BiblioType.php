<?php

namespace App\Form;

use App\Entity\Biblio;
use App\Entity\Corpus;
use App\Form\Type\SelectOrCreateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class BiblioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titreAbrege', TextType::class, [
                'label'    => 'biblio.fields.titre_abrege',
                'required' => false
            ])
            ->add('titreComplet', CKEditorType::class, array(
                'config_name' => 'text_styling_only',
                'label'       => 'biblio.fields.titre_complet',
                'required'    => false
            ))
            ->add('annee', IntegerType::class, [
                'label'    => 'biblio.fields.annee',
                'required' => false
            ])
            ->add('auteurBiblio', TextType::class, [
                'label'    => 'biblio.fields.auteur',
                'required' => false
            ])
            ->add('corpus', SelectOrCreateType::class, [
                'locale'                  => $options['locale'],
                'translations'            => $options['translations'],
                'field_name'              => 'corpus',
                'object_class'            => Corpus::class,
                'creation_form_class'     => CorpusType::class,
                'selection_choice_label'  => 'nom',
                'selection_query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Biblio::class,
        ]);
        $resolver->setRequired('translations');
        $resolver->setDefined('locale');
    }
}
