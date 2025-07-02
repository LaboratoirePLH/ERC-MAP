<?php

namespace App\Controller\Admin;

use App\Entity\Formule;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class FormuleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Formule::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Formule')
            ->setEntityLabelInPlural('Formule')
            ->setSearchFields(['formule', 'positionFormule', 'puissancesDivines', 'id'])
            ->setPaginatorPageSize(30);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('attestation');
    }

    public function configureFields(string $pageName): iterable
    {
        $formule = TextareaField::new('formule');
        $positionFormule = IntegerField::new('positionFormule');
        $puissancesDivines = IntegerField::new('puissancesDivines');
        $attestation = AssociationField::new('attestation');
        $createur = AssociationField::new('createur');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $formule, $attestation, $positionFormule, $puissancesDivines];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $formule, $attestation, $positionFormule, $puissancesDivines, $createur];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$formule, $attestation, $positionFormule, $puissancesDivines];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$formule, $attestation, $positionFormule, $puissancesDivines, $createur->setDisabled(true)];
        }
        return [];
    }

    public function createEntity(string $entityFqcn)
    {
        $formule = new Formule();
        $formule->setCreateur($this->getUser());

        return $formule;
    }
}
