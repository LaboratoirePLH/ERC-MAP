<?php
namespace App\Form\Type;

use App\Entity\Interfaces\CategorizedEntity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class DependentSelectType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['locale', 'translations', 'name', 'secondary_name', 'field_options', 'secondary_field_options', 'category_field']);
        $resolver->setDefault('locale', 'en');
    }

    public function getBlockPrefix(){
        return "dependentselect";
    }

    protected function addElements(FormInterface $form, $parentData = null) {
        $options = $form->getConfig()->getOptions();
        $form->add($options['name'], EntityType::class, $options['field_options']);

        $secondaryData = array();

        if ($parentData) {
            $secondaryRepo = $this->entityManager->getRepository($options['secondary_field_options']['class']);
            $secondaryData = $secondaryRepo->createQueryBuilder("e")
                ->where("e.".$options['category_field']." = :parentid")
                ->setParameter("parentid", $parentData->getId())
                ->getQuery()
                ->getResult();
        }

        $secondaryOptions = array_merge($options['secondary_field_options'], ['choices' => $secondaryData]);
        $form->add($options['secondary_name'], EntityType::class, $secondaryOptions);
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $options = $form->getConfig()->getOptions();
        $data = $event->getData();

        $parentRecord = null;
        if(!empty($data[$options['name']]))
        {
            $parentRecord = $this->entityManager
                                 ->getRepository($options['field_options']['class'])
                                 ->find($data[$options['name']]);
        }

        $this->addElements($form, $parentRecord);
    }

    function onPreSetData(FormEvent $event) {
        $form = $event->getForm();
        $options = $form->getConfig()->getOptions();
        $data = $event->getData();

        $parentRecord = $data[$options['name']] ?? null;

        $this->addElements($form, $parentRecord);
    }
}