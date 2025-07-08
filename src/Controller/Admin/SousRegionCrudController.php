<?php

namespace App\Controller\Admin;

use App\Entity\SousRegion;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SousRegionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SousRegion::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['progression', 'id', 'nomFr', 'nomEn'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        $progression = IntegerField::new('progression');
        $nomFr = TextField::new('nomFr');
        $nomEn = TextField::new('nomEn');
        $grandeRegion = AssociationField::new('grandeRegion');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $grandeRegion, $nomFr, $nomEn, $progression];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$progression, $id, $nomFr, $nomEn, $grandeRegion];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$progression, $nomFr, $nomEn, $grandeRegion];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$progression, $nomFr, $nomEn, $grandeRegion];
        }
        return [];
    }
}
