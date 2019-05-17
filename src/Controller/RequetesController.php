<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; //Remplace le Response
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


use App\Entity \ {
    Chercheur,
    Requetes,
    RequetesFormulaire,
    EtatFiche,
    Attestation,
    CategorieOccasion,
    Occasion,
    CategorieMateriel,
    Materiel,
    Nature,
    Genre,
    StatutAffiche,
    ActiviteAgent,
    Localisation,
    EntitePolitique,
    GrandeRegion,
    SousRegion,
    QTopographie,
    QFonction,
    ContientElement,
    GenreElement,
    NombreElement,
    CategorieSource,
    TypeSource,
    Langue,
    Titre,
    Auteur,
    CategorieMateriau,
    Materiau,
    TypeSupport,
    CategorieSupport
};
use Symfony\Component\Form\Extension\Core\Type \ {
    TextType,
    ButtonType,
    EmailType,
    HiddenType,
    PasswordType,
    TextareaType,
    SubmitType,
    NumberType,
    DateType,
    MoneyType,
    BirthdayType
};

class RequetesController extends AbstractController
{

    /**
     * Page queries
     *
     * @Route("/requetes", name="requetes_list")
     */
    public function index(Request $request)
    {
        //User
        $user = (string)$this->get('security.token_storage')->getToken()->getUser(); //On récupère le nom + prénom de l'user en cours
        $idChercheur = $this->getDoctrine()->getRepository(Chercheur::class)->findOneBy(['prenomNom' => $user]); //On récupère son id
        $reqSaveCorps = "";

        //Formulaire hidden (Pour l'enregistrement de requêtes)
        $reqSaveForm = new RequetesFormulaire();
        $reqSaveForm->setLib("Lib");
        $reqSaveForm->setCorps("Corps");
        $form = $this->createFormBuilder($reqSaveForm, ['attr' => ['id' => 'formEnrReq']])
            ->add('Lib', HiddenType::class)
            ->add('Corps', HiddenType::class)
            ->getForm();

        //Traitement formulaire
        $form->handleRequest($request);

        //Enregistrer une requête
        if ($form->isSubmitted() && $form->isValid()) {
            //Doctrine
            $reqForm = $form->getData();
            $reqSave = new Requetes();
            $reqSave->setReqLib($reqForm->getLib());
            $reqSaveCorps = $reqForm->getCorps();
            $reqSave->setReqCorps($reqSaveCorps);
            $reqSave->setIdChercheur($idChercheur); //Il faut lui mettre un objet chercheur que tu récupères avec une requete simple
            $em = $this->getDoctrine()->getManager();
            $em->persist($reqSave);
            $em->flush();

            $reqSaveCorps = json_encode($reqSaveCorps, JSON_HEX_APOS); //Encodage en json car sinon je ne peux pas envoyer de variable twig à la vue
            //Il faut laisser le APOS, car ça encode les apostrophes. Si on l'enlève ça casse tout
        }

        return $this->render('requetes/index.html.twig', [
            'controller_name' => 'RequetesController',
            'reqSaveCorps' => $reqSaveCorps,
            'formEnrReq' => $form->createView(),
            'breadcrumbs'   => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'requetes.list']
            ]
        ]);
    }

    /**
     * Page queries
     *
     * @Route("/requetes/form", name="requetes_list_comboBox", options={"expose"=true})
     */
    public function comboBox(Request $request) //Fonction pour les listes dynamiques
    {
        if ($request->isXmlHttpRequest()) {
            $nomBDD = $request->request->get('nomBDD');
            $nomTable = $request->request->get('nomTable');
            $nomTableClass = $this->_stringToClass($nomTable);

            //Requête DQL
            $repo = $this->getDoctrine()->getManager()->getRepository($nomTableClass);
            $rows = $repo->createQueryBuilder("e")
                ->getQuery()
                ->getResult();
            $responseArray = array();
            
            return new JsonResponse($this->_traiterRetour($rows,$responseArray,$nomBDD));
        }
    }

    
    /**
     * Page queries
     *
     * @Route("/requetes/chargerReq", name="requetes_charger_requete", options={"expose"=true})
     */
    public function chargerRequete(Request $request){
        if ($request->isXmlHttpRequest()) {
            $idReq = $request->request->get('idReq');

            //Requête DQL portant sur l'id
            $repo = $this->getDoctrine()->getManager()->getRepository(Requetes::class);
            $corpsReq = $repo->createQueryBuilder("e")
                ->from('')
                ->where("e.id = ?1")
                ->setParameter(1, $idReq)
                ->getQuery()
                ->getResult();

                $corpsReq = $corpsReq[0]->getReqCorps();
                return new JsonResponse($corpsReq);
        }
    }

    /**
     * Page queries
     *
     * @Route("/requetes/executerReq", name="requetes_executer", options={"expose"=true})
     */
    public function executerRequete(Request $request){
        if ($request->isXmlHttpRequest()) {
            $typeDonnee = $request->request->get('typeDonnee');
            $tabRequete = $request->request->get('tabRequete');
            $tabAffiche = $request->request->get('tabAffiche');
            $tmp="oui";
            return new JsonResponse($tmp);
        }
    }

    private function _traiterRetour($rows, $reponseArray,$nomBDD){//Pour ceux à qui la méthode getNom() ne peut s'appliquer
        switch($nomBDD){
            case "nomVille":
                foreach ($rows as $row) {
                    if($row->getNomVille() == "null" || $row->getNomVille() == ""){ //Pour virer ce qui n'est pas rempli/null

                    }
                    else{
                        $responseArray[] = array(
                            "nom" => $row->getNomVille()
                        );
                    }   
                }
                break;
              
            case "nomSite":
                foreach ($rows as $row) {
                    if($row->getNomSite() == "null" || $row->getNomSite() == ""){ //Pour virer ce qui n'est pas rempli/null

                    }
                    else{
                        $responseArray[] = array(
                            "nom" => $row->getNomSite()
                        );
                    }
                }
                break;
            
            case "etatMorphologique":
                foreach ($rows as $row) {
                        $responseArray[] = array(
                            "nom" => $row->getEtatMorphologique()
                        );
                    }
                break;

            default:
                foreach ($rows as $row) {
                    $responseArray[] = array(
                        "nom" => $row->getNom($nomBDD)
                    );
                }
        }
        return $responseArray;
    }


    private function _stringToClass($var)
    {
        switch ($var) {
            case "etat_fiche":
                $tmp = EtatFiche::class;
                break;

            case "attestation":
                $tmp = Attestation::class;
                break;

            case "categorie_occasion":
                $tmp = CategorieOccasion::class;
                break;

            case "occasion":
                $tmp = Occasion::class;
                break;

            case "categorie_materiel":
                $tmp = CategorieMateriel::class;
                break;

            case "materiel":
                $tmp = Materiel::class;
                break;

            case "nature":
                $tmp = Nature::class;
                break;

            case "genre":
                $tmp = Genre::Class;
                break;

            case "statut_affiche":
                $tmp = StatutAffiche::class;
                break;

            case "activite_agent":
                $tmp = ActiviteAgent::Class;
                break;

            case "localisation":
                $tmp = Localisation::class;
                break;

            case "entite_politique":
                $tmp = EntitePolitique::class;
                break;

            case "grande_region":
                $tmp = GrandeRegion::class;
                break;

            case "sous_region":
                $tmp = SousRegion::class;
                break;

            case "q_topographie":
                $tmp = QTopographie::class;
                break;

            case "q_fonction":
                $tmp = QFonction::class;
                break;

            case "contient_element":
                $tmp = ContientElement::class;
                break;

            case "genre_element":
                $tmp = GenreElement::class;
                break;

            case "nombre_element":
                $tmp = NombreElement::class;
                break;

            case "categorie_source":
                $tmp = CategorieSource::class;
                break;

            case "type_source":
                $tmp = TypeSource::class;
                break;

            case "langue":
                $tmp = Langue::class;
                break;

            case "titre":
                $tmp = Titre::class;
                break;

            case "auteur":
                $tmp = Auteur::class;
                break;

            case "categorie_materiau":
                $tmp = CategorieMateriau::class;
                break;

            case "materiau":
                $tmp = Materiau::class;
                break;

            case "type_support":
                $tmp = TypeSupport::class;
                break;

            case "categorie_support":
                $tmp = CategorieSupport::class;
                break;
        }
        return $tmp;
    }
}