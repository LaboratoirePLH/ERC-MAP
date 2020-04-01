CREATE
OR REPLACE VIEW public.vue_agent_en AS
SELECT
    agent.id AS "Id Agent",
    agent.id_attestation AS "Id Attestation",
    agent.est_localisee AS "Located",
    agent.localisation_id AS "Id Location",
    agent.designation AS "Designation",
    agent.commentaire_en AS "Commentary",
    string_agg(DISTINCT agentivite.nom_en :: text, ', ' :: text) AS "Agency",
    string_agg(DISTINCT nature.nom_en :: text, ', ' :: text) AS "Nature",
    string_agg(DISTINCT genre.nom_en :: text, ', ' :: text) AS "Gender",
    string_agg(
        DISTINCT statut_affiche.nom_en :: text,
        ', ' :: text
    ) AS "Explicit status",
    string_agg(
        DISTINCT activite_agent.nom_en :: text,
        ', ' :: text
    ) AS "Profession"
FROM
    agent
    LEFT JOIN agent_agentivite ON agent_agentivite.id_agent = agent.id
    LEFT JOIN agentivite ON agentivite.id = agent_agentivite.id_agentivite
    LEFT JOIN agent_nature ON agent_nature.id_agent = agent.id
    LEFT JOIN nature ON nature.id = agent_nature.id_nature
    LEFT JOIN agent_genre ON agent_genre.id_agent = agent.id
    LEFT JOIN genre ON genre.id = agent_genre.id_genre
    LEFT JOIN agent_statut ON agent_statut.id_agent = agent.id
    LEFT JOIN statut_affiche ON statut_affiche.id = agent_statut.id_statut
    LEFT JOIN agent_activite ON agent_activite.id_agent = agent.id
    LEFT JOIN activite_agent ON activite_agent.id = agent_activite.id_activite
GROUP BY
    agent.id,
    agent.id_attestation,
    agent.est_localisee,
    agent.localisation_id,
    agent.designation,
    agent.commentaire_en;

CREATE
OR REPLACE VIEW public.vue_agent_fr AS
SELECT
    agent.id AS "Id Agent",
    agent.id_attestation AS "Id Attestation",
    agent.est_localisee AS "Est localisé",
    agent.localisation_id AS "Id Localisation",
    agent.designation AS "Désignation",
    agent.commentaire_fr AS "Commentaire",
    string_agg(DISTINCT agentivite.nom_fr, ', ') AS "Agentivité",
    string_agg(DISTINCT nature.nom_fr, ', ') AS "Nature",
    string_agg(DISTINCT genre.nom_fr, ', ') AS "Genre",
    string_agg(DISTINCT statut_affiche.nom_fr, ', ') AS "Statut affiché",
    string_agg(DISTINCT activite_agent.nom_fr, ', ') AS "Activité"
FROM
    agent
    LEFT JOIN agent_agentivite ON agent_agentivite.id_agent = agent.id
    LEFT JOIN agentivite ON agentivite.id = agent_agentivite.id_agentivite
    LEFT JOIN agent_nature ON agent_nature.id_agent = agent.id
    LEFT JOIN nature ON nature.id = agent_nature.id_nature
    LEFT JOIN agent_genre ON agent_genre.id_agent = agent.id
    LEFT JOIN genre ON genre.id = agent_genre.id_genre
    LEFT JOIN agent_statut ON agent_statut.id_agent = agent.id
    LEFT JOIN statut_affiche ON statut_affiche.id = agent_statut.id_statut
    LEFT JOIN agent_activite ON agent_activite.id_agent = agent.id
    LEFT JOIN activite_agent ON activite_agent.id = agent_activite.id_activite
GROUP BY
    agent.id,
    agent.id_attestation,
    agent.est_localisee,
    agent.localisation_id,
    agent.designation,
    agent.commentaire_fr;

CREATE
OR REPLACE VIEW public.vue_attestation_en AS
SELECT
    attestation.id AS "Id testimony",
    attestation.id_source AS "Id source",
    etat_fiche.nom_en AS "Validation",
    attestation.localisation_id AS "Id Location",
    datation.post_quem AS "Post Quem",
    datation.ante_quem AS "Ante Quem",
    datation.commentaire_en AS "Datation commentary",
    attestation.extrait_avec_restitution AS "With rendition",
    attestation.translitteration "Transliteration",
    attestation.est_localisee AS "Located",
    (
        SELECT
            count(*) AS count
        FROM
            contient_element
        WHERE
            contient_element.id_attestation = attestation.id
    ) AS "Number of element",
    (
        SELECT
            count(*) AS count
        FROM
            agent
        WHERE
            agent.id_attestation = attestation.id
    ) AS "Number of agent",
    concat(
        'https://base-map-polytheisms.huma-num.fr/attestation/',
        attestation.id
    ) AS "BD Link",
    string_agg(
        DISTINCT traduction_attestation.nom_en :: text,
        ' / ' :: text
    ) AS "Traduction(s)",
    string_agg(DISTINCT formule.formule, '<br>' :: text) AS "Formula(s)",
    string_agg(DISTINCT pratique.nom_en :: text, ', ' :: text) AS "Connected acts",
    attestation.commentaire_en AS "Commentary",
    attestation.date_modification AS "Last modification",
    attestation.passage AS "Reference to the source",
    attestation.fiabilite_attestation AS "Reliability of the reading",
    attestation.prose AS "Prose",
    attestation.poesie AS "Poetry",
    attestation.est_datee AS "Dated",
    formule.puissances_divines AS "Divine powers"
FROM
    attestation
    LEFT JOIN etat_fiche ON etat_fiche.id = attestation.id_etat_fiche
    LEFT JOIN datation ON datation.id = attestation.datation_id
    LEFT JOIN traduction_attestation ON traduction_attestation.id_attestation = attestation.id
    LEFT JOIN formule ON formule.attestation_id = attestation.id
    LEFT JOIN attestation_pratique ON attestation_pratique.id_attestation = attestation.id
    LEFT JOIN pratique ON pratique.id = attestation_pratique.id_pratique
GROUP BY
    attestation.id,
    attestation.id_source,
    etat_fiche.nom_en,
    attestation.localisation_id,
    datation.post_quem,
    datation.ante_quem,
    datation.commentaire_en,
    formule.puissances_divines;

CREATE
OR REPLACE VIEW public.vue_attestation_fr AS
SELECT
    attestation.id AS "Id de l'attestation",
    attestation.id_source AS "Id de la source",
    etat_fiche.nom_fr AS "Validation de la fiche",
    attestation.localisation_id,
    datation.post_quem AS "Post Quem",
    datation.ante_quem AS "Ante Quem",
    datation.commentaire_fr AS "Commentaire Datation",
    attestation.extrait_avec_restitution,
    attestation.translitteration,
    est_localisee AS "Est localisée",
    (
        SELECT
            count(*)
        FROM
            contient_element
        WHERE
            contient_element.id_attestation = attestation.id
    ) AS "Nombre élément",
    (
        SELECT
            count(*)
        FROM
            agent
        WHERE
            agent.id_attestation = attestation.id
    ) AS "Nombre agent",
    concat(
        'https://base-map-polytheisms.huma-num.fr/attestation/',
        attestation.id
    ) AS "Lien_BD",
    String_AGG(DISTINCT traduction_attestation.nom_fr, ' / ') AS "Traduction(s) de l'attestation",
    string_agg(DISTINCT formule.formule, '<br>') AS "Formule(s)",
    String_AGG(DISTINCT pratique.nom_fr, ', ') AS "Pratique(s)",
    Attestation.commentaire_fr AS "Commentaire",
    Attestation.date_modification AS "Dernière modification",
    Attestation.passage AS "Passage",
    Attestation.fiabilite_attestation AS "Qualité de lecture",
    Attestation.prose AS "Prose",
    Attestation.poesie AS "Poésie",
    Attestation.est_datee AS "Est datée",
    formule.puissances_divines AS "Puissances divines"
FROM
    Attestation
    LEFT JOIN etat_fiche ON etat_fiche.id = attestation.id_etat_fiche
    LEFT JOIN datation ON datation.id = attestation.datation_id
    LEFT JOIN traduction_attestation ON traduction_attestation.id_attestation = attestation.id
    LEFT JOIN formule ON formule.attestation_id = attestation.id
    LEFT JOIN attestation_pratique ON attestation_pratique.id_attestation = attestation.id
    LEFT JOIN pratique ON pratique.id = attestation_pratique.id_pratique
GROUP BY
    attestation.id,
    attestation.id_source,
    etat_fiche.nom_fr,
    attestation.localisation_id,
    datation.post_quem,
    datation.ante_quem,
    datation.commentaire_fr,
    formule.puissances_divines;

CREATE
OR REPLACE VIEW vue_contient_element_fr AS
SELECT
    row_number() over () AS "Id contient élément",
    contient_element.id_attestation AS "Id attestation",
    contient_element.id_element AS "Id élément",
    contient_element.position_element AS "Position élément",
    element.etat_absolu AS "Etat absolu",
    element.beta_code AS "Beta Code",
    contient_element.suffixe AS "Suffixe",
    contient_element.etat_morphologique AS "Etat morphologique",
    contient_element.en_contexte AS "Elément en contexte",
    genre_element.nom_fr AS "Genre",
    nombre_element.nom_fr AS "Nombre",
    categorie_element.nom_fr AS "Catégorie contextuelle",
    concat(
        'https://base-map-polytheisms.huma-num.fr/attestation/',
        contient_element.id_attestation
    ) AS "Lien_BD_attestation",
    concat(
        'https://base-map-polytheisms.huma-num.fr/element/',
        contient_element.id_element
    ) AS "Lien_BD_element",
    attestation.extrait_avec_restitution AS "Attestation avec restitution",
    attestation.translitteration AS "Attestation translitterée",
    attestation.passage AS "Passage de la source"
FROM
    contient_element
    LEFT JOIN attestation ON attestation.id = contient_element.id_attestation
    LEFT JOIN element ON element.id = contient_element.id_element
    LEFT JOIN genre_element ON genre_element.id = contient_element.id_genre_element
    LEFT JOIN nombre_element ON nombre_element.id = contient_element.id_nombre_element
    LEFT JOIN categorie_element ON categorie_element.id = contient_element.id_categorie_element
ORDER BY
    "Id contient élément";

CREATE
OR REPLACE VIEW public.vue_contient_element_en AS
SELECT
    row_number() OVER () AS "Id Element in testimony",
    contient_element.id_attestation AS "Id testimony",
    contient_element.id_element AS "Id element",
    contient_element.position_element AS "Position of the element",
    element.etat_absolu AS "Absolute form",
    element.beta_code AS "Beta Code",
    contient_element.suffixe AS "Suffixe",
    contient_element.etat_morphologique AS "Morphological form",
    contient_element.en_contexte AS "Context element",
    genre_element.nom_fr AS "Gender",
    nombre_element.nom_fr AS "Number",
    categorie_element.nom_fr AS "Contextual category",
    concat(
        'https://base-map-polytheisms.huma-num.fr/attestation/',
        contient_element.id_attestation
    ) AS "BD Link testimony",
    concat(
        'https://base-map-polytheisms.huma-num.fr/element/',
        contient_element.id_element
    ) AS "BD Link element",
    attestation.extrait_avec_restitution AS "Testimony with rendition",
    attestation.translitteration AS "Transliteration",
    attestation.passage AS "Reference to the source"
FROM
    contient_element
    LEFT JOIN attestation ON attestation.id = contient_element.id_attestation
    LEFT JOIN element ON element.id = contient_element.id_element
    LEFT JOIN genre_element ON genre_element.id = contient_element.id_genre_element
    LEFT JOIN nombre_element ON nombre_element.id = contient_element.id_nombre_element
    LEFT JOIN categorie_element ON categorie_element.id = contient_element.id_categorie_element
ORDER BY
    (row_number() OVER ());

CREATE
OR REPLACE VIEW edges AS
SELECT
    poids "weight",
    row_number() OVER () AS "id",
    idele1 "source",
    idele2 "target",
    started "start",
    ended "end",
    gr "gde_reg",
    sr "ss_reg" --, concat('<[',started,', ',ended,']>') as"Interval"
FROM
    (
        SELECT
            r.id_element idele1,
            s.id_element idele2,
            count(*) poids,
            d.post_quem started,
            d.ante_quem ended,
            grande_region.nom_fr gr,
            sous_region.nom_fr sr
        FROM
            contient_element r,
            contient_element s
            LEFT JOIN attestation ON attestation.id = s.id_attestation
            LEFT JOIN source ON source.id = attestation.id_source
            LEFT JOIN localisation ON localisation.id = source.localisation_decouverte_id
            LEFT JOIN grande_region ON grande_region.id = localisation.grande_region_id
            LEFT JOIN sous_region ON sous_region.id = localisation.sous_region_id
            LEFT JOIN datation d ON d.id = source.datation_id
        WHERE
            r.id_element < s.id_element
            AND r.id_attestation = s.id_attestation
        GROUP BY
            r.id_element,
            s.id_element,
            d.post_quem,
            d.ante_quem,
            grande_region.id,
            sous_region.id
        ORDER BY
            1,
            2
    ) AS deux
WHERE
    started IS NOT NULL
    AND ended IS NOT NULL;

CREATE
OR REPLACE VIEW vue_element_categorie AS
SELECT
    element_categorie.id_element,
    categorie_element.nom_fr AS "Catégorie",
    categorie_element.nom_fr AS "Category",
    element_categorie.id_categorie_element AS "Id catégorie élément",
    row_number() OVER () AS "Id lien catégorie élément"
FROM
    element_categorie
    LEFT JOIN categorie_element ON categorie_element.id = element_categorie.id_categorie_element
    LEFT JOIN element e ON e.id = element_categorie.id_element;

CREATE
OR REPLACE VIEW public.vue_element_en AS
SELECT
    element.id AS "Id element",
    element.localisation_id AS "Id Location",
    element.etat_absolu AS "Absolute form",
    element.beta_code AS "Beta Code",
    element.est_localisee AS "Located",
    element.commentaire_fr AS "Commentary",
    nature_element.nom_en AS "Nature",
    string_agg(
        DISTINCT traduction_element.nom_en :: text,
        ', ' :: text
    ) AS "Traduction",
    string_agg(
        DISTINCT categorie_element.nom_en :: text,
        ', ' :: text
    ) AS "Category",
    string_agg(DISTINCT biblio.titre_complet, '/ ' :: text) AS "Bibliography",
    element.date_modification AS "Last modification",
    (
        SELECT
            count(*) AS count
        FROM
            contient_element
        WHERE
            contient_element.id_element = element.id
    ) AS "Number of context",
    concat(
        'https://base-map-polytheisms.huma-num.fr/element/',
        element.id
    ) AS "BD Link"
FROM
    element
    LEFT JOIN nature_element ON nature_element.id = element.id_nature_element
    LEFT JOIN traduction_element ON traduction_element.id_element = element.id
    LEFT JOIN element_categorie ON element_categorie.id_element = element.id
    LEFT JOIN categorie_element ON categorie_element.id = element_categorie.id_categorie_element
    LEFT JOIN element_biblio ON element_biblio.id_element = element.id
    LEFT JOIN biblio ON biblio.id = element_biblio.id_biblio
GROUP BY
    element.id,
    element.localisation_id,
    element.etat_absolu,
    element.beta_code,
    element.est_localisee,
    element.commentaire_en,
    nature_element.nom_en
ORDER BY
    element.id;

CREATE
OR REPLACE VIEW vue_element_fr AS
SELECT
    element.id AS "Id élément",
    element.localisation_id AS "Id Localisation",
    element.etat_absolu AS "Etat absolu",
    element.beta_code AS "Beta Code",
    element.est_localisee AS "Est localisé",
    element.commentaire_fr AS "Commentaire",
    nature_element.nom_fr AS "Nature",
    STRING_AGG(DISTINCT traduction_element.nom_fr, ', ') AS "Traduction",
    STRING_AGG(DISTINCT categorie_element.nom_fr, ', ') AS "Catégorie(s)",
    STRING_AGG(DISTINCT biblio.titre_complet, '/ ') AS "Bibliographie",
    element.date_modification AS "Dernière modification",
    (
        SELECT
            count(*) AS count
        FROM
            contient_element
        WHERE
            contient_element.id_element = element.id
    ) AS "Nombre de fois en contexte",
    concat(
        'https://base-map-polytheisms.huma-num.fr/element/',
        element.id
    ) AS "Lien_BD"
FROM
    element
    LEFT JOIN nature_element ON nature_element.id = element.id_nature_element
    LEFT JOIN traduction_element ON traduction_element.id_element = element.id
    LEFT JOIN element_categorie ON element_categorie.id_element = element.id
    LEFT JOIN categorie_element ON categorie_element.id = element_categorie.id_categorie_element
    LEFT JOIN element_biblio ON element_biblio.id_element = element.id
    LEFT JOIN biblio ON biblio.id = element_biblio.id_biblio
GROUP BY
    element.id,
    element.localisation_id,
    element.etat_absolu,
    element.beta_code,
    element.est_localisee,
    element.commentaire_fr,
    nature_element.nom_fr
ORDER BY
    element.id;

CREATE
OR REPLACE VIEW public.vue_loc_en AS
SELECT
    localisation.id AS "ID Location",
    localisation.reel AS "Real",
    grande_region.id AS "ID Region",
    grande_region.nom_en AS "Region",
    sous_region.id AS "ID Sub-region",
    sous_region.nom_en AS "Sub-region",
    entite_politique.nom_en AS "Polity",
    entite_politique.numero_iacp AS "IACP number",
    localisation.nom_ville AS "Place",
    localisation.latitude,
    localisation.longitude,
    localisation.nom_site AS "Site",
    localisation.commentaire_en AS "Commentary",
    localisation.geom,
    string_agg(DISTINCT q_fonction.nom_en :: text, ', ' :: text) AS "Function",
    string_agg(
        DISTINCT q_topographie.nom_en :: text,
        ', ' :: text
    ) AS "Topography",
    1 AS "Density"
FROM
    localisation
    LEFT JOIN grande_region ON grande_region.id = localisation.grande_region_id
    LEFT JOIN sous_region ON sous_region.id = localisation.sous_region_id
    LEFT JOIN entite_politique ON entite_politique.id = localisation.entite_politique
    LEFT JOIN localisation_q_fonction lqf ON lqf.id_localisation = localisation.id
    LEFT JOIN q_fonction ON q_fonction.id = lqf.id_q_fonction
    LEFT JOIN localisation_q_topographie lqt ON lqt.id_localisation = localisation.id
    LEFT JOIN q_topographie ON q_topographie.id = lqt.id_q_topographie
WHERE
    localisation.grande_region_id IS NOT NULL
GROUP BY
    localisation.id,
    grande_region.id,
    sous_region.id,
    entite_politique.nom_en,
    entite_politique.numero_iacp;

CREATE
OR REPLACE VIEW public.vue_loc_fr AS
SELECT
    localisation.id AS "ID Localisation",
    localisation.reel AS "Réel",
    grande_region.id AS "ID Grande région",
    grande_region.nom_fr AS "Grande région",
    sous_region.id AS "ID Sous-région",
    sous_region.nom_fr AS "Sous-région",
    entite_politique.nom_fr AS "Entité politique",
    entite_politique.numero_iacp AS "Numéro IACP",
    localisation.nom_ville AS "Nom de la ville",
    Localisation.latitude,
    Localisation.longitude,
    localisation.nom_site AS "Nom du site",
    localisation.commentaire_fr AS "Commentaire",
    localisation.geom,
    String_AGG (DISTINCT q_fonction.nom_fr, ', ') AS "Qualification fonctionnelle",
    String_AGG (DISTINCT q_topographie.nom_fr, ', ') AS "Qualification topographique",
    1 AS "Densité"
FROM
    localisation
    LEFT JOIN grande_region ON grande_region.id = localisation.grande_region_id
    LEFT JOIN sous_region ON sous_region.id = localisation.sous_region_id
    LEFT JOIN entite_politique ON entite_politique.id = localisation.entite_politique
    LEFT JOIN localisation_q_fonction lqf ON lqf.id_localisation = localisation.id
    LEFT JOIN q_fonction ON q_fonction.id = lqf.id_q_fonction
    LEFT JOIN localisation_q_topographie lqt ON lqt.id_localisation = localisation.id
    LEFT JOIN q_topographie ON q_topographie.id = lqt.id_q_topographie
WHERE
    localisation.grande_region_id IS NOT NULL
GROUP BY
    localisation.id,
    grande_region.id,
    sous_region.id,
    entite_politique.nom_fr,
    entite_politique.numero_iacp;

CREATE
OR REPLACE VIEW public.vue_materiel_attestation_fr AS
SELECT
    attestation_materiel.quantite AS "Quantité",
    categorie_materiel.nom_fr AS "Categorie de matériel",
    materiel.nom_fr AS "Matériel",
    attestation_materiel.id AS "Id occasion",
    attestation_materiel.id_attestation AS "Id Attestation",
    categorie_materiel.nom_en AS "Category of connected material",
    materiel.nom_en AS "Material"
FROM
    attestation_materiel
    LEFT JOIN categorie_materiel ON categorie_materiel.id = attestation_materiel.id_categorie_materiel
    LEFT JOIN materiel ON materiel.id = attestation_materiel.id_materiel
ORDER BY
    attestation_materiel.id;

CREATE VIEW nodes AS
SELECT
    contient_element.id_element "nodes",
    contient_element.id_element "id",
    element.etat_absolu "label",
    count(*) "weight"
FROM
    contient_element,
    element
WHERE
    contient_element.id_element = element.id
GROUP BY
    contient_element.id_element,
    element.etat_absolu
ORDER BY
    contient_element.id_element;

CREATE
OR REPLACE VIEW public.vue_occasion_attestation_fr AS
SELECT
    categorie_occasion.nom_fr AS "Categorie d'occasion",
    occasion.nom_fr AS "Occasion",
    attestation_occasion.id AS "Id occasion",
    attestation_occasion.id_attestation AS "Id Attestation",
    categorie_occasion.nom_en AS "Category of occasion",
    occasion.nom_en AS "Occasions"
FROM
    attestation_occasion
    LEFT JOIN categorie_occasion ON categorie_occasion.id = attestation_occasion.id_categorie_occasion
    LEFT JOIN occasion ON occasion.id = attestation_occasion.id_occasion
ORDER BY
    attestation_occasion.id;

CREATE
OR REPLACE VIEW public.vue_pratique_attestation_fr AS
SELECT
    attestation_pratique.id_attestation AS "Id de l'attestation",
    pratique.nom_fr AS "Pratique(s)",
    pratique.nom_en AS "Pratique"
FROM
    attestation_pratique
    LEFT JOIN pratique ON pratique.id = attestation_pratique.id_pratique;

CREATE
OR REPLACE VIEW public.vue_source_en AS
SELECT
    source.id,
    titre.nom_en AS "Title",
    type_support.nom_en AS "Type of support",
    categorie_support.nom_en AS "Category of support",
    materiau.nom_en AS "Material",
    categorie_materiau.nom_en AS "Category of material",
    categorie_source.nom_en AS "Category of source",
    source.datation_id,
    source.url_texte AS "URL text",
    source.iconographie AS "Iconography",
    source.url_image AS "URL image",
    source.in_situ AS "In situ",
    source.fiabilite_localisation AS "Precision of location",
    source.fiabilite_datation AS "Precision of datation",
    source.commentaire_en AS "Commentary",
    source.localisation_decouverte_id,
    source.localisation_origine_id,
    source.est_datee AS "Dated",
    projet.nom_en AS "Project",
    datation.post_quem AS "Post Quem",
    datation.ante_quem AS "Ante Quem",
    datation.commentaire_en AS "Commentary Datation",
    (
        SELECT
            count(*) AS count
        FROM
            attestation
        WHERE
            source.id = attestation.id_source
    ) AS "Number of testimony",
    concat(
        'https://base-map-polytheisms.huma-num.fr/source/',
        source.id
    ) AS "BD Link source",
    string_agg(DISTINCT l.nom_en :: text, ', ' :: text) AS "Language",
    string_agg(DISTINCT a.nom_en :: text, ', ' :: text) AS "Author",
    string_agg(DISTINCT ts.nom_en :: text, ', ' :: text) AS "Type of source",
    b.titre_complet AS "Main edition",
    sb.reference_source AS "Quotation",
    concat(
        'https://base-map-polytheisms.huma-num.fr/bibliography/',
        sb.id_biblio
    ) AS "BD Link bibliography",
    source.date_modification AS "Last modification",
    b.titre_abrege AS "Short main edition"
FROM
    source
    LEFT JOIN categorie_source ON categorie_source.id = source.categorie_source_id
    LEFT JOIN categorie_materiau ON categorie_materiau.id = source.categorie_materiau_id
    LEFT JOIN materiau ON materiau.id = source.materiau_id
    LEFT JOIN categorie_support ON categorie_support.id = source.categorie_support_id
    LEFT JOIN type_support ON type_support.id = source.type_support_id
    LEFT JOIN projet ON projet.id = source.id_projet
    LEFT JOIN datation ON datation.id = source.datation_id
    LEFT JOIN titre ON titre.id = source.titre_principal_id
    LEFT JOIN source_langue sl ON sl.id_source = source.id
    LEFT JOIN langue l ON l.id = sl.id_langue
    LEFT JOIN source_auteur sa ON sa.id_source = source.id
    LEFT JOIN auteur a ON a.id = sa.id_auteur
    LEFT JOIN source_type_source sts ON sts.id_source = source.id
    LEFT JOIN type_source ts ON ts.id = sts.id_type_source
    JOIN source_biblio sb ON sb.id_source = source.id
    LEFT JOIN biblio b ON b.id = sb.id_biblio
WHERE
    sb.edition_principale IS TRUE
GROUP BY
    source.id,
    titre.nom_en,
    type_support.nom_en,
    categorie_support.nom_en,
    materiau.nom_en,
    categorie_materiau.nom_en,
    categorie_source.nom_en,
    projet.nom_en,
    datation.post_quem,
    datation.ante_quem,
    datation.commentaire_en,
    sb.id_biblio,
    b.titre_complet,
    sb.reference_source,
    b.titre_abrege;

CREATE
OR REPLACE VIEW public.vue_source_fr AS
SELECT
    source.id,
    titre.nom_fr AS "titre_principal_id",
    type_support.nom_fr AS "Type de support",
    categorie_support.nom_fr AS "Catégorie de support",
    materiau.nom_fr AS "Matériau",
    categorie_materiau.nom_fr AS "Catégorie de matériau",
    categorie_source.nom_fr AS "Catégorie de source",
    source.datation_id,
    source.url_texte AS "URL du texte",
    source.iconographie AS "Iconographie",
    source.url_image AS "URL de l'image",
    source.in_situ AS "In situ",
    source.fiabilite_localisation AS "Précision localisation",
    source.fiabilite_datation AS "Précision datation",
    source.commentaire_fr AS "Commentaire",
    source.localisation_decouverte_id,
    source.localisation_origine_id,
    source.est_datee AS "Est datée",
    projet.nom_fr AS "Projet",
    datation.post_quem AS "Post Quem",
    datation.ante_quem AS "Ante Quem",
    datation.commentaire_fr AS "Commentaire Datation",
    (
        SELECT
            count(*)
        FROM
            attestation
        WHERE
            source.id = attestation.id_source
    ) AS "Nombre attestation",
    concat(
        'https://base-map-polytheisms.huma-num.fr/source/',
        source.id
    ),
    String_AGG(DISTINCT l.nom_fr, ', ') AS "Langue",
    String_AGG(DISTINCT a.nom_fr, ', ') AS "Auteur",
    String_AGG(DISTINCT ts.nom_fr, ', ') AS "Type de source",
    b.titre_complet AS "Source principale",
    sb.reference_source AS "Référence",
    concat(
        'https://base-map-polytheisms.huma-num.fr/bibliography/',
        sb.id_biblio
    ) AS "Lien vers bibliographie",
    source.date_modification AS "Dernière modification",
    b.titre_abrege AS "Source principale abrégée"
FROM
    source
    LEFT JOIN categorie_source ON categorie_source.id = source.categorie_source_id
    LEFT JOIN categorie_materiau ON categorie_materiau.id = source.categorie_materiau_id
    LEFT JOIN materiau ON materiau.id = source.materiau_id
    LEFT JOIN categorie_support ON categorie_support.id = source.categorie_support_id
    LEFT JOIN type_support ON type_support.id = source.type_support_id
    LEFT JOIN projet ON projet.id = source.id_projet
    LEFT JOIN datation ON datation.id = source.datation_id
    LEFT JOIN titre ON titre.id = source.titre_principal_id
    LEFT JOIN source_langue sl ON sl.id_source = source.id
    LEFT JOIN langue l ON l.id = sl.id_langue
    LEFT JOIN source_auteur sa ON sa.id_source = source.id
    LEFT JOIN auteur a ON a.id = sa.id_auteur
    LEFT JOIN source_type_source sts ON sts.id_source = source.id
    LEFT JOIN type_source ts ON ts.id = sts.id_type_source
    INNER JOIN source_biblio sb ON sb.id_source = source.id
    LEFT JOIN biblio b ON b.id = sb.id_biblio
WHERE
    sb.edition_principale IS TRUE
GROUP BY
    source.id,
    titre.nom_fr,
    type_support.nom_fr,
    categorie_support.nom_fr,
    materiau.nom_fr,
    categorie_materiau.nom_fr,
    categorie_source.nom_fr,
    projet.nom_fr,
    datation.post_quem,
    datation.ante_quem,
    datation.commentaire_fr,
    sb.id_biblio,
    b.titre_complet,
    sb.reference_source,
    b.titre_abrege;

CREATE
OR REPLACE VIEW vue_source_langue AS
SELECT
    source_langue.id_source AS "Id Source",
    langue.nom_fr AS "Langue",
    langue.nom_en AS "Language"
FROM
    source_langue
    LEFT JOIN langue ON langue.id = source_langue.id_langue;

CREATE
OR REPLACE VIEW vue_source_typologie AS
SELECT
    source.id AS "Id source",
    type_source.nom_fr AS "Type de source",
    type_source.nom_en AS "Type of source",
    categorie_source.nom_fr AS "Catégorie de source",
    categorie_source.nom_en AS "Category of source"
FROM
    source
    LEFT JOIN source_type_source ON source_type_source.id_source = source.id
    LEFT JOIN type_source ON source_type_source.id_type_source = type_source.id
    LEFT JOIN categorie_source ON categorie_source.id = source.categorie_source_id;