<?php

namespace App\Controller\Admin;

use App\Entity\Localisation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LocalisationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Localisation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Localisation')
            ->setEntityLabelInPlural('Localisation')
            ->setSearchFields(['pleiadesVille', 'nomVille', 'latitude', 'longitude', 'pleiadesSite', 'nomSite', 'geom', 'id', 'commentaireFr', 'commentaireEn'])
            ->setPaginatorPageSize(30);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('grandeRegion')
            ->add('sousRegion');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        $pleiadesVille = IntegerField::new('pleiadesVille');
        $nomVille = TextField::new('nomVille');
        $latitude = NumberField::new('latitude')->setNumDecimals(8);
        $longitude = NumberField::new('longitude')->setNumDecimals(8);
        $pleiadesSite = IntegerField::new('pleiadesSite');
        $nomSite = TextField::new('nomSite');
        $reel = BooleanField::new('reel');
        $geom = TextField::new('geom');
        $commentaireFr = TextareaField::new('commentaireFr');
        $commentaireEn = TextareaField::new('commentaireEn');
        $entitePolitique = AssociationField::new('entitePolitique');
        $grandeRegion = AssociationField::new('grandeRegion');
        $sousRegion = AssociationField::new('sousRegion');
        $topographies = AssociationField::new('topographies');
        $fonctions = AssociationField::new('fonctions');
        $id = IntegerField::new('id', 'ID');
        $grandeRegionNomFr = TextField::new('grandeRegion.nomFr');
        $sousRegionNomFr = TextField::new('sousRegion.nomFr');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $grandeRegionNomFr, $sousRegionNomFr, $pleiadesVille, $nomVille, $pleiadesSite, $nomSite];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$pleiadesVille, $nomVille, $latitude, $longitude, $pleiadesSite, $nomSite, $reel, $geom, $id, $commentaireFr, $commentaireEn, $entitePolitique, $grandeRegion, $sousRegion, $topographies, $fonctions];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$pleiadesVille, $nomVille, $latitude, $longitude, $pleiadesSite, $nomSite, $reel, $geom, $commentaireFr, $commentaireEn, $entitePolitique, $grandeRegion, $sousRegion, $topographies, $fonctions];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$reel, $entitePolitique, $grandeRegion, $sousRegion, $pleiadesVille, $nomVille, $pleiadesSite, $nomSite, $latitude, $longitude, $topographies, $fonctions, $commentaireFr, $commentaireEn];
        }
        return [];
    }
}
