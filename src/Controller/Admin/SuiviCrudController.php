<?php

namespace App\Controller\Admin;

use App\Entity\Suivi;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SuiviCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Suivi::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Suivi')
            ->setEntityLabelInPlural('Suivi')
            ->setSearchFields(['nom_table', 'id_entite', 'action', 'old_data', 'new_data', 'detail', 'id'])
            ->setPaginatorPageSize(30);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IntegerField::new('id', 'ID');
        $nomTable = TextField::new('nom_table', 'Type de fiche');
        $idEntite = IntegerField::new('id_entite', 'ID de la fiche');
        $dateHeure = DateTimeField::new('date_heure', 'Date et heure');
        $action = TextField::new('action', 'Action');
        $oldData = TextareaField::new('old_data', 'Anciennes données');
        $newData = TextareaField::new('new_data', 'Nouvelles données');
        $detail = TextareaField::new('detail', 'Détails');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $nomTable, $idEntite, $action, $dateHeure];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $nomTable, $idEntite, $dateHeure, $action, $oldData, $newData, $detail];
        }
        return [];
    }
}
