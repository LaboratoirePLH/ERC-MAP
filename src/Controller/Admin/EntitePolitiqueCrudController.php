<?php

namespace App\Controller\Admin;

use App\Entity\EntitePolitique;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EntitePolitiqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EntitePolitique::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['numeroIacp', 'id', 'nomFr', 'nomEn'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        $numeroIacp = IntegerField::new('numeroIacp');
        $nomFr = TextField::new('nomFr');
        $nomEn = TextField::new('nomEn');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$numeroIacp, $id, $nomFr, $nomEn];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$numeroIacp, $id, $nomFr, $nomEn];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$numeroIacp, $nomFr, $nomEn];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$numeroIacp, $nomFr, $nomEn];
        }
        return [];
    }
}
