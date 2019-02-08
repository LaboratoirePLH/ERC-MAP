
--
-- Data for Name: datation; Type: TABLE DATA; Schema: public; Owner: 3rgo
--

INSERT INTO public.datation (id, post_quem, ante_quem, post_quem_cit, ante_quem_cit, date_anc, com_date, com_date_en, fiab_datation) VALUES (1, 0, 5, NULL, NULL, 'c''est très vieux', 'commentaire FR', NULL, 4);
INSERT INTO public.datation (id, post_quem, ante_quem, post_quem_cit, ante_quem_cit, date_anc, com_date, com_date_en, fiab_datation) VALUES (5, 2, 3, NULL, NULL, NULL, NULL, NULL, 1);
INSERT INTO public.datation (id, post_quem, ante_quem, post_quem_cit, ante_quem_cit, date_anc, com_date, com_date_en, fiab_datation) VALUES (28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO public.datation (id, post_quem, ante_quem, post_quem_cit, ante_quem_cit, date_anc, com_date, com_date_en, fiab_datation) VALUES (29, 10, 20, 30, 40, '50', 'Commentaire 1', 'Comments 1', NULL);

--
-- Data for Name: attestation; Type: TABLE DATA; Schema: public; Owner: 3rgo
--

INSERT INTO public.attestation (id, ref_source, restit_ss, restit_avec, translitt, com_attest, date_ope, version, id_etat, created, source_id, id_loc, com_attest_en, id_datation, create_attest, modif_attest, fiab_attest, prose, poesie, a_traduire, to_translate) VALUES (2, 'p113', 'sans restit', 'avec restit', 'transillumination', 'je commente attestation', '2018-02-10 00:00:00', 2, 1, '2018-02-10 00:00:00', 6, NULL, NULL, NULL, 1, 1, NULL, true, NULL, NULL, NULL);

--
-- Data for Name: agent; Type: TABLE DATA; Schema: public; Owner: 3rgo
--

INSERT INTO public.agent (id, designation, com_agent, id_attest, id_loc, com_agent_en) VALUES (12, 'Antoine', 'commentaire agent', 2, NULL, 'really me?');
INSERT INTO public.agent (id, designation, com_agent, id_attest, id_loc, com_agent_en) VALUES (14, 'Mon frère ', 'c''est mon frère', 2, NULL, 'oh my god!');
INSERT INTO public.agent (id, designation, com_agent, id_attest, id_loc, com_agent_en) VALUES (15, 'James bond', 'mon nom est', NULL, NULL, 'my name is');
INSERT INTO public.agent (id, designation, com_agent, id_attest, id_loc, com_agent_en) VALUES (13, 'Jean-Baptiste', 'commentaire JB', 2, NULL, 'really you??');

--
-- Data for Name: elements; Type: TABLE DATA; Schema: public; Owner: 3rgo
--

INSERT INTO public.elements (id, etat_abs, etat_morpho, com_elt, id_loc, com_elt_en, id_nature, beta_code, a_traduire, to_translate) VALUES (1, 'zeus', 'zeus', 'c''est un dieu', NULL, 'Commentaire EN', NULL, NULL, NULL, NULL);
INSERT INTO public.elements (id, etat_abs, etat_morpho, com_elt, id_loc, com_elt_en, id_nature, beta_code, a_traduire, to_translate) VALUES (3, 'Hera', 'Hera le retour', 'Fr', NULL, 'En', NULL, NULL, NULL, NULL);

--
-- Data for Name: titre; Type: TABLE DATA; Schema: public; Owner: 3rgo
--

INSERT INTO public.titre (id_titre, nom, name) VALUES (1, 'joli titre', 'nice tittle');
INSERT INTO public.titre (id_titre, nom, name) VALUES (2, 'un nouveau titre', 'one more title');
INSERT INTO public.titre (id_titre, nom, name) VALUES (4, 'Test titre 1zz', 'Test title 1zz');


--
-- Data for Name: a_ecrit; Type: TABLE DATA; Schema: public; Owner: 3rgo
--

INSERT INTO public.a_ecrit (id_titre, id_auteur) VALUES (4, 5);


--
-- Data for Name: a_langue; Type: TABLE DATA; Schema: public; Owner: 3rgo
--

INSERT INTO public.a_langue (id_langue, id_source) VALUES (14, 40);
INSERT INTO public.a_langue (id_langue, id_source) VALUES (7, 41);
INSERT INTO public.a_langue (id_langue, id_source) VALUES (14, 41);
INSERT INTO public.a_langue (id_langue, id_source) VALUES (8, 43);
INSERT INTO public.a_langue (id_langue, id_source) VALUES (20, 910);


--
-- Data for Name: biblio; Type: TABLE DATA; Schema: public; Owner: 3rgo
--

INSERT INTO public.biblio (id, titre_abr, titre_com, corpus_id, annee, auteur_biblio) VALUES (7, 'C''est montiter', 'Jean-Pierre, C''est montiter (2007)', 1, 2007, 'Jean-Pierre');
INSERT INTO public.biblio (id, titre_abr, titre_com, corpus_id, annee, auteur_biblio) VALUES (8, 'Euryopa est grand', 'Map team, Euryopa est grand (2018)', 1, 2018, 'Map team');
INSERT INTO public.biblio (id, titre_abr, titre_com, corpus_id, annee, auteur_biblio) VALUES (10, 'Mon joli bateau', 'Bruno Mars, Mon joli bateau (2010)', 1, 2010, 'Bruno Mars');
INSERT INTO public.biblio (id, titre_abr, titre_com, corpus_id, annee, auteur_biblio) VALUES (11, 'Mon joli corpus', 'Mon joli corpus (2), P. 163', 2, 0, '');
INSERT INTO public.biblio (id, titre_abr, titre_com, corpus_id, annee, auteur_biblio) VALUES (12, 'joli titre', 'joli titre (1), p. 270 n°74', 1, 0, 'yann');
INSERT INTO public.biblio (id, titre_abr, titre_com, corpus_id, annee, auteur_biblio) VALUES (13, 'Titre super abrégé', 'Jean - Michel, Titre super abrégé 2 (2020)', 1, 2020, 'Jean - Michel');

--
-- Data for Name: source; Type: TABLE DATA; Schema: public; Owner: 3rgo
--

INSERT INTO public.source (id, citation, url_texte, url_image, in_situ, fiab_loc, com_source, date_ope, version, id_mat, id_support, id_type_cat, id_typo, id_titre, created, com_source_en, id_datation, id_cat_support, id_cat_mat, create_source, modif_source, a_traduire, to_translate) VALUES (12, true, 'encore une', 'une autre', false, 1, 'du commentaire ici?', '2018-11-12 11:39:26', 2, 1, 1, 3, 1, 1, '2018-11-02 17:17:13', NULL, 1, 1, 1, 1, 1, NULL, NULL);
INSERT INTO public.source (id, citation, url_texte, url_image, in_situ, fiab_loc, com_source, date_ope, version, id_mat, id_support, id_type_cat, id_typo, id_titre, created, com_source_en, id_datation, id_cat_support, id_cat_mat, create_source, modif_source, a_traduire, to_translate) VALUES (28, false, '', '', false, 1, '', '2018-11-02 19:32:34', 1, 1, 1, 2, 1, 1, '2018-11-02 19:32:34', NULL, 1, 3, 1, 1, 1, NULL, NULL);
INSERT INTO public.source (id, citation, url_texte, url_image, in_situ, fiab_loc, com_source, date_ope, version, id_mat, id_support, id_type_cat, id_typo, id_titre, created, com_source_en, id_datation, id_cat_support, id_cat_mat, create_source, modif_source, a_traduire, to_translate) VALUES (26, false, '''l''adresse du site est''', '', false, 1, '', '2018-11-02 19:32:42', 1, 1, 1, 4, 1, 1, '2018-11-02 19:32:42', NULL, 1, 2, 1, 2, 1, NULL, NULL);
INSERT INTO public.source (id, citation, url_texte, url_image, in_situ, fiab_loc, com_source, date_ope, version, id_mat, id_support, id_type_cat, id_typo, id_titre, created, com_source_en, id_datation, id_cat_support, id_cat_mat, create_source, modif_source, a_traduire, to_translate) VALUES (27, false, '', '', false, 1, 'commente ta source', '2018-11-05 09:05:37', 1, 1, 1, 3, 1, 1, '2018-11-05 09:05:37', NULL, 1, 4, 1, 3, 1, NULL, NULL);
INSERT INTO public.source (id, citation, url_texte, url_image, in_situ, fiab_loc, com_source, date_ope, version, id_mat, id_support, id_type_cat, id_typo, id_titre, created, com_source_en, id_datation, id_cat_support, id_cat_mat, create_source, modif_source, a_traduire, to_translate) VALUES (9, false, 'mon url texte', 'mon url image', false, 1, 'fsefs', '2018-11-05 11:08:27', 1, 1, 1, 1, 1, 1, '2018-11-05 11:08:27', NULL, 1, 2, 1, 3, 1, NULL, NULL);
INSERT INTO public.source (id, citation, url_texte, url_image, in_situ, fiab_loc, com_source, date_ope, version, id_mat, id_support, id_type_cat, id_typo, id_titre, created, com_source_en, id_datation, id_cat_support, id_cat_mat, create_source, modif_source, a_traduire, to_translate) VALUES (41, false, '', '', false, 1, '', '2018-11-16 19:55:59', 1, 2, 2, 4, 1, 1, '2018-11-16 19:55:59', NULL, 1, 1, 1, 5, 1, NULL, NULL);
INSERT INTO public.source (id, citation, url_texte, url_image, in_situ, fiab_loc, com_source, date_ope, version, id_mat, id_support, id_type_cat, id_typo, id_titre, created, com_source_en, id_datation, id_cat_support, id_cat_mat, create_source, modif_source, a_traduire, to_translate) VALUES (43, true, '', '', true, 1, NULL, '2018-11-20 11:14:26', 1, 4, 123, 2, 36, 2, '2018-11-20 11:14:26', '', NULL, 6, 4, 1, 2, NULL, NULL);
INSERT INTO public.source (id, citation, url_texte, url_image, in_situ, fiab_loc, com_source, date_ope, version, id_mat, id_support, id_type_cat, id_typo, id_titre, created, com_source_en, id_datation, id_cat_support, id_cat_mat, create_source, modif_source, a_traduire, to_translate) VALUES (6, false, 'map-polytheisms.huma-num.fr', 'map-polytheisms.huma-num.fr', false, 1, 'c''est une belle source', '2018-11-09 11:43:43', 2, 49, 1, 1, 1, 1, '2018-11-05 14:10:02', NULL, 1, 1, 1, 2, 1, NULL, NULL);
INSERT INTO public.source (id, citation, url_texte, url_image, in_situ, fiab_loc, com_source, date_ope, version, id_mat, id_support, id_type_cat, id_typo, id_titre, created, com_source_en, id_datation, id_cat_support, id_cat_mat, create_source, modif_source, a_traduire, to_translate) VALUES (910, true, 'http://www.text.url', 'http://www.image.url', true, NULL, 'Commentaire 1', '2019-02-07 17:12:06', 8, 40, 28, NULL, 48, 4, '2019-02-07 16:16:29', 'Comments 1', 29, NULL, NULL, 9, 9, false, true);


--
-- Data for Name: ecrit; Type: TABLE DATA; Schema: public; Owner: 3rgo
--

INSERT INTO public.ecrit (id_source, id_auteur, auteur_citant) VALUES (43, 52, false);
INSERT INTO public.ecrit (id_source, id_auteur, auteur_citant) VALUES (910, 6, false);

--
-- Data for Name: reference_elts; Type: TABLE DATA; Schema: public; Owner: 3rgo
--

INSERT INTO public.reference_elts (id_elt, id_elt_1, theo_ss_entendu, const_sur_theo) VALUES (1, 3, false, true);


--
-- Data for Name: titre_cite; Type: TABLE DATA; Schema: public; Owner: 3rgo
--

INSERT INTO public.titre_cite (id_source, id_titre) VALUES (910, 2);

--
-- Data for Name: trad_elt; Type: TABLE DATA; Schema: public; Owner: 3rgo
--

INSERT INTO public.trad_elt (id_trad_elt, nom, name, id_elt) VALUES (1, 'Méga dieu', 'Puissant god', 1);


