<?php

namespace App\Controller\Admin;

use App\Entity\Attestation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AttestationTraductionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Attestation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['passage', 'extraitAvecRestitution', 'translitteration', 'fiabiliteAttestation', 'id', 'version', 'commentaireFr', 'commentaireEn'])
            ->setPaginatorPageSize(30);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('source');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }

    public function configureFields(string $pageName): iterable
    {
        $traduireFr = Field::new('traduireFr');
        $traduireEn = Field::new('traduireEn');
        $id = IntegerField::new('id', 'ID');
        $source = AssociationField::new('source');
        $affichage = TextField::new('affichage');

        return [$source, $id, $affichage, $traduireFr, $traduireEn];
    }
}
