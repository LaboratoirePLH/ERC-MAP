<?php

namespace App\Controller\Admin;

use App\Entity\ActiviteAgent;
use App\Entity\Agent;
use App\Entity\Agentivite;
use App\Entity\Attestation;
use App\Entity\Auteur;
use App\Entity\CategorieElement;
use App\Entity\CategorieMateriau;
use App\Entity\CategorieMateriel;
use App\Entity\CategorieOccasion;
use App\Entity\CategorieSource;
use App\Entity\CategorieSupport;
use App\Entity\Chercheur;
use App\Entity\Element;
use App\Entity\EntitePolitique;
use App\Entity\EtatFiche;
use App\Entity\Formule;
use App\Entity\Genre;
use App\Entity\GenreElement;
use App\Entity\GrandeRegion;
use App\Entity\Langue;
use App\Entity\Localisation;
use App\Entity\Materiau;
use App\Entity\Materiel;
use App\Entity\Nature;
use App\Entity\NatureElement;
use App\Entity\NombreElement;
use App\Entity\Occasion;
use App\Entity\Pratique;
use App\Entity\Projet;
use App\Entity\QFonction;
use App\Entity\QTopographie;
use App\Entity\RequeteEnregistree;
use App\Entity\Source;
use App\Entity\SousRegion;
use App\Entity\StatutAffiche;
use App\Entity\Suivi;
use App\Entity\Titre;
use App\Entity\TraductionElement;
use App\Entity\TypeSource;
use App\Entity\TypeSupport;
use App\Entity\VerrouEntite;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    /**
     * @Route("/admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        return $this->redirect($this->adminUrlGenerator->setController(ChercheurCrudController::class)->generateUrl());
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ERC-MAP - Administration');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setDateFormat('dd/MM/yyyy')
            ->setDateTimeFormat('dd/MM/yyyy HH:mm:ss')
            ->setTimeFormat('HH:mm');
    }

    public function configureMenuItems(): iterable
    {

        yield MenuItem::section('Gestion', 'fas fa-folder-open');
        yield MenuItem::linkToCrud('Chercheur', 'fas fa-folder-open', Chercheur::class)
            ->setController(ChercheurCrudController::class);
        yield MenuItem::linkToCrud('Nouveaux utilisateurs', 'fas fa-folder-open', Chercheur::class)
            ->setController(NewAccountsCrudController::class);
        yield MenuItem::linkToCrud('Projet', 'fas fa-folder-open', Projet::class);
        yield MenuItem::linkToCrud('Suivi', 'fas fa-folder-open', Suivi::class);
        yield MenuItem::linkToCrud('Verrou Entité', 'fas fa-folder-open', VerrouEntite::class);
        yield MenuItem::linkToCrud('Requête Enregistrée', 'fas fa-folder-open', RequeteEnregistree::class);

        yield MenuItem::section('Source', 'fas fa-folder-open');
        yield MenuItem::linkToCrud('Sources (Traductions)', 'fas fa-folder-open', Source::class);
        yield MenuItem::linkToCrud('Auteur', 'fas fa-folder-open', Auteur::class);
        yield MenuItem::linkToCrud('Catégorie (Materiau)', 'fas fa-folder-open', CategorieMateriau::class);
        yield MenuItem::linkToCrud('Catégorie (Source)', 'fas fa-folder-open', CategorieSource::class);
        yield MenuItem::linkToCrud('Catégorie (Support)', 'fas fa-folder-open', CategorieSupport::class);
        yield MenuItem::linkToCrud('Langue', 'fas fa-folder-open', Langue::class);
        yield MenuItem::linkToCrud('Matériau', 'fas fa-folder-open', Materiau::class);
        yield MenuItem::linkToCrud('Titre', 'fas fa-folder-open', Titre::class);
        yield MenuItem::linkToCrud('Type (Source)', 'fas fa-folder-open', TypeSource::class);
        yield MenuItem::linkToCrud('Type (Support)', 'fas fa-folder-open', TypeSupport::class);

        yield MenuItem::section('Attestation', 'fas fa-folder-open');
        yield MenuItem::linkToCrud('Attestations (Etat Fiche)', 'fas fa-folder-open', Attestation::class)
            ->setController(AttestationEtatFicheCrudController::class);
        yield MenuItem::linkToCrud('Attestations (Traductions)', 'fas fa-folder-open', Attestation::class)
            ->setController(AttestationTraductionsCrudController::class);
        yield MenuItem::linkToCrud('Agent', 'fas fa-folder-open', Agent::class);
        yield MenuItem::linkToCrud('Catégorie (Materiel)', 'fas fa-folder-open', CategorieMateriel::class);
        yield MenuItem::linkToCrud('Catégorie (Occasion)', 'fas fa-folder-open', CategorieOccasion::class);
        yield MenuItem::linkToCrud('Etat Fiche', 'fas fa-folder-open', EtatFiche::class);
        yield MenuItem::linkToCrud('Formule', 'fas fa-folder-open', Formule::class);
        yield MenuItem::linkToCrud('Matériel', 'fas fa-folder-open', Materiel::class);
        yield MenuItem::linkToCrud('Occasion', 'fas fa-folder-open', Occasion::class);
        yield MenuItem::linkToCrud('Pratique', 'fas fa-folder-open', Pratique::class);

        yield MenuItem::section('Agent', 'fas fa-folder-open');
        yield MenuItem::linkToCrud('Activité (Agent)', 'fas fa-folder-open', ActiviteAgent::class);
        yield MenuItem::linkToCrud('Agentivité', 'fas fa-folder-open', Agentivite::class);
        yield MenuItem::linkToCrud('Genre', 'fas fa-folder-open', Genre::class);
        yield MenuItem::linkToCrud('Nature', 'fas fa-folder-open', Nature::class);
        yield MenuItem::linkToCrud('Statut Affiché', 'fas fa-folder-open', StatutAffiche::class);

        yield MenuItem::section('Elément', 'fas fa-folder-open');
        yield MenuItem::linkToCrud('Elements (Traductions)', 'fas fa-folder-open', Element::class);
        yield MenuItem::linkToCrud('Catégorie (Elément)', 'fas fa-folder-open', CategorieElement::class);
        yield MenuItem::linkToCrud('Genre (Elément)', 'fas fa-folder-open', GenreElement::class);
        yield MenuItem::linkToCrud('Nature (Element)', 'fas fa-folder-open', NatureElement::class);
        yield MenuItem::linkToCrud('Nombre (Element)', 'fas fa-folder-open', NombreElement::class);
        yield MenuItem::linkToCrud('Traduction (Elément)', 'fas fa-folder-open', TraductionElement::class);

        yield MenuItem::section('Localisation', 'fas fa-folder-open');
        yield MenuItem::linkToCrud('Localisation', 'fas fa-folder-open', Localisation::class);
        yield MenuItem::linkToCrud('Entité Politique', 'fas fa-folder-open', EntitePolitique::class);
        yield MenuItem::linkToCrud('Grande Région', 'fas fa-folder-open', GrandeRegion::class);
        yield MenuItem::linkToCrud('Qualité Fonctionnnelle', 'fas fa-folder-open', QFonction::class);
        yield MenuItem::linkToCrud('Qualité Topographique', 'fas fa-folder-open', QTopographie::class);
        yield MenuItem::linkToCrud('Sous Région', 'fas fa-folder-open', SousRegion::class);
    }
}
