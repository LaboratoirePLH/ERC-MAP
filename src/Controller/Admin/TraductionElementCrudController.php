<?php

namespace App\Controller\Admin;

use App\Entity\TraductionElement;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TraductionElementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TraductionElement::class;
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
        $element = AssociationField::new('element');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $nomFr, $nomEn, $element];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $nomFr, $nomEn, $element];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$nomFr, $nomEn, $element];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$nomFr, $nomEn, $element];
        }
        return [];
    }
}
