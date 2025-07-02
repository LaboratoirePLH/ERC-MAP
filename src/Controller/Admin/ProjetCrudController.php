<?php

namespace App\Controller\Admin;

use App\Entity\Projet;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProjetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Projet::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Projet')
            ->setEntityLabelInPlural('Projet')
            ->setSearchFields(['id', 'nomFr', 'nomEn'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        $nomFr = TextField::new('nomFr');
        $nomEn = TextField::new('nomEn');
        $chercheurs = AssociationField::new('chercheurs');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $nomFr, $nomEn, $chercheurs];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $nomFr, $nomEn, $chercheurs];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$nomFr, $nomEn, $chercheurs];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$nomFr, $nomEn, $chercheurs];
        }
        return [];
    }
}
