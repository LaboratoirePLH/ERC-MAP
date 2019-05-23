<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; //Remplace le Response
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\NoFileException;

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

            $reqSaveCorps = 18; //Renvoie l'id de la requête
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

            //Traitement de nomBDD, pour cela corresponde au getNom($lang) dans le switch
            if (strcmp($nomBDD, "nom_fr") == 0) {
                $nomBDD = "fr";
            } elseif (strcmp($nomBDD, "nom_en") == 0) {
                $nomBDD = "en";
            }

            $nomTable = $request->request->get('nomTable');
            $nomTableClass = "App\\Entity\\" . $nomTable; //On le traduit en Symfony pour qu'il comprenne où aller

            //Requête QB
            $repo = $this->getDoctrine()->getManager()->getRepository($nomTableClass);
            $rows = $repo->createQueryBuilder("e")
                ->getQuery()
                ->getResult();

            return new JsonResponse($this->_traiterRetour($rows, $nomBDD));
        }
    }


    /**
     * Page queries
     *
     * @Route("/requetes/chargerReq", name="requetes_charger_requete", options={"expose"=true})
     */
    public function chargerRequete(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $idReq = $request->request->get('idReq');

            //Requête QB portant sur l'id
            $repo = $this->getDoctrine()->getManager()->getRepository(Requetes::class);
            $corpsReq = $repo->createQueryBuilder("e")
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
    public function executerRequete(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $typeDonnee = strtolower($request->request->get('typeDonnee')); //Je le formate pour que ça corresponde aux tables de la BDD
            $tabRequete = $request->request->get('tabRequete');
            //$tabAffiche = $request->request->get('tabAffiche');
            //$typeDonneeTable = "App\\Entity\\".$typeDonnee; //On le traduit en Symfony pour qu'il comprenne où aller

            $nbCriteres = sizeof($tabRequete);

            //Affectation du contenu de tabRequete         
            $tabTable = array();
            $tabNomBDD = array();
            $tabValue = array();
            $tabOperator = array();
            for ($i = 0; $i < $nbCriteres; $i++) {
                $tabTable[$i] = $tabRequete["where" . $i]["table"];
                $tabNomBDD[$i] = $tabRequete["where" . $i]["nomBDD"];
                $tabValue[$i] = $tabRequete["where" . $i]["value"];
                $tabOperator[$i] = $tabRequete["where" . $i]["operator"];
            }
            $tabTable = array_unique($tabTable, SORT_REGULAR); //J'enlève les doublons

            //Pour les jointures  
            $jointures = " WHERE 1=1 ";
            $jointures .= $this->_faireJointure($typeDonnee, $tabTable);

            //Pour le FROM WHERE
            $from = "";
            $from .= $this->_faireFrom($from, $tabTable, $typeDonnee);

            $condition = ""; //Where

            for ($i = 0; $i < sizeof($tabTable); $i++) {
                $condition .= "AND " . $tabTable[$i] . "." . $tabNomBDD[$i] . " ";
                $condition .= $this->_operatorValue($tabOperator[$i], $tabValue[$i]);
            }

            $sql = "SELECT attestation.id ";
            $sql .= $from;
            $sql .= $jointures;
            $sql .= $condition;

            //return new JsonResponse($sql);

            //Requête
            $conn = $this->getDoctrine()->getEntityManager()->getConnection();

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            //Traitement de la requête
            $tmp2 = [];
            $i = 0;
            while ($data = $stmt->fetch()) {
                $tmp2[$i]["id"] = $data["id"];
                $i++;
            }
            return new JsonResponse($tmp2);
        }
    }

    private function _faireFrom($from, $tabTable, $typeDonnee)
    {
        if (in_array($typeDonnee, $tabTable)) { //Si le type donnée est dans le tableau
            $from = "FROM "; //From
        } else { //S'il n'y est pas on l'ajoute pour pouvoir faire la requête
            $from = "FROM " . $typeDonnee . ", ";
        }

        for ($i = 0; $i < sizeof($tabTable); $i++) {
            switch($tabTable[$i]){
                case "categorie_materiel":
                    if(!in_array("materiel",$tabTable)){ //S'il n'est pas dans le tableau (Pour ne pas l'ajouter deux fois)
                        $from .= "materiel, ";
                        if(!in_array("attestation_materiel",$tabTable)){
                            $from .= "attestation_materiel, ";
                        }
                    }
                    break;
                
                case "materiel":
                    if(!in_array("attestation_materiel",$tabTable)){ //S'il n'est pas dans le tableau (Pour ne pas l'ajouter deux fois)
                        $from .= "attestation_materiel, ";
                    }
                    break;

                case "occasion":
                    if(!in_array("attestation_occasion",$tabTable)){ //S'il n'est pas dans le tableau (Pour ne pas l'ajouter deux fois)
                        $from .= "attestation_occasion, ";
                    }
                    break;

                case "categorie_occasion":
                    if(!in_array("occasion",$tabTable)){ //S'il n'est pas dans le tableau (Pour ne pas l'ajouter deux fois)
                        $from .= "occasion, ";
                        if(!in_array("attestation_occasion",$tabTable)){
                            $from .= "attestation_occasion, ";
                        }
                    }
                    break;
            }
            $from .= $tabTable[$i];
            if ($i + 1 != sizeof($tabTable)) { //Pour ne pas mettre la virgule au dernier
                $from .= ", ";
            }
        }
        return $from;
    }
    private function _faireJointure($typeDonnee, $tabTable)
    {
        $jointures = "";
        foreach ($tabTable as $table) {
            if ($table == "localisation") {
                if ($typeDonnee == "source") { //Si c'est sur la source, c'est un traitement différent

                } else { //Pour l'élément et l'attestation
                    $jointures .= "AND " . $typeDonnee . ".id_localisation = localisation.id ";
                }
            } else {
                switch ($typeDonnee) {
                    case "attestation":
                        switch ($table) {
                                //Les périphériques à Attestation
                            case "attestation": //Si c'est la même table
                                break;

                            case "etat_fiche":
                                $jointures .= "AND attestation.id_etat_fiche = etat_fiche.id ";
                                break;

                            case "categorie_occasion":
                                $tabFaireJointureTmp[0] = "occasion";
                                $jointures .= $this->_faireJointure($typeDonnee, $tabFaireJointureTmp); //J'appelle récursivement la fonction, pour qu'il me renvoir la jointure que je concatène
                                $jointures .= "AND occasion.categorie_occasion_id = categorie_occasion.id ";
                                break;

                            case "occasion":
                                if(!in_array("categorie_occasion",$tabTable)){ //Pour éviter de le mettre deux fois
                                    $jointures .= "AND attestation_occasion.id_attestation = attestation.id AND attestation_occasion.id_occasion = occasion.id ";
                                }
                                break;

                            case "categorie_materiel":
                                $tabFaireJointureTmp[0] = "materiel";
                                $jointures .= $this->_faireJointure($typeDonnee, $tabFaireJointureTmp);
                                $jointures .= "AND materiel.id = categorie_materiel.id ";
                                break;

                            case "materiel":
                                if(!in_array("categorie_materiel",$tabTable)){ //Pour éviter de le mettre deux fois
                                    $jointures .= "AND attestation_materiel.id_attestation = attestation.id AND attestation_materiel.id_materiel = materiel.id ";
                                }
                                break;

                            case "source":
                                $jointures .= "AND attestation.id_source = source.id ";
                                break;

                            case "agent":
                                $jointures .= "AND attestation.id = agent.id_attestation ";
                                break;

                            case "element":
                                $jointures .= "AND attestation.id = contient_element.id_attestation AND contient_element.id_element = element.id ";
                                break;

                            default:
                                throw new NoFileException('Champ non pris en compte');
                                break;
                        }
                        break;

                    case "source":
                        break;

                    case "element":
                        break;
                }
            }
        }
        return $jointures;
    }

    private function _operatorValue($op, $value)
    {
        $operator = "";
        switch ($op) {

            case "null":
                $operator = "IS NULL ";
                break;

                //String
            case "LIKE %var%":
                $operator = "LIKE '%" . $value . "%' ";
                break;

            case "NOT LIKE %var%":
                $operator = "NOT LIKE '%" . $value . "%' ";
                break;

            case "LIKE var%":
                $operator = "LIKE '" . $value . "%' ";
                break;

            case "LIKE %var":
                $operator = "LIKE '%" . $value . "' ";
                break;

            case "IS EMPTY":
                $operator = "IS NULL ";

                //Booleen
            case "IS TRUE":
                break;

            case "IS FALSE":
                $operator = "= 'f' ";
                break;

            case "IS NULL":
                $operator = "IS NULL ";
                break;

                //Liste
            case "LIKE":
                $operator = "LIKE '" . $value . "' ";
                break;

            case "NOT LIKE":
                $operator = "NOT LIKE '" . $value . "' ";
                break;

                //Number
            case ">":
                $operator = ">" . $value . " ";
                break;

            case "<":
                $operator = "<" . $value . " ";
                break;

            case "=":
                $operator = "=" . $value . " ";
                break;
        }
        return $operator;
    }

    private function _traiterRetour($rows, $nomBDD)
    { //Pour ceux à qui la méthode getNom() ne peut s'appliquer
        switch ($nomBDD) {
            case "nom_ville":
                foreach ($rows as $row) {
                    if ($row->getNomVille() == "null" || $row->getNomVille() == "") { //Pour virer ce qui n'est pas rempli/null

                    } else {
                        $responseArray[] = array(
                            "nom" => $row->getNomVille()
                        );
                    }
                }
                break;

            case "nom_site":
                foreach ($rows as $row) {
                    if ($row->getNomSite() == "null" || $row->getNomSite() == "") { //Pour virer ce qui n'est pas rempli/null

                    } else {
                        $responseArray[] = array(
                            "nom" => $row->getNomSite()
                        );
                    }
                }
                break;

            case "etat_morphologique":
                $responseArrayTmp = array();

                foreach ($rows as $row) { //Pour chaque ContientElement
                    $etatMorpho = $row->getEtatMorphologique();
                    if ($etatMorpho != null) { //S'il existe
                        array_push($responseArrayTmp, $etatMorpho);
                    }
                }
                foreach ($responseArrayTmp as $row) { //Formattage du tableau pour qu'il corresponde au traitement en JS
                    $responseArray[] = array(
                        "nom" => $row
                    );
                }
                break;

            case "theonymes_implicites":
                $responseArrayTmp = array();
                foreach ($rows as $row) { //Pour chaque élément
                    $elem = $row->getTheonymesImplicites(); //Je get le théonyme d'un des élement

                    if (!is_null($elem[0])) //Si l'élément a des théonymes
                    {
                        $tabEtatAbsolu = array();
                        $i = 0;

                        foreach ($elem as $theo) { //Pour chaque théonyme contenu dans l'élement courrant
                            $tabEtatAbsolu[$i] = strip_tags($theo->getEtatAbsolu()); //On le récupère en enlevant les balises
                            $i++;
                        }
                        $responseArrayTmp = array_merge($responseArrayTmp, $tabEtatAbsolu); //Fusion des deux tableaux
                    }
                    $responseArrayTmp = array_unique($responseArrayTmp, SORT_REGULAR); //Suppression des doublons
                }
                foreach ($responseArrayTmp as $row) { //Formattage du tableau pour qu'il corresponde au traitement en JS
                    $responseArray[] = array(
                        "nom" => $row
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
}
