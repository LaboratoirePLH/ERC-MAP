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
            //Cas particuluier : pour categorie_element
            if($nomTable == "Element" && ($nomBDD == "fr" || $nomBDD == "en")){
                $nomTable = "CategorieElement";
            }

            $nomTableClass = "App\\Entity\\" . $nomTable; //On le traduit en Symfony pour qu'il comprenne où aller

            //Requête QB
            $repo = $this->getDoctrine()->getManager()->getRepository($nomTableClass);
            $rows = $repo->createQueryBuilder("e")
                ->getQuery()
                ->getResult();

            return new JsonResponse($this->_traiterRetour($rows, $nomBDD, $nomTable));
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
            $tabAffiche = $request->request->get('tabAffiche');

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

            $tabTableAffiche = array(); //Le tableau qui va prendre les tables requises pour le SELECT
            $tabOrdreAffiche = array(); //Pour l'ordre d'affichage des select
            for ($i = 0; $i < sizeof($tabAffiche); $i++) {
                $tabTableAffiche[$i] = $tabAffiche["select" . $i]["table"];
                $ordreTmp = $tabAffiche["select" . $i]["ordre"] -1;
                $tabOrdreAffiche[$ordreTmp] = $tabAffiche["select" . $i];
            }

            //Pour le FROM WHERE
            $condition = ""; //Where

            for ($i = 0; $i < sizeof($tabTable); $i++) {
                $condition .= "AND " . $tabTable[$i] . "." . $tabNomBDD[$i] . " ";
                $condition .= $this->_operatorValue($tabOperator[$i], $tabValue[$i]);
            }

            //Je suis obligé de mettre ça ici car si je le met avant, il ne prend pas les conditions sur une même table à cause du array_unique. Ex : Element qui a un nom qui commence par x, et element qui a un nom qui finit par y
            $tabTable = array_merge($tabTable, $tabTableAffiche); //J'ajoute les tables du SELECT pour les jointures et le FROM
            $tabTable = array_values(array_unique($tabTable, SORT_REGULAR)); //J'enlève les doublons, le array_values est là pour éviter les erreurs d'index dûes au array_unique

            $from = "";
            $from .= $this->_faireFrom($from, $tabTable, $typeDonnee);

            //Pour les jointures  
            $jointures = " WHERE 1=1 ";
            $jointures .= $this->_faireJointure($typeDonnee, $tabTable);

            //Pour le select;
            $select = "SELECT ";
            $select .= $this->_faireSelect($tabOrdreAffiche);

            $sql = "";
            $sql .= $select;
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
                for ($j = 0; $j < sizeof($tabAffiche); $j++) { //Boucle pour récupérer tous les champs SELECT
                    $tmp2[$i]["select".$j] = strip_tags($data["select".$j]); //Affectation des champs à une variable
                }
                $i++;
            }
            return new JsonResponse($tmp2);
        }
    }

    private function _faireSelect($tab)
    {
        $select = "";
        for ($i = 0; $i < sizeof($tab); $i++) {
            $select .= $tab[$i]["table"] . "." . $tab[$i]["nomBDD"] . " as select" . $i; //Ex : "attestation.id as 0"
            if ($i + 1 != sizeof($tab)) { //Pour ne pas mettre la virgule au dernier
                $select .= ", ";
            } else {
                $select .= " "; //J'ajoute un espace pour le FROM après
            }
        }
        return $select;
    }

    private function _faireFrom($from, $tabTable, $typeDonnee)
    {
        if (in_array($typeDonnee, $tabTable)) { //Si le type donnée est dans le tableau
            $from = "FROM "; //From
        } else { //S'il n'y est pas on l'ajoute pour pouvoir faire la requête
            $from = "FROM " . $typeDonnee . ", ";
        }

        for ($i = 0; $i < sizeof($tabTable); $i++) {
            switch ($tabTable[$i]) {
                case "categorie_materiel":
                    if (!in_array("materiel", $tabTable)) { //S'il n'est pas dans le tableau (Pour ne pas l'ajouter deux fois)
                        $from .= "materiel, ";
                        if (!in_array("attestation_materiel", $tabTable)) {
                            $from .= "attestation_materiel, ";
                        }
                    }
                    break;

                case "materiel":
                    if (!in_array("attestation_materiel", $tabTable)) { //S'il n'est pas dans le tableau (Pour ne pas l'ajouter deux fois)
                        $from .= "attestation_materiel, ";
                    }
                    break;

                case "occasion":
                    if (!in_array("attestation_occasion", $tabTable)) { //S'il n'est pas dans le tableau (Pour ne pas l'ajouter deux fois)
                        $from .= "attestation_occasion, ";
                    }
                    break;

                case "categorie_occasion":
                    if (!in_array("occasion", $tabTable)) { //S'il n'est pas dans le tableau (Pour ne pas l'ajouter deux fois)
                        $from .= "occasion, ";
                        if (!in_array("attestation_occasion", $tabTable)) {
                            $from .= "attestation_occasion, ";
                        }
                    }
                    break;

                    //Pour tous les périphériques de Localisation
                case "sous_region":
                case "grande_region":
                case "entite_politique":
                    if (!in_array(array("localisation"), $tabTable)) {
                        $from .= "localisation, ";
                    }
                    break;
                case "q_topographie":
                case "q_fonction":
                    if (!in_array("localisation", $tabTable)) {
                        $from .= "localisation_" . $tabTable[$i] . ", "; //La table intermédiaire (Ex : localisation_q_topographie)
                        $from .= "localisation, ";
                    }
                    break;

                case "nature":
                case "genre":
                case "agentivite":
                    if (!in_array("agent", $tabTable)) { //S'il n'y a pas agent on l'ajoute
                        $from .= "agent_" . $tabTable[$i] . ", "; //La table intermédiaire (Ex: agent_genre)
                        $from .= "agent, ";
                    }
                    break;

                case "statut_affiche":
                    $from .= "agent_statut, "; //La table intermédiaire (Ex: agent_genre)
                    $from .= "agent, ";
                    break;

                    //Element
                case "genre_element":
                case "nombre_element":
                    if (!in_array("contient_element", $tabTable)) {
                        $from .= "contient_element, ";
                    }
                    if ($typeDonnee == "attestation") { //Quand je veux passer de attestation à un périphérique de element
                        if (!in_array("element", $tabTable)) {
                            $from .= "element, ";
                        }
                    }
                    break;

                case "categorie_element":
                    if (!in_array("element_categorie", $tabTable)) {
                        $from .= "element_categorie, ";
                    }
                    if ($typeDonnee == "attestation") { //Quand je veux passer de attestation à un périphérique de element
                        if (!in_array("element", $tabTable)) {
                            $from .= "element, ";
                        }
                    }
                    break;

                case "contient_element":
                    if ($typeDonnee == "attestation") { //Quand je veux passer de attestation à un périphérique de element
                        if (!in_array("element", $tabTable)) {
                            $from .= "element, ";
                        }
                    }
                    break;

                case "element_biblio":
                    if ($typeDonnee == "attestation") { //Quand je veux passer de attestation à un périphérique de element
                        if (!in_array("element", $tabTable)) {
                            $from .= "element, ";
                        }
                        if (!in_array("contient_element", $tabTable)) { //S'il il n'y a pas contient_element, car sinon il n'apparaît pas dans le from
                            $from .= "contient_element, ";
                        }
                    }
                    break;

                case "element":
                    if ($typeDonnee == "attestation") { //Quand je veux passer de attestation à element
                        $from .= "contient_element, ";
                    }
                    break;

                case "pratique":
                    if (!in_array("attestation_pratique", $tabTable)) {
                        $from .= "attestation_pratique, ";
                    }
                    break;
            } //End switch

            $from .= $tabTable[$i];
            if ($i + 1 != sizeof($tabTable)) { //Pour ne pas mettre la virgule au dernier
                $from .= ", ";
            }
        }
        return $from;
    }
    private function _faireJointure($typeDonnee, $tabTable)
    {
        $tabElement = array("theonymes_implicites", "contient_element", "categorie_element", "contient_element", "genre_element", "nombre_element", "element_biblio");
        $tabAttestation = array("etat_fiche", "categorie_occasion", "occasion", "categorie_materiel", "materiel", "agent", "nature", "genre", "agentivite", "statut_affiche");
        //$tabSource: Mettre tout ce qui est en rapport avec la source
        //$tabAttestation :
        $jointures = "";
        foreach ($tabTable as $table) {
            switch ($table) { //Tout ce qui est générique

                case "localisation": //Pour la table localisation
                    if (!in_array(array("entite_politique", "grande_region", "sous_region", "q_topographie", "q_fonction"), $tabTable)) { //S'il y a un champ qui demande obligatoirement une jointure à localisation, alors on ne fait rien
                        if ($typeDonnee == "source") { //Si c'est sur la source, c'est un traitement différent
                            $jointures .= "AND source.localisation_origine_id = localisation.id ";
                        } else { //Pour l'élément et l'attestation
                            $jointures .= "AND " . $typeDonnee . ".localisation_id = localisation.id ";
                        }
                    }
                    break;

                case "entite_politique":
                    $tabFaireJointureTmp[0] = "localisation";
                    $jointures .= $this->_faireJointure($typeDonnee, $tabFaireJointureTmp);
                    $jointures .= "AND localisation.entite_politique = entite_politique.id ";
                    break;

                case "grande_region":
                case "sous_region":
                    $tabFaireJointureTmp[0] = "localisation";
                    $jointures .= $this->_faireJointure($typeDonnee, $tabFaireJointureTmp);
                    $jointures .= "AND localisation." . $table . "_id = " . $table . ".id "; //AND localisation.grnade_region_id = grande_region.id
                    break;

                case "q_topographie":
                case "q_fonction":
                    $tabFaireJointureTmp[0] = "localisation";
                    $jointures .= $this->_faireJointure($typeDonnee, $tabFaireJointureTmp);
                    $jointures .= "AND localisation.id = localisation_" . $table . ".id_localisation AND localisation_" . $table . ".id_" . $table . " = " . $table . ".id "; // Ex : AND localisation.id = localisation_q_topographie.id_localisation AND localisation_q_topographie.id_q_topographie = q_topographie.id
                    break;

                case "datation": //Pour la datation : attestation et source
                    $jointures .= "AND " . $typeDonnee . ".datation_id = datation.id "; //AND source.datation_id = datation.id
                    break;

                default:
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
                                    if (!in_array("categorie_occasion", $tabTable)) { //Pour éviter de le mettre deux fois
                                        $jointures .= "AND attestation_occasion.id_attestation = attestation.id AND attestation_occasion.id_occasion = occasion.id ";
                                    }
                                    break;

                                case "categorie_materiel":
                                    $tabFaireJointureTmp[0] = "materiel";
                                    $jointures .= $this->_faireJointure($typeDonnee, $tabFaireJointureTmp);
                                    $jointures .= "AND materiel.categorie_materiel_id = categorie_materiel.id ";
                                    break;

                                case "materiel":
                                    if (!in_array("categorie_materiel", $tabTable)) { //Pour éviter de le mettre deux fois
                                        $jointures .= "AND attestation_materiel.id_attestation = attestation.id AND attestation_materiel.id_materiel = materiel.id ";
                                    }
                                    break;

                                    //Agent et ses périphériques
                                case "agent":
                                    if (!in_array(array("agentivite", "nature", "genre", "statut_affiche"), $tabTable)) {
                                        $jointures .= "AND attestation.id = agent.id_attestation ";
                                    }
                                    break;

                                case "nature":
                                case "genre":
                                case "agentivite":
                                    $tabFaireJointureTmp[0] = "agent";
                                    $jointures .= $this->_faireJointure($typeDonnee, $tabFaireJointureTmp);
                                    $jointures .= "AND agent.id = agent_" . $table . ".id_agent AND agent_" . $table . ".id_" . $table . " = " . $table . ".id "; //AND agent.id = agent_agentivite.id_agent AND agent_agentivite.id_agentivite = agentivite.id
                                    break;

                                case "statut_affiche":
                                    $tabFaireJointureTmp[0] = "agent";
                                    $jointures .= $this->_faireJointure($typeDonnee, $tabFaireJointureTmp);
                                    $jointures .= "AND agent.id = agent_statut.id_agent AND agent_statut.id_statut = " . $table . ".id ";
                                    break;

                                case "pratique":
                                    if (!in_array("attestation_pratique", $tabTable)) {
                                        $jointures .= "AND attestation.id = attestation_pratique.id_attestation AND attestation_pratique.id_pratique = pratique.id ";
                                    }
                                    break;

                                case "source":
                                    $jointures .= "AND attestation.id_source = source.id ";
                                    break;

                                case "element":
                                    $jointures .= "AND contient_element.id_element = element.id AND contient_element.id_attestation = attestation.id ";
                                    break;

                                default:
                                    if (in_array($table, $tabElement)) {
                                        $jointures .= "AND contient_element.id_element = element.id AND contient_element.id_attestation = attestation.id "; //Je fais la liaison entre attestation et element
                                        $tmpTypeDonnee = "element";
                                    }
                                    /* elseif(in_array($table,$tabSource)){
                                        $tmpTypeDonnee = "source";
                                    }*/ else {
                                        throw new NoFileException('Champ non pris en compte');
                                    }
                                    $tabFaireJointureTmp[0] = array($table);
                                    $jointures .= $this->_faireJointure($tmpTypeDonnee, $tabFaireJointureTmp[0]); //En sachant qu'il faut savoir quel type donnée correspond à quoi*/
                                    break;
                            }
                            break;

                        case "source":
                            break;

                        case "element":
                            switch ($table) {
                                case "element":
                                    break;

                                case "theonymes_implicites":
                                    // SELECT * FROM element e1, element e2, theonymes_implicites t WHERE e1.id = t.id_parent AND e2.id = t.id_enfant 	and e2.etat_absolu like '<div>eeeeezrzefššsd</div>'
                                    //Penser à le rajouter dans le $from avec le  if($typeDonnee == "attestation"){ if(!in_array("element",$tabTable)){ $from .= "element, ";   } break;

                                case "contient_element":
                                    if (!in_array(array("nombre_element", "genre_element"), $tabTable)) { //Pour éviter de le mettre deux fois
                                        $jointures .= "AND element.id = contient_element.id_element ";
                                    }
                                    break;

                                case "categorie_element":
                                    $jointures .= "AND element.id = element_categorie.id_element AND element_categorie.id_categorie_element = categorie_element.id ";
                                    break;

                                case "genre_element":
                                    $tabFaireJointureTmp[0] = "contient_element";
                                    $jointures .= $this->_faireJointure($typeDonnee, $tabFaireJointureTmp);
                                    $jointures .= "AND contient_element.id_genre_element = genre_element.id ";
                                    break;

                                case "nombre_element":
                                    $tabFaireJointureTmp[0] = "contient_element";
                                    $jointures .= $this->_faireJointure($typeDonnee, $tabFaireJointureTmp);
                                    $jointures .= "AND contient_element.id_nombre_element = nombre_element.id ";
                                    break;

                                case "element_biblio":
                                    $jointures .= "AND element.id = element_biblio.id_element ";
                                    break;

                                case "attestation":
                                    $jointures .= "AND contient_element.id_element = element.id AND contient_element.id_attestation = attestation.id ";
                                    break;

                                case "source":
                                    break;

                                default:
                                    if (in_array($table, $tabAttestation)) {
                                        $jointures .= "AND contient_element.id_element = element.id AND contient_element.id_attestation = attestation.id "; //Je fais la liaison entre attestation et element
                                        $tmpTypeDonnee = "attestation";
                                    }
                                    /* elseif(in_array($table,$tabSource)){
                                        $tmpTypeDonnee = "source";
                                    }*/ else {
                                        throw new NoFileException('Champ non pris en compte');
                                    }
                                    $tabFaireJointureTmp[0] = array($table);
                                    $jointures .= $this->_faireJointure($tmpTypeDonnee, $tabFaireJointureTmp[0]); //En sachant qu'il faut savoir quel type donnée correspond à quoi*/
                                    break;
                            }
                            break;

                        default:
                            throw new NoFileException('Champ non pris en compte');
                            break;
                    }
            }
        }
        return $jointures;
    }

    private function _operatorValue($op, $value)
    {
        $value = str_replace("'", "''", $value); //Je remplace les ' par des '' : Je les banalise pour les requêtes SQL
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

    private function _traiterRetour($rows, $nomBDD, $nomTable)
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
