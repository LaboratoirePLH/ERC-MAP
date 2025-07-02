<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AuteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Auteur::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Auteur')
            ->setEntityLabelInPlural('Auteur')
            ->setSearchFields(['id', 'nomFr', 'nomEn'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        $nomFr = TextField::new('nomFr');
        $nomEn = TextField::new('nomEn');
        $titres = AssociationField::new('titres');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $nomFr, $nomEn, $titres];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $nomFr, $nomEn, $titres];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$nomFr, $nomEn, $titres];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$nomFr, $nomEn, $titres];
        }
        return [];
    }
}
