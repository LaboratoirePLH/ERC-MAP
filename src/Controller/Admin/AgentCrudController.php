<?php

namespace App\Controller\Admin;

use App\Entity\Agent;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class AgentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Agent::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Agent')
            ->setEntityLabelInPlural('Agent')
            ->setSearchFields(['designation', 'id', 'commentaireFr', 'commentaireEn'])
            ->setPaginatorPageSize(30);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('attestation');
    }

    public function configureFields(string $pageName): iterable
    {
        $designation = TextareaField::new('designation');
        $estLocalisee = Field::new('estLocalisee');
        $commentaireFr = TextareaField::new('commentaireFr');
        $commentaireEn = TextareaField::new('commentaireEn');
        $attestation = AssociationField::new('attestation');
        $statutAffiches = AssociationField::new('statutAffiches');
        $natures = AssociationField::new('natures');
        $genres = AssociationField::new('genres');
        $activites = AssociationField::new('activites');
        $agentivites = AssociationField::new('agentivites');
        $localisation = AssociationField::new('localisation');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $estLocalisee, $attestation, $statutAffiches, $natures, $genres, $activites];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$designation, $id, $estLocalisee, $commentaireFr, $commentaireEn, $attestation, $statutAffiches, $natures, $genres, $activites, $agentivites, $localisation];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$designation, $estLocalisee, $commentaireFr, $commentaireEn, $attestation, $statutAffiches, $natures, $genres, $activites, $agentivites, $localisation];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$designation, $estLocalisee, $commentaireFr, $commentaireEn, $attestation, $statutAffiches, $natures, $genres, $activites, $agentivites, $localisation];
        }
        return [];
    }
}
