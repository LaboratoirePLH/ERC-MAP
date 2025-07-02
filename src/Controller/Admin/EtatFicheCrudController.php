<?php

namespace App\Controller\Admin;

use App\Entity\EtatFiche;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EtatFicheCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EtatFiche::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'nomFr', 'nomEn'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IntegerField::new('id', 'ID');
        $nomFr = TextField::new('nomFr');
        $nomEn = TextField::new('nomEn');
        $openAccess = BooleanField::new('open_access', 'Inclure en Open Access ?');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $nomFr, $nomEn, $openAccess];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$openAccess, $id, $nomFr, $nomEn];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$openAccess, $nomFr, $nomEn];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$openAccess, $nomFr, $nomEn];
        }
        return [];
    }
}
