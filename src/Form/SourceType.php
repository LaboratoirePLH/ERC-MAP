<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Langue;
use App\Entity\Materiau;
use App\Entity\Source;
use App\Entity\Titre;
use App\Entity\TypeSource;
use App\Entity\TypeSupport;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];

        $builder
            ->add('traduireFr', CheckboxType::class, [
                'label'      => 'source.fields.to_translate_fr',
                'required' => false
            ])
            ->add('traduireEn', CheckboxType::class, [
                'label'      => 'source.fields.to_translate_en',
                'required' => false
            ])
            ->add('citation', CheckboxType::class, [
                'label'      => 'source.fields.citation',
                'label_attr' => [
                    'class' => 'quote_field'
                ],
                'required' => false
            ])
            ->add('urlTexte', UrlType::class, [
                'label'    => 'source.fields.url_texte',
                'required' => false
            ])
            ->add('urlImage', UrlType::class, [
                'label'    => 'source.fields.url_image',
                'required' => false
            ])
            ->add('inSitu', CheckboxType::class, [
                'label'    => 'source.fields.in_situ',
                'required' => false
            ])
            ->add('commentaireSourceFr', TextareaType::class, [
                'label'    => 'generic.fields.commentaire_fr',
                'required' => false
            ])
            ->add('commentaireSourceEn', TextareaType::class, [
                'label'    => 'generic.fields.commentaire_en',
                'required' => false
            ])
            ->add('typeSource', EntityType::class, [
                'label'        => 'source.fields.type_source',
                'required'     => false,
                'class'        => TypeSource::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'group_by'      => 'categorieSource.nom'.ucfirst($locale),
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->leftJoin('e.categorieSource', 'c')
                        ->addSelect('c')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('materiau', EntityType::class, [
                'label'        => 'source.fields.materiau',
                'required'     => false,
                'class'        => Materiau::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'group_by'     => 'categorieMateriau.nom'.ucfirst($locale),
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->leftJoin('e.categorieMateriau', 'c')
                        ->addSelect('c')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('typeSupport', EntityType::class, [
                'label'        => 'source.fields.support',
                'required'     => false,
                'class'        => TypeSupport::class,
                'choice_label' => 'nom'.ucfirst($locale),
                'attr'         => [
                    'class' => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_element']
                ],
                'group_by'     => 'categorieSupport.nom'.ucfirst($locale),
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->leftJoin('e.categorieSupport', 'c')
                        ->addSelect('c')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
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
            ])
            ->add('langues', EntityType::class, [
                'label'        => 'source.fields.langues',
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Langue::class,
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
            ->add('titrePrincipal', TitreType::class, [
                'label'         => 'source.fields.titre_principal',
                'required'      => false,
                'mapped'        => true,
                'locale'        => $locale,
                'translations'  => $options['translations']
            ])
            ->add('titresCites', EntityType::class, [
                'label'      => 'source.fields.titres_cites',
                'label_attr' => [
                    'class' => 'quote_dependent'
                ],
                'required'     => false,
                'multiple'     => true,
                'expanded'     => false,
                'class'        => Titre::class,
                'choice_label' => 'affichage'.ucfirst($locale),
                'attr'         => [
                    'class'            => 'autocomplete',
                    'data-placeholder' => $options['translations']['autocomplete.select_multiple']
                ],
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nom'.ucfirst($locale), 'ASC');
                }
            ])
            ->add('datation', DatationType::class)
            ->add('sourceBiblios', CollectionType::class, [
                'label'         => false,
                'entry_type'    => SourceBiblioType::class,
                'entry_options' => array_intersect_key($options, array_flip(["translations","locale"])),
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
            ])
            ->add('save',      SubmitType::class, [
                'label' => 'generic.save'
            ])
        ;
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    public function onPreSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        $rawData = $data;
        foreach($rawData['sourceBiblios'] as $index => &$sourceBiblio){
            // On ignore la référence bibliographique si une de ces conditions est réunie :
            // - l'utilisateur n'a pas choisi entre une biblio existante ou une nouvelle
            // - l'utilisateur a choisi une biblio existante mais ne l'a pas sélectionnée
            // - l'utilisateur a choisi de créer une biblio et :
            //     - il manque des données
            //     - il n'a pas choisi entre un corpus existant ou un nouveau
            //     - il a choisi un corpus existant mais ne l'a pas sélectionné
            //     - il a choisi de créer un corpus et il manque des données
            if(!array_key_exists('biblio_creation', $sourceBiblio)
                || $sourceBiblio['biblio_creation'] === "no" && empty($sourceBiblio['biblio'])
                || $sourceBiblio['biblio_creation'] === "yes" && (
                    !array_key_exists('biblioNew', $sourceBiblio)
                    || !array_key_exists('corpus_creation', $sourceBiblio['biblioNew'])
                    || (
                    $sourceBiblio['biblioNew']['corpus_creation'] === "no"
                    && empty($sourceBiblio['biblioNew']['corpus'])
                ) || (
                    $sourceBiblio['biblioNew']['corpus_creation'] === "yes"
                    && !array_key_exists('corpusNew', $sourceBiblio['biblioNew'])
                )
            )){
                unset($data['sourceBiblios'][$index]);
                continue;
            }

            // Si la référence a passé les tests, on regarde le choix de biblio
            // Si c'est une création, on vient écraser la clé "biblio" avec ce qui est dans "biblioNew"
            // Même logique si l'on crée un nouveau corpus
            // On retire ensuite du formulaire
            if($sourceBiblio['biblio_creation'] === "yes"){
                // $data['sourceBiblios'][$index]['biblio'] = $data['sourceBiblios'][$index]['biblioNew'];
                if($sourceBiblio['biblioNew']['corpus_creation'] === "yes"){
                    $data['sourceBiblios'][$index]['biblio']['corpus'] = $data['sourceBiblios'][$index]['biblio']['corpusNew'];
                }
                unset($data['sourceBiblios'][$index]['biblioNew']['corpusNew']);
                unset($data['sourceBiblios'][$index]['biblioNew']['corpus_creation']);
                unset($data['sourceBiblios'][$index]['biblio']);
            } else {
                unset($data['sourceBiblios'][$index]['biblioNew']);
            }
            unset($data['sourceBiblios'][$index]['biblio_creation']);
        }
        $event->setData($data);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Source::class,
        ]);
        $resolver->setRequired('locale');
        $resolver->setRequired('translations');
    }
}
