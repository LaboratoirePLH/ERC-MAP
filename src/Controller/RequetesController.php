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
            $tmp = $idChercheur->getId();
            //Pour avoir l'id de la dernière requête
            $sql = "SELECT r.id FROM requetes r, chercheur c WHERE r.id_chercheur_id = c.id AND c.id = $tmp ORDER BY r.id DESC";
            $conn = $this->getDoctrine()->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt = $stmt->fetchAll();
            $reqSaveCorps = $stmt[0]["id"];
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
            if ($nomTable == "Element" && ($nomBDD == "fr" || $nomBDD == "en")) {
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
     * @Route("/requetes/chargerListeReq", name="requetes_charger_liste_requete", options={"expose"=true})
     */
    public function chargerListeRequete(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $user = (string)$this->get('security.token_storage')->getToken()->getUser(); //On récupère le nom + prénom de l'user en cours
            $idChercheur = $this->getDoctrine()->getRepository(Chercheur::class)->findOneBy(['prenomNom' => $user]); //On récupère son id
            $repo = $this->getDoctrine()->getManager()->getRepository(Chercheur::class);
            $rows = $repo->createQueryBuilder("e")
                ->andWhere('e.id = :id')
                ->setParameter('id', $idChercheur)
                ->getQuery()
                ->getResult();
            $rows = $rows[0]->getRequetes(); //Je get les requêtes
            foreach ($rows as $row) {
                $responseArray[] = array(
                    "id" => $row->getId(),
                    "tete" => $row->getReqLib()
                );
            }
            if (!isset($responseArray) || empty($responseArray)) {
                $responseArray = [];
            }
            return new JsonResponse($responseArray);
        }
    }
    /**
     * Page queries
     *
     * @Route("/requetes/supprimerReq", name="requetes_supprimer_requete", options={"expose"=true})
     */
    public function supprimerRequete(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $idReq = $request->request->get('idReq');
            //Requête QB portant sur l'id
            $repository = $this->getDoctrine()->getRepository(Requetes::class);
            $requete = $repository->find($idReq);
            $em = $this->getDoctrine()->getManager();
            $em->remove($requete);
            $em->flush();
            return new JsonResponse($em);
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
            $listeNull = $request->request->get('listeNull');

            $nbCriteres = sizeof($tabRequete);
            //Affectation du contenu de tabRequete
            $tabTable = array();
            $tabNomBDD = array();
            $tabValue = array();
            $tabOperator = array();
            $tabEtOu = array();
            $search = array('à', 'ã', 'â', 'ç', 'è', 'é', 'ë', 'ê', 'î', 'ï', 'ô', 'ù', 'û', 'ñ', ';', '%'); // J'enlève les caractères qui peuvent poser problème + sécurisation
            $replace = array('a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'o', 'u', 'u', 'n');
            for ($i = 0; $i < $nbCriteres; $i++) {
                $tabTable[$i] = $tabRequete["where" . $i]["table"];
                $tabNomBDD[$i] = $tabRequete["where" . $i]["nomBDD"];
                $tabOperator[$i] = $tabRequete["where" . $i]["operator"];
                $tabEtOu[$i] = $tabRequete["where" . $i]["etOu"];
                if (strpos($tabRequete["where" . $i]["operator"], '%') !== false) { //Si c'est un input
                    $tabValue[$i] = str_replace($search, $replace, mb_strtolower(strip_tags(stripslashes($tabRequete["where" . $i]["value"])))); //Sécurisation des values + casse
                } else {
                    $tabValue[$i] = $tabRequete["where" . $i]["value"];
                }
            }
            $tabTableAffiche = array(); //Le tableau qui va prendre les tables requises pour le SELECT
            $tabOrdreAffiche = array(); //Pour l'ordre d'affichage des select
            for ($i = 0; $i < sizeof($tabAffiche); $i++) {
                $tabTableAffiche[$i] = $tabAffiche["select" . $i]["table"];
                $ordreTmp = $tabAffiche["select" . $i]["ordre"] - 1;
                $tabOrdreAffiche[$ordreTmp] = $tabAffiche["select" . $i];
            }
            //Pour le FROM WHERE
            $condition = " "; //Where
            for ($i = 0; $i < sizeof($tabTable); $i++) {
                if ($tabTable[$i] != "theonymes_implicites") { //Si ce n'est pas théonyes_implicites, car j'ai fait la condition dans les jointures
                    if ($tabTable[$i] != "NULL") { //Si ce n'est pas un cas spécial
                        if (strpos($tabOperator[$i], '%') !== false) { //Si c'est un input, donc s'il y a un "%" dans le champ
                            $condition .= $tabEtOu[$i] . " translate(lower(" . $tabTable[$i] . "." . $tabNomBDD[$i] . ")" . ",'àãâçèéëêîïôùûñ','aaaceeeeiiouun') "; //On met tout sans accents/majuscule
                        } else {
                            $condition .= $tabEtOu[$i] . " " . $tabTable[$i] . "." . $tabNomBDD[$i] . " ";
                        }
                        $condition .= $this->_operatorValue($tabOperator[$i], $tabValue[$i]);
                    }
                } else { //Si c'est un theonyme
                    $condition .= $tabEtOu[$i] . " e1.etat_absolu "; //Elément de comparaison : l'état absolu du second élément (donc du fils)
                    $condition .= $this->_operatorValue($tabOperator[$i], "<div>" . $tabValue[$i] . "</div>"); //Je rajoute le <div> qui est dans la BDD
                }
            }

            //Traitement des listes nulles
            if (!empty($listeNull) && isset($listeNull)) {
                for ($i = 0; $i < sizeof($listeNull[0]); $i++) {
                    $condition .= $listeNull[0][$i]; //Les conditions
                }

                if (!empty($listeNull[1]) && isset($listeNull[1])) {
                    for ($i = 0; $i < sizeof($listeNull[1]); $i++) {
                        $tabTable[] = $listeNull[1][$i]; //Les tables à ajouter s'il y en a
                    }
                }
            }

            //Je suis obligé de mettre ça ici car si je le met avant, il ne prend pas les conditions sur une même table à cause du array_unique. Ex : Element qui a un nom qui commence par x, et element qui a un nom qui finit par y
            $tabTable = array_merge($tabTable, $tabTableAffiche); //J'ajoute les tables du SELECT pour les jointures et le FROM
            $tabTable = array_values(array_unique($tabTable, SORT_REGULAR)); //J'enlève les doublons, le array_values est là pour éviter les erreurs d'index dûes au array_unique
            $from = "";
            $from .= $this->_faireFrom($from, $tabTable, $typeDonnee);

            //Pour les jointures
            $jointures = " WHERE 1=1";
            $jointures .= $this->_faireJointure($typeDonnee, $tabTable, 0);

            //Pour le select;
            $select = "SELECT DISTINCT $typeDonnee.id as selectid, ";
            $select .= $this->_faireSelect($tabOrdreAffiche);

            //Group by
            $groupBy = "GROUP BY selectid, ";
            for ($i = 0; $i < sizeof($tabOrdreAffiche); $i++) {
                $groupBy .= "select" . $i;
                if ($i + 1 == sizeof($tabOrdreAffiche)) {
                    $groupBy .= " ";
                } else {
                    $groupBy .= ", ";
                }
            }
            $orderBy = "ORDER BY $typeDonnee.id";
            $sql = "";
            $sql .= $select;
            $sql .= $from;
            $sql .= $jointures;
            $sql .= $condition;
            $sql .= $groupBy;
            $sql .= $orderBy;

            // return new JsonResponse($sql);

            //Requête
            $conn = $this->getDoctrine()->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            //Traitement de la requête
            $objectResponse = [];
            $i = 0;
            while ($data = $stmt->fetch()) {
                for ($j = 0; $j < sizeof($tabAffiche); $j++) { //Boucle pour récupérer tous les champs SELECT
                    $objectResponse[$i]["select" . $j] = strip_tags($data["select" . $j]); //Affectation des champs à une variable
                    $objectResponse[$i]["selectid"] = strip_tags($data["selectid"]); //Je récupère l'id
                }
                $i++;
            }
            return new JsonResponse($objectResponse);
        }
    }

    private function _faireSelect($tab)
    {
        $select = "";
        for ($i = 0; $i < sizeof($tab); $i++) {
            if (strcmp(substr($tab[$i]["table"], 0, 6), "agent_") == 0) //Pour le localisation_agent
            {
                $tab[$i]["table"] = substr($tab[$i]["table"], 6);
            }
            if ($tab[$i] != "NULL" && $tab[$i]["nomBDD"] != "NULL") { //Si ce n'est pas un cas spécial de listeNull
                $select .= $tab[$i]["table"] . "." . $tab[$i]["nomBDD"] . " as select" . $i; //Ex : "attestation.id as select0"
                if ($i + 1 != sizeof($tab)) { //Pour ne pas mettre la virgule au dernier
                    $select .= ", ";
                } else {
                    $select .= " "; //J'ajoute un espace pour le FROM après
                }
            }
        }
        return $select;
    }
    private function _faireFrom($from, $tabTable, $typeDonnee)
    {
        $tabVerif = array(); //Tableau qui va contenir tous les from
        $tabVerif[] = $typeDonnee;
        for ($i = 0; $i < sizeof($tabTable); $i++) {
            switch ($tabTable[$i]) {
                    //Cas spéciaux :
                case "agent_" . substr($tabTable[$i], 6): //Si c'est localisation_agent
                    $tabVerif[] = "localisation l1";
                    $tabVerif[] = "agent";
                    if (strcmp(substr($tabTable[$i], 6), "q_topographie") == 0 || strcmp(substr($tabTable[$i], 6), "q_fonction") == 0) { //Pour les périphériques avec d'autres tables
                        $tabVerif[] = "localisation_" . substr($tabTable[$i], 6); //Ex : localisation_q_topographie
                        $tabVerif[] = substr($tabTable[$i], 6); //Ex : q_topographie
                    } else if (strcmp(substr($tabTable[$i], 6), "localisation") != 0) { //Encore les périphériques
                        $tabVerif[] = substr($tabTable[$i], 6); //Ex : sous_region
                    }
                    break;
                case "theonymes_implicites":
                    $tabVerif[] = "element";
                    $tabVerif[] = "element e1";
                    if ($typeDonnee == "attestation" || $typeDonnee == "source") {
                        $tabVerif[] = "contient_element";
                    }
                    break;
                    //Attestation
                case "categorie_materiel":
                    if ($typeDonnee == "element") {
                        $tabVerif[] = "contient_element";
                    }
                    $tabVerif[] = "materiel";
                    $tabVerif[] = "attestation_materiel";
                    $tabVerif[] = "attestation";
                    break;
                case "materiel":
                    if ($typeDonnee == "element") {
                        $tabVerif[] = "contient_element";
                    }
                    $tabVerif[] = "attestation_materiel";
                    $tabVerif[] = "attestation";
                    break;
                case "occasion":
                    if ($typeDonnee == "element") {
                        $tabVerif[] = "contient_element";
                    }
                    $tabVerif[] = "attestation_occasion";
                    $tabVerif[] = "attestation";
                    break;
                case "categorie_occasion":
                    if ($typeDonnee == "element") {
                        $tabVerif[] = "contient_element";
                    }
                    $tabVerif[] = "occasion";
                    $tabVerif[] = "attestation_occasion";
                    $tabVerif[] = "attestation";
                    break;
                case "etat_fiche":
                case "attestation_occasion":
                    if ($typeDonnee == "element") {
                        $tabVerif[] = "contient_element";
                    }
                    $tabVerif[] = "attestation";
                    break;
                    //Pour tous les périphériques de Localisation
                case "sous_region":
                case "grande_region":
                case "entite_politique":
                    $tabVerif[] = "localisation";
                    break;
                case "q_topographie":
                case "q_fonction":
                    $tabVerif[] = "localisation";
                    $tabVerif[] = "localisation_" . $tabTable[$i];
                    break;
                    //Pour agent
                case "nature":
                case "genre":
                case "agentivite":
                    $tabVerif[] = "agent";
                    $tabVerif[] = "agent_" . $tabTable[$i];
                    break;
                case "statut_affiche":
                    $tabVerif[] = "agent_statut"; //La table intermédiaire (Ex: agent_genre)
                    $tabVerif[] = "agent";
                    break;
                    //Element
                case "genre_element":
                case "nombre_element":
                    $tabVerif[] = "contient_element";
                    if ($typeDonnee == "attestation" || $typeDonnee == "source") { //Quand je veux passer de attestation à un périphérique de element ou de source à élément
                        $tabVerif[] = "element";
                        $tabVerif[] = "contient_element";
                        $tabVerif[] = "attestation"; //Si c'est la source, pour la jointure source -> attestation -> element
                    }
                    break;
                case "categorie_element":
                    $tabVerif[] = "element_categorie";
                    if ($typeDonnee == "attestation" || $typeDonnee == "source") { //Quand je veux passer de attestation à un périphérique de element ou de source à élément
                        $tabVerif[] = "element";
                        $tabVerif[] = "contient_element";
                        $tabVerif[] = "attestation"; //Si c'est la source, pour la jointure source -> attestation -> element
                    }
                    break;
                case "contient_element":
                    if ($typeDonnee == "attestation" || $typeDonnee == "source") { //Quand je veux passer de attestation à un périphérique de element ou de source à élément
                        $tabVerif[] = "element";
                        $tabVerif[] = "contient_element";
                        $tabVerif[] = "attestation"; //Si c'est la source, pour la jointure source -> attestation -> element
                    }
                    break;
                case "element":
                    if ($typeDonnee == "attestation" || $typeDonnee == "source") { //Quand je veux passer de attestation à element
                        $tabVerif[] = "contient_element";
                        $tabVerif[] = "attestation"; //Si c'est la source, pour la jointure source -> attestation -> element
                    }
                    break;
                case "pratique":
                    $tabVerif[] = "attestation_pratique";
                    break;
                    //Source
                case "materiau":
                case "categorie_materiau":
                case "type_support":
                case "categorie_support":
                case "categorie_source":
                case "titre":
                    if ($typeDonnee == "element") {
                        $tabVerif[] = "contient_element";
                        $tabVerif[] = "attestation";
                    }
                    $tabVerif[] = "source";
                    break;

                case "type_source":
                    if ($typeDonnee == "element") {
                        $tabVerif[] = "contient_element";
                        $tabVerif[] = "attestation";
                    }
                    $tabVerif[] = "source";
                    $tabVerif[] = "source_type_source";
                    break;

                case "langue":
                case "auteur":
                    $tabVerif[] = "source_" . $tabTable[$i];
                    $tabVerif[] = "source";
                    if ($typeDonnee == "element") {
                        $tabVerif[] = "contient_element";
                        $tabVerif[] = "attestation";
                    }
                    $tabVerif[] = "source";
                    break;
                    //Biblio
                case "biblio":
                    if ($typeDonnee == "source") {
                        $tabVerif[] = "source_biblio";
                    }
                    if ($typeDonnee == "element") {
                        $tabVerif[] = "element_biblio";
                    }
                    break;
                case "element_biblio":
                    if ($typeDonnee == "source") {
                        $tabVerif[] = "source_biblio";
                        $tabVerif[] = "biblio";
                    }
                    break;
                case "source_biblio":
                    if ($typeDonnee == "element") {
                        $tabVerif[] = "element_biblio";
                        $tabVerif[] = "biblio";
                    }
                    break;
            } //End switch
            if (!in_array($tabTable[$i], $tabVerif) && $tabTable[$i] != "NULL" && strcmp(substr($tabTable[$i], 0, 6), "agent_") != 0) { //Pour le NULL : les cas spéciaux des listeNull et si c'est pas un localisation_agent
                $tabVerif[] = $tabTable[$i];
            }
        }
        //Je met tout dans un string
        $from = "FROM ";
        $tabVerif = array_values(array_unique($tabVerif, SORT_REGULAR)); //J'enlève les doublons, le array_values est là pour éviter les erreurs d'index dûes au array_unique
        foreach ($tabVerif as $key => $val) {
            if ($key + 1 == sizeof($tabVerif)) {
                $from .= $val;
            } else {
                $from .= $val . ", ";
            }
        }
        return $from;
    }
    private function _faireJointure($typeDonnee, $tabTable, $recursif) //Recursif : 1 si oui, 0 si non
    {
        $tabVerif = array(); //Même utilité que pour le FROM
        $tabElement = array("theonymes_implicites", "contient_element", "categorie_element", "contient_element", "genre_element", "nombre_element", "element_biblio");
        $tabAttestation = array("etat_fiche", "categorie_occasion", "occasion", "categorie_materiel", "materiel", "agent", "nature", "genre", "agentivite", "statut_affiche");
        $tabSource = array("materiau", "categorie_materiau", "type_source", "type_support", "categorie_support", "categorie_source", "langue", "titre", "auteur");
        foreach ($tabTable as $table) {
            switch ($table) { //Tout ce qui est générique
                case "agent_" . substr($table, 6): //Pour le localisation_agent
                    $tabVerif[] = "l1.id = agent.localisation_id";
                    $tabVerif[] = "attestation.id = agent.id_attestation";
                    if (strcmp(substr($table, 6), "q_topographie") == 0 || strcmp(substr($table, 6), "q_fonction") == 0) { //Pour les périphériques avec d'autres tables
                        $tabVerif[] = "localisation_" . substr($table, 6) . ".id_localisation = l1.id";
                        $tabVerif[] = "localisation_" . substr($table, 6) . ".id_" . substr($table, 6) . " = " . substr($table, 6) . ".id";
                    } else if (strcmp(substr($table, 6), "localisation") != 0) { //Encore les périphériques
                        if (strcmp(substr($table, 6), "entite_politique") == 0) //Pour entite_politique car il a un traitement différent
                        {
                            $tabVerif[] = "l1.entite_politique = entite_politique.id";
                        } else //Le reste
                        {
                            $tabVerif[] = substr($table, 6) . ".id = l1." . substr($table, 6) . "_id"; //Ex : sous_region.id = localisation.sous_region_id
                        }
                    }
                    break;
                case "localisation": //Pour la table localisation
                    if ($typeDonnee == "source") { //Si c'est sur la source, c'est un traitement différent
                        $tabVerif[] = "source.localisation_origine_id = localisation.id";
                    } else { //Pour l'élément et l'attestation
                        $tabVerif[] = $typeDonnee . ".localisation_id = localisation.id";
                    }
                    break;
                case "entite_politique":
                    $tabFaireJointureTmp[0] = "localisation";
                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                    $tabVerif[] = "localisation.entite_politique = entite_politique.id";
                    break;
                case "grande_region":
                case "sous_region":
                    $tabFaireJointureTmp[0] = "localisation";
                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                    $tabVerif[] = "localisation." . $table . "_id = " . $table . ".id"; //AND localisation.grnade_region_id = grande_region.id
                    break;
                case "q_topographie":
                case "q_fonction":
                    $tabFaireJointureTmp[0] = "localisation";
                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                    $tabVerif[] = "localisation.id = localisation_" . $table . ".id_localisation"; // Ex : AND localisation.id = localisation_q_topographie.id_localisation AND localisation_q_topographie.id_q_topographie = q_topographie.id
                    $tabVerif[] = "localisation_" . $table . ".id_" . $table . " = " . $table . ".id";
                    break;
                case "datation": //Pour la datation : attestation et source
                    $tabVerif[] = "" . $typeDonnee . ".datation_id = datation.id"; //AND source.datation_id = datation.id
                    break;
                case "NULL": //Cas spéciaux des listeNull
                    break;
                default:
                    switch ($typeDonnee) {
                        case "attestation":
                            switch ($table) {
                                    //Les périphériques à Attestation
                                case "attestation": //Si c'est la même table
                                    break;
                                case "etat_fiche":
                                    $tabVerif[] = "attestation.id_etat_fiche = etat_fiche.id";
                                    break;
                                case "categorie_occasion":
                                    $tabFaireJointureTmp[0] = "occasion";
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1)); //J'appelle récursivement la fonction, pour qu'il me renvoir la jointure que je concatène
                                    $tabVerif[] = "occasion.categorie_occasion_id = categorie_occasion.id";
                                    break;
                                case "attestation_occasion":
                                    $tabVerif[] = "attestation_occasion.id_attestation = attestation.id";
                                    break;
                                case "occasion":
                                    $tabVerif[] = "attestation_occasion.id_attestation = attestation.id";
                                    $tabVerif[] = "attestation_occasion.id_occasion = occasion.id";
                                    break;
                                case "categorie_materiel":
                                    $tabFaireJointureTmp[0] = "materiel";
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                                    $tabVerif[] = "materiel.categorie_materiel_id = categorie_materiel.id";
                                    break;
                                case "materiel":
                                    $tabVerif[] = "attestation_materiel.id_attestation = attestation.id";
                                    $tabVerif[] = "attestation_materiel.id_materiel = materiel.id";
                                    break;
                                    //Agent et ses périphériques
                                case "agent":
                                    $tabVerif[] = "attestation.id = agent.id_attestation ";
                                    break;
                                case "activite_agent":
                                    $tabFaireJointureTmp[0] = "agent";
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                                    $tabVerif[] = "agent.id = agent_activite.id_agent";
                                    $tabVerif[] = "agent_activite.id_activite = activite_agent.id";
                                    break;
                                case "nature":
                                case "genre":
                                case "agentivite":
                                    $tabFaireJointureTmp[0] = "agent";
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                                    $tabVerif[] = "agent.id = agent_" . $table . ".id_agent"; //AND agent.id = agent_agentivite.id_agent AND agent_agentivite.id_agentivite = agentivite.id
                                    $tabVerif[] = "agent_" . $table . ".id_" . $table . " = " . $table . ".id";
                                    break;
                                case "statut_affiche":
                                    $tabFaireJointureTmp[0] = "agent";
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                                    $tabVerif[] = "agent.id = agent_statut.id_agent";
                                    $tabVerif[] = "agent_statut.id_statut = " . $table . ".id";
                                    break;
                                case "pratique":
                                    $tabVerif[] = "attestation.id = attestation_pratique.id_attestation";
                                    $tabVerif[] = "attestation_pratique.id_pratique = pratique.id";
                                    break;
                                case "formule":
                                    $tabVerif[] = "attestation.id = formule.attestation_id";
                                    break;
                                case "source":
                                    $tabVerif[] = "attestation.id_source = source.id";
                                    break;
                                case "element":
                                    $tabVerif[] = "contient_element.id_element = element.id";
                                    $tabVerif[] = "contient_element.id_attestation = attestation.id";
                                    break;
                                default:
                                    if (in_array($table, $tabElement)) {
                                        $tabFaireJointureTmp[0] = "element";
                                        $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1)); //Entre attestation et element
                                        $tmpTypeDonnee = "element";
                                    } elseif (in_array($table, $tabSource)) {
                                        $tmpTypeDonnee = "source";
                                        $tabFaireJointureTmp[0] = "source";
                                        $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                                    } else {
                                        throw new NoFileException('Champ non pris en compte');
                                    }
                                    $tabFaireJointureTmp[0] = array($table);
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($tmpTypeDonnee, $tabFaireJointureTmp[0], 1)); //En sachant qu'il faut savoir quel type donnée correspond à quoi*/
                                    break;
                            }
                            break;
                        case "source":
                            switch ($table) {
                                case "source":
                                    break;
                                case "element":
                                    $tabFaireJointureTmp[0] = "source";
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure("element", $tabFaireJointureTmp, 1));
                                    break;

                                case "type_source":
                                    $tabVerif[] = "source_type_source.id_source = source.id";
                                    $tabVerif[] = "source_type_source.id_type_source = type_source.id";
                                    break;

                                case "materiau":
                                case "categorie_materiau":
                                case "type_support":
                                case "categorie_support":
                                case "categorie_source":
                                    $tabVerif[] = "source." . $table . "_id = " . $table . ".id";
                                    break;
                                case "langue":
                                    $tabVerif[] = "source.id = source_langue.id_source";
                                    $tabVerif[] = "source_langue.id_langue = langue.id";
                                    break;
                                case "titre":
                                    $tabVerif[] = "source.titre_principal_id = titre.id";
                                    break;
                                case "auteur":
                                    $tabVerif[] = "source.id = source_auteur.id_source";
                                    $tabVerif[] = "source_auteur.id_auteur = auteur.id";
                                    break;
                                case "attestation":
                                    $tabVerif[] = "attestation.id_source = source.id";
                                    break;
                                case "source_biblio":
                                    $tabVerif[] = "source.id = source_biblio.id_source";
                                    break;
                                case "biblio":
                                    $tmpTypeDonnee = "source";
                                    $tabFaireJointureTmp[0] = "source_biblio";
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                                    $tabVerif[] = "source_biblio.id_biblio = biblio.id";
                                    break;
                                case "element_biblio":
                                    $tmpTypeDonnee = "source";
                                    $tabFaireJointureTmp[0] = "biblio";
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                                    $tabVerif[] = "biblio.id = element_biblio.id_biblio";
                                    break;
                                default:
                                    if (in_array($table, $tabAttestation)) {
                                        $tmpTypeDonnee = "attestation";
                                        $tabFaireJointureTmp[0] = "attestation";
                                        $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                                    } elseif (in_array($table, $tabElement)) {
                                        $tmpTypeDonnee = "element";
                                        $tabFaireJointureTmp[0] = "element";
                                        $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                                    } else {
                                        throw new NoFileException('Champ non pris en compte');
                                    }
                                    $tabFaireJointureTmp[0] = array($table);
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($tmpTypeDonnee, $tabFaireJointureTmp[0], 1)); //Je fais la jointure avec les deux autres
                                    break;
                            }
                            break;
                        case "element":
                            switch ($table) {
                                case "element":
                                    break;
                                case "theonymes_implicites":
                                    $tabVerif[] = "e1.id = theonymes_implicites.id_parent AND element.id = theonymes_implicites.id_enfant";
                                    break;
                                case "contient_element":
                                    $tabVerif[] = "element.id = contient_element.id_element";
                                    break;
                                case "categorie_element":
                                    $tabVerif[] = "element.id = element_categorie.id_element";
                                    $tabVerif[] = "element_categorie.id_categorie_element = categorie_element.id";
                                    break;
                                case "genre_element":
                                    $tabFaireJointureTmp[0] = "contient_element";
                                    $tabVerif = $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                                    $tabVerif[] = "contient_element.id_genre_element = genre_element.id";
                                    break;
                                case "nombre_element":
                                    $tabFaireJointureTmp[0] = "contient_element";
                                    $tabVerif = $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                                    $tabVerif[] = "contient_element.id_nombre_element = nombre_element.id";
                                    break;
                                case "element_biblio":
                                    $tabVerif[] = "element.id = element_biblio.id_element";
                                    break;
                                case "attestation":
                                    $tabVerif[] = "contient_element.id_element = element.id";
                                    $tabVerif[] = "contient_element.id_attestation = attestation.id";
                                    break;
                                case "source":
                                    $tabFaireJointureTmp[0] = "attestation";
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1)); //On fait la jointure sur attestation
                                    $tabFaireJointureTmp[0] = "source";
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure("attestation", $tabFaireJointureTmp, 1)); //On fait la liaison entre attestation et source
                                    break;
                                case "element_biblio":
                                    $tabVerif[] = "element.id = element_biblio.id_element";
                                    break;
                                case "biblio":
                                    $tmpTypeDonnee = "element";
                                    $tabFaireJointureTmp[0] = "element_biblio";
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                                    $tabVerif[] = "element_biblio.id_biblio = biblio.id";
                                    break;
                                case "source_biblio":
                                    $tmpTypeDonnee = "element";
                                    $tabFaireJointureTmp[0] = "biblio";
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1));
                                    $tabVerif[] = "biblio.id = source_biblio.id_biblio";
                                    break;
                                default:
                                    if (in_array($table, $tabAttestation)) {
                                        $tmpTypeDonnee = "attestation";
                                        $tabFaireJointureTmp[0] = "attestation";
                                        $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1)); //On fait la liaison entre element et attestation
                                    } elseif (in_array($table, $tabSource)) {
                                        $tmpTypeDonnee = "source";
                                        $tabFaireJointureTmp[0] = "source";
                                        $tabVerif = array_merge($tabVerif, $this->_faireJointure($typeDonnee, $tabFaireJointureTmp, 1)); //On fait la liaison entre element et source
                                    } else {
                                        throw new NoFileException('Champ non pris en compte');
                                    }
                                    $tabFaireJointureTmp[0] = array($table);
                                    $tabVerif = array_merge($tabVerif, $this->_faireJointure($tmpTypeDonnee, $tabFaireJointureTmp[0], 1)); //En sachant qu'il faut savoir quel type donnée correspond à quoi*/
                                    break;
                            }
                            break;
                        default:
                            throw new NoFileException('Champ non pris en compte');
                            break;
                    }
            }
        }
        if ($recursif == 1) {
            return $tabVerif;
        } else {
            $jointures = "";
            $tabVerif = array_values(array_unique($tabVerif, SORT_REGULAR)); //J'enlève les doublons, le array_values est là pour éviter les erreurs d'index dûes au array_unique
            foreach ($tabVerif as $val) {
                $jointures .= " AND " . $val;
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
                $operator = "ILIKE '%" . $value . "%' ";
                break;
            case "NOT LIKE %var%":
                $operator = "NOT ILIKE '%" . $value . "%' ";
                break;
            case "LIKE var%":
                $operator = "ILIKE '" . $value . "%' ";
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
                $tabEtatAbsolu = array();
                $i = 0;
                foreach ($rows as $row) { //Pour chaque élément
                    $elem = $row->getTheonymesImplicites(); //Je get le théonyme d'un des élement
                    if (!is_null($elem[0])) //Si l'élément a des théonymes
                    {
                        foreach ($elem as $theo) { //Pour chaque théonyme contenu dans l'élement courrant
                            $tabEtatAbsolu[$i] = $theo->getEtatAbsolu(); //On le récupère
                            $i++;
                        }
                    }
                }
                $tabEtatAbsolu = array_unique($tabEtatAbsolu, SORT_REGULAR); //Suppression des doublons
                foreach ($tabEtatAbsolu as $row) { //Formattage du tableau pour qu'il corresponde au traitement en JS
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
