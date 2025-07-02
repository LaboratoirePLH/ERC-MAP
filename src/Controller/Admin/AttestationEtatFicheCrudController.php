<?php

namespace App\Controller\Admin;

use App\Entity\Attestation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AttestationEtatFicheCrudController extends AbstractCrudController
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
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        $etatFiche = AssociationField::new('etatFiche');
        $id = IntegerField::new('id', 'ID Attestation');
        $source = AssociationField::new('source');
        $affichage = TextField::new('affichage');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$source, $id, $affichage, $etatFiche];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$source->setDisabled(true), $id->setDisabled(true), $affichage->setDisabled(true), $etatFiche];
        }
        return [];
    }
}
