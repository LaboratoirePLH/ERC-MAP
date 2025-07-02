<?php

namespace App\Controller\Admin;

use App\Entity\TypeSupport;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TypeSupportCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeSupport::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'nomFr', 'nomEn'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        $nomFr = TextField::new('nomFr');
        $nomEn = TextField::new('nomEn');
        $categorieSupport = AssociationField::new('categorieSupport');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $nomFr, $nomEn, $categorieSupport];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $nomFr, $nomEn, $categorieSupport];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$nomFr, $nomEn, $categorieSupport];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$nomFr, $nomEn, $categorieSupport];
        }
        return [];
    }
}
