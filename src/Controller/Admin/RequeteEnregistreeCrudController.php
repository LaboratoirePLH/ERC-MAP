<?php

namespace App\Controller\Admin;

use App\Entity\RequeteEnregistree;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RequeteEnregistreeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RequeteEnregistree::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['query', 'id', 'nomFr', 'nomEn', 'commentaireFr', 'commentaireEn'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        $query = TextareaField::new('query');
        $nomFr = TextField::new('nomFr');
        $nomEn = TextField::new('nomEn');
        $commentaireFr = TextareaField::new('commentaireFr');
        $commentaireEn = TextareaField::new('commentaireEn');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $nomFr, $nomEn];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$query, $id, $nomFr, $nomEn, $commentaireFr, $commentaireEn];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$query, $nomFr, $nomEn, $commentaireFr, $commentaireEn];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$query, $nomFr, $nomEn, $commentaireFr, $commentaireEn];
        }
        return [];
    }
}
