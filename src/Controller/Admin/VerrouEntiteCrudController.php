<?php

namespace App\Controller\Admin;

use App\Entity\VerrouEntite;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class VerrouEntiteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VerrouEntite::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        $dateFin = DateTimeField::new('date_fin');
        $sources = AssociationField::new('sources');
        $elements = AssociationField::new('elements');
        $biblios = AssociationField::new('biblios');
        $createur = AssociationField::new('createur');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$dateFin, $id, $sources, $elements, $biblios, $createur];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$dateFin, $id, $sources, $elements, $biblios, $createur];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$dateFin, $sources, $elements, $biblios, $createur];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$dateFin, $sources, $elements, $biblios, $createur];
        }
        return [];
    }
}
