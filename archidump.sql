--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.10
-- Dumped by pg_dump version 9.6.10

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;
SET default_tablespace = '';
SET default_with_oids = false;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner:
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner:
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: postgis; Type: EXTENSION; Schema: -; Owner:
--

CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA public;


--
-- Name: EXTENSION postgis; Type: COMMENT; Schema: -; Owner:
--

COMMENT ON EXTENSION postgis IS 'PostGIS geometry, geography, and raster spatial types and functions';


--
-- Name: a_agentivite; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.a_agentivite (
    id_agentivite smallint NOT NULL,
    id_agent integer NOT NULL
);


ALTER TABLE public.a_agentivite OWNER TO "3rgo";

--
-- Name: a_agnetivite_id_agent_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_agnetivite_id_agent_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_agnetivite_id_agent_seq OWNER TO "3rgo";

--
-- Name: a_agnetivite_id_agent_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.a_agnetivite_id_agent_seq OWNED BY public.a_agentivite.id_agent;


--
-- Name: a_cat_occasion; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.a_cat_occasion (
    id_cat_occ smallint NOT NULL,
    id integer NOT NULL
);


ALTER TABLE public.a_cat_occasion OWNER TO "3rgo";

--
-- Name: a_cat_occasion_id_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_cat_occasion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_cat_occasion_id_seq OWNER TO "3rgo";

--
-- Name: a_cat_occasion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.a_cat_occasion_id_seq OWNED BY public.a_cat_occasion.id;


--
-- Name: a_catgeorie; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.a_catgeorie (
    id_cat_elt smallint NOT NULL,
    id_elt integer NOT NULL
);


ALTER TABLE public.a_catgeorie OWNER TO "3rgo";

--
-- Name: a_catgeorie_id_elt_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_catgeorie_id_elt_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_catgeorie_id_elt_seq OWNER TO "3rgo";

--
-- Name: a_catgeorie_id_elt_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.a_catgeorie_id_elt_seq OWNED BY public.a_catgeorie.id_elt;


--
-- Name: a_ecrit; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.a_ecrit (
    id_titre integer NOT NULL,
    id_auteur integer NOT NULL
);


ALTER TABLE public.a_ecrit OWNER TO "3rgo";

--
-- Name: a_ecrit_id_auteur_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_ecrit_id_auteur_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_ecrit_id_auteur_seq OWNER TO "3rgo";

--
-- Name: a_ecrit_id_auteur_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.a_ecrit_id_auteur_seq OWNED BY public.a_ecrit.id_auteur;


--
-- Name: a_ecrit_id_titre_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_ecrit_id_titre_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_ecrit_id_titre_seq OWNER TO "3rgo";

--
-- Name: a_ecrit_id_titre_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.a_ecrit_id_titre_seq OWNED BY public.a_ecrit.id_titre;


--
-- Name: a_fonc; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.a_fonc (
    id_fonc smallint NOT NULL,
    id_loc integer NOT NULL
);


ALTER TABLE public.a_fonc OWNER TO "3rgo";

--
-- Name: a_fonc_id_loc_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_fonc_id_loc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_fonc_id_loc_seq OWNER TO "3rgo";

--
-- Name: a_fonc_id_loc_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.a_fonc_id_loc_seq OWNED BY public.a_fonc.id_loc;


--
-- Name: a_langue; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.a_langue (
    id_langue smallint NOT NULL,
    id_source integer NOT NULL
);


ALTER TABLE public.a_langue OWNER TO "3rgo";

--
-- Name: a_langue_id_source_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_langue_id_source_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_langue_id_source_seq OWNER TO "3rgo";

--
-- Name: a_langue_id_source_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.a_langue_id_source_seq OWNED BY public.a_langue.id_source;


--
-- Name: a_mat; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.a_mat (
    id_mat smallint NOT NULL,
    nom character varying(100),
    name character varying(100),
    id_type smallint NOT NULL
);


ALTER TABLE public.a_mat OWNER TO "3rgo";

--
-- Name: a_mat_attest; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.a_mat_attest (
    quantite integer,
    id_attest integer NOT NULL,
    id_mat smallint NOT NULL
);


ALTER TABLE public.a_mat_attest OWNER TO "3rgo";

--
-- Name: a_mat_attest_id_attest_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_mat_attest_id_attest_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_mat_attest_id_attest_seq OWNER TO "3rgo";

--
-- Name: a_mat_attest_id_attest_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.a_mat_attest_id_attest_seq OWNED BY public.a_mat_attest.id_attest;


--
-- Name: a_matx_id_mat_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_matx_id_mat_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_matx_id_mat_seq OWNER TO "3rgo";

--
-- Name: a_matx; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.a_matx (
    id_matx integer DEFAULT nextval('public.a_matx_id_mat_seq'::regclass) NOT NULL,
    nom character varying(100),
    name character varying(100),
    id_cat_matx smallint NOT NULL
);


ALTER TABLE public.a_matx OWNER TO "3rgo";

--
-- Name: a_occasion; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.a_occasion (
    id_occ smallint NOT NULL,
    id_attest integer NOT NULL
);


ALTER TABLE public.a_occasion OWNER TO "3rgo";

--
-- Name: a_occasion_id_attest_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_occasion_id_attest_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_occasion_id_attest_seq OWNER TO "3rgo";

--
-- Name: a_occasion_id_attest_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.a_occasion_id_attest_seq OWNED BY public.a_occasion.id_attest;


--
-- Name: a_pratique; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.a_pratique (
    id_pratique smallint NOT NULL,
    id_attest integer NOT NULL
);


ALTER TABLE public.a_pratique OWNER TO "3rgo";

--
-- Name: a_pratique_id_attest_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_pratique_id_attest_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_pratique_id_attest_seq OWNER TO "3rgo";

--
-- Name: a_pratique_id_attest_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.a_pratique_id_attest_seq OWNED BY public.a_pratique.id_attest;


--
-- Name: a_statut; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.a_statut (
    id_statut integer NOT NULL,
    id_agent integer NOT NULL
);


ALTER TABLE public.a_statut OWNER TO "3rgo";

--
-- Name: a_statut_id_agent_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_statut_id_agent_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_statut_id_agent_seq OWNER TO "3rgo";

--
-- Name: a_statut_id_agent_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.a_statut_id_agent_seq OWNED BY public.a_statut.id_agent;


--
-- Name: a_statut_id_statut_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_statut_id_statut_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_statut_id_statut_seq OWNER TO "3rgo";

--
-- Name: a_statut_id_statut_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.a_statut_id_statut_seq OWNED BY public.a_statut.id_statut;


--
-- Name: a_topo; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.a_topo (
    id_topo smallint NOT NULL,
    id_loc integer NOT NULL
);


ALTER TABLE public.a_topo OWNER TO "3rgo";

--
-- Name: a_topo_id_loc_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.a_topo_id_loc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.a_topo_id_loc_seq OWNER TO "3rgo";

--
-- Name: a_topo_id_loc_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.a_topo_id_loc_seq OWNED BY public.a_topo.id_loc;


--
-- Name: activite_agent; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.activite_agent (
    id_activite smallint NOT NULL,
    nom character varying(50),
    name character varying(50)
);


ALTER TABLE public.activite_agent OWNER TO "3rgo";

--
-- Name: agent; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.agent (
    id integer NOT NULL,
    designation text,
    com_agent text,
    id_attest integer,
    id_loc integer,
    com_agent_en text
);


ALTER TABLE public.agent OWNER TO "3rgo";

--
-- Name: agent_activite; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.agent_activite (
    id_activite smallint NOT NULL,
    id_agent integer NOT NULL
);


ALTER TABLE public.agent_activite OWNER TO "3rgo";

--
-- Name: agent_activite_id_agent_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.agent_activite_id_agent_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.agent_activite_id_agent_seq OWNER TO "3rgo";

--
-- Name: agent_activite_id_agent_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.agent_activite_id_agent_seq OWNED BY public.agent_activite.id_agent;


--
-- Name: agent_genre; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.agent_genre (
    id_genre smallint NOT NULL,
    id_agent integer NOT NULL
);


ALTER TABLE public.agent_genre OWNER TO "3rgo";

--
-- Name: agent_genre_id_agent_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.agent_genre_id_agent_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.agent_genre_id_agent_seq OWNER TO "3rgo";

--
-- Name: agent_genre_id_agent_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.agent_genre_id_agent_seq OWNED BY public.agent_genre.id_agent;


--
-- Name: agent_id_agent_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.agent_id_agent_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.agent_id_agent_seq OWNER TO "3rgo";

--
-- Name: agent_id_agent_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.agent_id_agent_seq OWNED BY public.agent.id;


--
-- Name: agent_id_attest_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.agent_id_attest_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.agent_id_attest_seq OWNER TO "3rgo";

--
-- Name: agent_id_attest_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.agent_id_attest_seq OWNED BY public.agent.id_attest;


--
-- Name: agent_nature; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.agent_nature (
    id_nat smallint NOT NULL,
    id_agent integer NOT NULL
);


ALTER TABLE public.agent_nature OWNER TO "3rgo";

--
-- Name: agent_nature_id_agent_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.agent_nature_id_agent_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.agent_nature_id_agent_seq OWNER TO "3rgo";

--
-- Name: agent_nature_id_agent_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.agent_nature_id_agent_seq OWNED BY public.agent_nature.id_agent;


--
-- Name: agentivite; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.agentivite (
    id_agentivite smallint NOT NULL,
    nom character varying(50),
    name character varying(50)
);


ALTER TABLE public.agentivite OWNER TO "3rgo";

--
-- Name: attestation; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.attestation (
    id integer NOT NULL,
    ref_source character varying(255),
    restit_ss text,
    restit_avec text,
    translitt text,
    com_attest text,
    date_ope timestamp without time zone NOT NULL,
    version integer NOT NULL,
    id_etat smallint,
    created timestamp without time zone NOT NULL,
    source_id integer,
    id_loc integer,
    com_attest_en text,
    id_datation integer,
    create_attest smallint DEFAULT 1 NOT NULL,
    modif_attest smallint,
    fiab_attest smallint,
    prose boolean DEFAULT true,
    poesie boolean,
    a_traduire boolean,
    to_translate boolean
);


ALTER TABLE public.attestation OWNER TO "3rgo";

--
-- Name: attestation_id_attest_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.attestation_id_attest_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.attestation_id_attest_seq OWNER TO "3rgo";

--
-- Name: attestation_id_attest_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.attestation_id_attest_seq OWNED BY public.attestation.id;


--
-- Name: attestation_version_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.attestation_version_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.attestation_version_seq OWNER TO "3rgo";

--
-- Name: attestation_version_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.attestation_version_seq OWNED BY public.attestation.version;


--
-- Name: auteur; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.auteur (
    id_auteur integer NOT NULL,
    nom character varying(255),
    name character varying(255)
);


ALTER TABLE public.auteur OWNER TO "3rgo";

--
-- Name: auteur_id_auteur_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.auteur_id_auteur_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.auteur_id_auteur_seq OWNER TO "3rgo";

--
-- Name: auteur_id_auteur_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.auteur_id_auteur_seq OWNED BY public.auteur.id_auteur;


--
-- Name: biblio; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.biblio (
    id integer NOT NULL,
    titre_abr character varying(255),
    titre_com text,
    corpus_id integer,
    annee smallint,
    auteur_biblio character varying(255),
    CONSTRAINT annee_controle CHECK (((annee > '-2000'::integer) AND (annee < 2100)))
);


ALTER TABLE public.biblio OWNER TO "3rgo";

--
-- Name: COLUMN biblio.corpus_id; Type: COMMENT; Schema: public; Owner: 3rgo
--

COMMENT ON COLUMN public.biblio.corpus_id IS 'fkey';


--
-- Name: biblio_id_biblio_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.biblio_id_biblio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.biblio_id_biblio_seq OWNER TO "3rgo";

--
-- Name: biblio_id_biblio_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.biblio_id_biblio_seq OWNED BY public.biblio.id;


--
-- Name: cat_mat; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.cat_mat (
    id smallint NOT NULL,
    nom character varying(100),
    name character varying(100)
);


ALTER TABLE public.cat_mat OWNER TO "3rgo";

--
-- Name: cat_matx; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.cat_matx (
    id_cat_matx smallint NOT NULL,
    nom character varying(100),
    name character varying(100)
);


ALTER TABLE public.cat_matx OWNER TO "3rgo";

--
-- Name: cat_occasion; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.cat_occasion (
    id_cat_occ smallint NOT NULL,
    nom character varying(100),
    name character varying(100)
);


ALTER TABLE public.cat_occasion OWNER TO "3rgo";

--
-- Name: cat_support; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.cat_support (
    id smallint NOT NULL,
    nom character varying(100),
    name character varying(100)
);


ALTER TABLE public.cat_support OWNER TO "3rgo";

--
-- Name: categorie_elt; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.categorie_elt (
    id_cat_elt smallint NOT NULL,
    nom character varying(50),
    name character varying(50)
);


ALTER TABLE public.categorie_elt OWNER TO "3rgo";

--
-- Name: categorie_source; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.categorie_source (
    id smallint NOT NULL,
    nom character varying(50),
    name character varying(50)
);


ALTER TABLE public.categorie_source OWNER TO "3rgo";

--
-- Name: chercheur; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.chercheur (
    id integer NOT NULL,
    prenom_nom character varying(255) NOT NULL,
    username character varying(150) NOT NULL,
    institution character varying(250),
    mail character varying(250),
    password character varying(250),
    date_ajout date,
    role character varying(50)
);


ALTER TABLE public.chercheur OWNER TO "3rgo";

--
-- Name: chercheur_id_chercheur_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.chercheur_id_chercheur_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.chercheur_id_chercheur_seq OWNER TO "3rgo";

--
-- Name: chercheur_id_chercheur_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.chercheur_id_chercheur_seq OWNED BY public.chercheur.id;


--
-- Name: contient_elt; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.contient_elt (
    id_elt integer NOT NULL,
    id_attest integer NOT NULL,
    position_elt smallint NOT NULL,
    id_nombre smallint,
    id_genre smallint,
    suffixe boolean,
    restit_ss character varying(255),
    restit_avec character varying(255),
    id_categorie_elt smallint
);


ALTER TABLE public.contient_elt OWNER TO "3rgo";

--
-- Name: contient_elt_id_attest_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.contient_elt_id_attest_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contient_elt_id_attest_seq OWNER TO "3rgo";

--
-- Name: contient_elt_id_attest_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.contient_elt_id_attest_seq OWNED BY public.contient_elt.id_attest;


--
-- Name: contient_elt_id_elt_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.contient_elt_id_elt_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contient_elt_id_elt_seq OWNER TO "3rgo";

--
-- Name: contient_elt_id_elt_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.contient_elt_id_elt_seq OWNED BY public.contient_elt.id_elt;


--
-- Name: corpus; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.corpus (
    id integer NOT NULL,
    nom character varying(255),
    nom_complet text
);


ALTER TABLE public.corpus OWNER TO "3rgo";

--
-- Name: corpus_id_corpus_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.corpus_id_corpus_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.corpus_id_corpus_seq OWNER TO "3rgo";

--
-- Name: corpus_id_corpus_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.corpus_id_corpus_seq OWNED BY public.corpus.id;


--
-- Name: datation; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.datation (
    id integer NOT NULL,
    post_quem smallint,
    ante_quem smallint,
    post_quem_cit smallint,
    ante_quem_cit smallint,
    date_anc text,
    com_date text,
    com_date_en text,
    fiab_datation smallint,
    CONSTRAINT "ante>=post" CHECK ((ante_quem >= post_quem)),
    CONSTRAINT "antecitant>=post" CHECK ((ante_quem_cit >= post_quem_cit)),
    CONSTRAINT fiab_1_a_4 CHECK ((fiab_datation = ANY (ARRAY['1'::smallint, '2'::smallint, '3'::smallint, '4'::smallint])))
);


ALTER TABLE public.datation OWNER TO "3rgo";

--
-- Name: CONSTRAINT "ante>=post" ON datation; Type: COMMENT; Schema: public; Owner: 3rgo
--

COMMENT ON CONSTRAINT "ante>=post" ON public.datation IS 'La date ante quem est supérieure ou égale à post quem';


--
-- Name: CONSTRAINT "antecitant>=post" ON datation; Type: COMMENT; Schema: public; Owner: 3rgo
--

COMMENT ON CONSTRAINT "antecitant>=post" ON public.datation IS 'Ante quem citant est supérieur ou égal à post quem citant';


--
-- Name: datation_id_datation_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.datation_id_datation_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.datation_id_datation_seq OWNER TO "3rgo";

--
-- Name: datation_id_datation_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.datation_id_datation_seq OWNED BY public.datation.id;


--
-- Name: ecrit; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.ecrit (
    id_source integer NOT NULL,
    id_auteur integer NOT NULL,
    auteur_citant boolean DEFAULT false
);


ALTER TABLE public.ecrit OWNER TO "3rgo";

--
-- Name: ecrit_id_auteur_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.ecrit_id_auteur_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ecrit_id_auteur_seq OWNER TO "3rgo";

--
-- Name: ecrit_id_auteur_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.ecrit_id_auteur_seq OWNED BY public.ecrit.id_auteur;


--
-- Name: ecrit_id_source_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.ecrit_id_source_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ecrit_id_source_seq OWNER TO "3rgo";

--
-- Name: ecrit_id_source_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.ecrit_id_source_seq OWNED BY public.ecrit.id_source;


--
-- Name: elements; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.elements (
    id integer NOT NULL,
    etat_abs character varying(255),
    etat_morpho character varying(255),
    com_elt text,
    id_loc integer,
    com_elt_en text,
    id_nature integer,
    beta_code character varying(255),
    a_traduire boolean,
    to_translate boolean
);


ALTER TABLE public.elements OWNER TO "3rgo";

--
-- Name: elements_id_elt_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.elements_id_elt_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.elements_id_elt_seq OWNER TO "3rgo";

--
-- Name: elements_id_elt_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.elements_id_elt_seq OWNED BY public.elements.id;


--
-- Name: entite_pol; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.entite_pol (
    id integer NOT NULL,
    nom character varying(255),
    name character varying(255),
    num_iacp smallint
);


ALTER TABLE public.entite_pol OWNER TO "3rgo";

--
-- Name: entite_pol_id_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.entite_pol_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.entite_pol_id_seq OWNER TO "3rgo";

--
-- Name: entite_pol_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.entite_pol_id_seq OWNED BY public.entite_pol.id;


--
-- Name: etat_fiche; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.etat_fiche (
    id smallint NOT NULL,
    nom character varying(50),
    name character varying(50)
);


ALTER TABLE public.etat_fiche OWNER TO "3rgo";

--
-- Name: formules; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.formules (
    id_formule integer NOT NULL,
    formule text,
    position_form smallint,
    fiab_form boolean,
    id_attest integer NOT NULL,
    id_chercheur integer NOT NULL
);


ALTER TABLE public.formules OWNER TO "3rgo";

--
-- Name: formules_id_attest_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.formules_id_attest_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.formules_id_attest_seq OWNER TO "3rgo";

--
-- Name: formules_id_attest_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.formules_id_attest_seq OWNED BY public.formules.id_attest;


--
-- Name: formules_id_chercheur_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.formules_id_chercheur_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.formules_id_chercheur_seq OWNER TO "3rgo";

--
-- Name: formules_id_chercheur_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.formules_id_chercheur_seq OWNED BY public.formules.id_chercheur;


--
-- Name: formules_id_formule_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.formules_id_formule_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.formules_id_formule_seq OWNER TO "3rgo";

--
-- Name: formules_id_formule_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.formules_id_formule_seq OWNED BY public.formules.id_formule;


--
-- Name: gde_reg; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.gde_reg (
    gid_reg smallint NOT NULL,
    nom character varying(100),
    name character varying(100),
    geom public.geometry(MultiPolygon,4326),
    id smallint
);


ALTER TABLE public.gde_reg OWNER TO "3rgo";

--
-- Name: genre; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.genre (
    id_genre smallint NOT NULL,
    nom character varying(50),
    name character varying(50)
);


ALTER TABLE public.genre OWNER TO "3rgo";

--
-- Name: genre_elt; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.genre_elt (
    id_genre smallint NOT NULL,
    nom character varying(50),
    name character varying(50)
);


ALTER TABLE public.genre_elt OWNER TO "3rgo";

--
-- Name: langue; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.langue (
    id smallint NOT NULL,
    nom character varying(100),
    name character varying(100)
);


ALTER TABLE public.langue OWNER TO "3rgo";

--
-- Name: lien_loc; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.lien_loc (
    id_loc integer NOT NULL,
    id_source integer NOT NULL,
    type_loc smallint NOT NULL
);


ALTER TABLE public.lien_loc OWNER TO "3rgo";

--
-- Name: lien_loc_id_loc_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.lien_loc_id_loc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lien_loc_id_loc_seq OWNER TO "3rgo";

--
-- Name: lien_loc_id_loc_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.lien_loc_id_loc_seq OWNED BY public.lien_loc.id_loc;


--
-- Name: lien_loc_id_source_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.lien_loc_id_source_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lien_loc_id_source_seq OWNER TO "3rgo";

--
-- Name: lien_loc_id_source_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.lien_loc_id_source_seq OWNED BY public.lien_loc.id_source;


--
-- Name: loc_type; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.loc_type (
    id_loc_type smallint NOT NULL,
    nom character varying(50),
    name character varying(50)
);


ALTER TABLE public.loc_type OWNER TO "3rgo";

--
-- Name: localisation; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.localisation (
    id integer NOT NULL,
    pleiades_ville integer,
    nom_ville character varying(255),
    lat real,
    long real,
    pleiades_site integer,
    nom_site character varying(255),
    reel boolean,
    com_loc text,
    id_ssreg smallint,
    id_reg smallint NOT NULL,
    geom public.geometry(Point,4326),
    com_loc_en text,
    entite_pol integer
);


ALTER TABLE public.localisation OWNER TO "3rgo";

--
-- Name: localisation_id_loc_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.localisation_id_loc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.localisation_id_loc_seq OWNER TO "3rgo";

--
-- Name: localisation_id_loc_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.localisation_id_loc_seq OWNED BY public.localisation.id;


--
-- Name: nature; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.nature (
    id_nat smallint NOT NULL,
    nom character varying(50),
    name character varying(50)
);


ALTER TABLE public.nature OWNER TO "3rgo";

--
-- Name: nature_elt; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.nature_elt (
    id_nature smallint NOT NULL,
    nom character varying(50),
    name character varying(50)
);


ALTER TABLE public.nature_elt OWNER TO "3rgo";

--
-- Name: nombre_elt; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.nombre_elt (
    id_nombre smallint NOT NULL,
    nom character varying(50),
    name character varying(50)
);


ALTER TABLE public.nombre_elt OWNER TO "3rgo";

--
-- Name: occasion; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.occasion (
    id_occ smallint NOT NULL,
    nom character varying(100),
    name character varying(100),
    id_cat_occ smallint NOT NULL
);


ALTER TABLE public.occasion OWNER TO "3rgo";

--
-- Name: pratique; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.pratique (
    id_pratique smallint NOT NULL,
    nom character varying(100),
    name character varying(100)
);


ALTER TABLE public.pratique OWNER TO "3rgo";

--
-- Name: q_fonc; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.q_fonc (
    id smallint NOT NULL,
    nom character varying(100),
    name character varying(100)
);


ALTER TABLE public.q_fonc OWNER TO "3rgo";

--
-- Name: q_topo; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.q_topo (
    id smallint NOT NULL,
    nom character varying(100),
    name character varying(100)
);


ALTER TABLE public.q_topo OWNER TO "3rgo";

--
-- Name: reference_elts; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.reference_elts (
    id_elt integer NOT NULL,
    id_elt_1 integer NOT NULL,
    theo_ss_entendu boolean DEFAULT false,
    const_sur_theo boolean DEFAULT false
);


ALTER TABLE public.reference_elts OWNER TO "3rgo";

--
-- Name: reference_elts_id_elt_1_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.reference_elts_id_elt_1_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.reference_elts_id_elt_1_seq OWNER TO "3rgo";

--
-- Name: reference_elts_id_elt_1_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.reference_elts_id_elt_1_seq OWNED BY public.reference_elts.id_elt_1;


--
-- Name: reference_elts_id_elt_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.reference_elts_id_elt_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.reference_elts_id_elt_seq OWNER TO "3rgo";

--
-- Name: reference_elts_id_elt_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.reference_elts_id_elt_seq OWNED BY public.reference_elts.id_elt;


--
-- Name: sequence_suivi_id; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.sequence_suivi_id
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sequence_suivi_id OWNER TO "3rgo";

--
-- Name: SEQUENCE sequence_suivi_id; Type: COMMENT; Schema: public; Owner: 3rgo
--

COMMENT ON SEQUENCE public.sequence_suivi_id IS 'Sequence sur le champ id de la table suivi';


--
-- Name: source; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.source (
    id integer NOT NULL,
    citation boolean DEFAULT false,
    url_texte text,
    url_image text,
    in_situ boolean DEFAULT false,
    fiab_loc smallint,
    com_source text,
    date_ope timestamp without time zone DEFAULT now() NOT NULL,
    version integer NOT NULL,
    id_mat smallint,
    id_support smallint,
    id_type_cat smallint,
    id_typo smallint NOT NULL,
    id_titre integer,
    created timestamp without time zone NOT NULL,
    com_source_en text,
    id_datation integer,
    id_cat_support integer,
    id_cat_mat integer,
    create_source smallint NOT NULL,
    modif_source smallint,
    a_traduire boolean,
    to_translate boolean
);


ALTER TABLE public.source OWNER TO "3rgo";

--
-- Name: source_id_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.source_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 999999999
    CACHE 1;


ALTER TABLE public.source_id_seq OWNER TO "3rgo";

--
-- Name: source_id_source_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.source_id_source_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.source_id_source_seq OWNER TO "3rgo";

--
-- Name: source_id_source_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.source_id_source_seq OWNED BY public.source.id;


--
-- Name: source_id_titre_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.source_id_titre_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.source_id_titre_seq OWNER TO "3rgo";

--
-- Name: source_id_titre_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.source_id_titre_seq OWNED BY public.source.id_titre;


--
-- Name: source_version_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.source_version_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.source_version_seq OWNER TO "3rgo";

--
-- Name: source_version_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.source_version_seq OWNED BY public.source.version;


--
-- Name: ss_reg; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.ss_reg (
    gid_ssreg smallint NOT NULL,
    nom character varying(100),
    name character varying(100),
    geom public.geometry(Point,4326),
    gid_reg smallint NOT NULL,
    id smallint
);


ALTER TABLE public.ss_reg OWNER TO "3rgo";

--
-- Name: statu_aff; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.statu_aff (
    id integer NOT NULL,
    nom text,
    name text
);


ALTER TABLE public.statu_aff OWNER TO "3rgo";

--
-- Name: statu_aff_id_statu_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.statu_aff_id_statu_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.statu_aff_id_statu_seq OWNER TO "3rgo";

--
-- Name: statu_aff_id_statu_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.statu_aff_id_statu_seq OWNED BY public.statu_aff.id;


--
-- Name: suivi; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.suivi (
    id integer DEFAULT nextval('public.sequence_suivi_id'::regclass) NOT NULL,
    schema character varying(15) NOT NULL,
    nomtable character varying(50) NOT NULL,
    utilisateur character varying(100),
    dateheure timestamp without time zone DEFAULT ('now'::text)::timestamp without time zone NOT NULL,
    action character varying(1) NOT NULL,
    dataorigine text,
    datanouvelle text,
    detailmaj text,
    idobjet integer,
    CONSTRAINT suivi_action_check CHECK (((action)::text = ANY (ARRAY[('I'::character varying)::text, ('D'::character varying)::text, ('U'::character varying)::text])))
);


ALTER TABLE public.suivi OWNER TO "3rgo";

--
-- Name: titre; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.titre (
    id_titre integer NOT NULL,
    nom character varying(255),
    name character varying(255)
);


ALTER TABLE public.titre OWNER TO "3rgo";

--
-- Name: titre_cite; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.titre_cite (
    id_source integer NOT NULL,
    id_titre integer NOT NULL
);


ALTER TABLE public.titre_cite OWNER TO "3rgo";

--
-- Name: titre_cite_id_source_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.titre_cite_id_source_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.titre_cite_id_source_seq OWNER TO "3rgo";

--
-- Name: titre_cite_id_source_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.titre_cite_id_source_seq OWNED BY public.titre_cite.id_source;


--
-- Name: titre_cite_id_titre_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.titre_cite_id_titre_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.titre_cite_id_titre_seq OWNER TO "3rgo";

--
-- Name: titre_cite_id_titre_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.titre_cite_id_titre_seq OWNED BY public.titre_cite.id_titre;


--
-- Name: titre_id_titre_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.titre_id_titre_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.titre_id_titre_seq OWNER TO "3rgo";

--
-- Name: titre_id_titre_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.titre_id_titre_seq OWNED BY public.titre.id_titre;


--
-- Name: trad_elt; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.trad_elt (
    id_trad_elt integer NOT NULL,
    nom character varying(100),
    name character varying(100),
    id_elt integer NOT NULL
);


ALTER TABLE public.trad_elt OWNER TO "3rgo";

--
-- Name: trad_elt_id_elt_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.trad_elt_id_elt_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.trad_elt_id_elt_seq OWNER TO "3rgo";

--
-- Name: trad_elt_id_elt_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.trad_elt_id_elt_seq OWNED BY public.trad_elt.id_elt;


--
-- Name: trad_elt_id_trad_elt_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.trad_elt_id_trad_elt_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.trad_elt_id_trad_elt_seq OWNER TO "3rgo";

--
-- Name: trad_elt_id_trad_elt_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.trad_elt_id_trad_elt_seq OWNED BY public.trad_elt.id_trad_elt;


--
-- Name: trouve_elt; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.trouve_elt (
    id_biblio integer NOT NULL,
    id_elt integer NOT NULL,
    com_bib_fr text,
    com_bib_en text,
    bib_ref_elt character varying(255)
);


ALTER TABLE public.trouve_elt OWNER TO "3rgo";

--
-- Name: trouve_elt_id_biblio_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.trouve_elt_id_biblio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.trouve_elt_id_biblio_seq OWNER TO "3rgo";

--
-- Name: trouve_elt_id_biblio_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.trouve_elt_id_biblio_seq OWNED BY public.trouve_elt.id_biblio;


--
-- Name: trouve_elt_id_elt_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.trouve_elt_id_elt_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.trouve_elt_id_elt_seq OWNER TO "3rgo";

--
-- Name: trouve_elt_id_elt_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.trouve_elt_id_elt_seq OWNED BY public.trouve_elt.id_elt;


--
-- Name: trouve_source; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.trouve_source (
    ed_ppale boolean,
    id_biblio integer NOT NULL,
    id_source integer NOT NULL,
    com_bib_fr text,
    com_bib_en text,
    bib_ref_source character varying(255)
);


ALTER TABLE public.trouve_source OWNER TO "3rgo";

--
-- Name: trouve_source_id_biblio_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.trouve_source_id_biblio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.trouve_source_id_biblio_seq OWNER TO "3rgo";

--
-- Name: trouve_source_id_biblio_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.trouve_source_id_biblio_seq OWNED BY public.trouve_source.id_biblio;


--
-- Name: trouve_source_id_source_seq; Type: SEQUENCE; Schema: public; Owner: 3rgo
--

CREATE SEQUENCE public.trouve_source_id_source_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.trouve_source_id_source_seq OWNER TO "3rgo";

--
-- Name: trouve_source_id_source_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: 3rgo
--

ALTER SEQUENCE public.trouve_source_id_source_seq OWNED BY public.trouve_source.id_source;


--
-- Name: type_source; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.type_source (
    id smallint NOT NULL,
    nom character varying(50),
    name character varying(50),
    id_cat_source smallint
);


ALTER TABLE public.type_source OWNER TO "3rgo";

--
-- Name: type_support; Type: TABLE; Schema: public; Owner: 3rgo
--

CREATE TABLE public.type_support (
    id smallint NOT NULL,
    nom character varying(100),
    name character varying(100),
    id_cat_supp smallint NOT NULL
);


ALTER TABLE public.type_support OWNER TO "3rgo";

--
-- Name: a_agentivite id_agent; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_agentivite ALTER COLUMN id_agent SET DEFAULT nextval('public.a_agnetivite_id_agent_seq'::regclass);


--
-- Name: a_cat_occasion id; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_cat_occasion ALTER COLUMN id SET DEFAULT nextval('public.a_cat_occasion_id_seq'::regclass);


--
-- Name: a_catgeorie id_elt; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_catgeorie ALTER COLUMN id_elt SET DEFAULT nextval('public.a_catgeorie_id_elt_seq'::regclass);


--
-- Name: a_ecrit id_titre; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_ecrit ALTER COLUMN id_titre SET DEFAULT nextval('public.a_ecrit_id_titre_seq'::regclass);


--
-- Name: a_ecrit id_auteur; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_ecrit ALTER COLUMN id_auteur SET DEFAULT nextval('public.a_ecrit_id_auteur_seq'::regclass);


--
-- Name: a_fonc id_loc; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_fonc ALTER COLUMN id_loc SET DEFAULT nextval('public.a_fonc_id_loc_seq'::regclass);


--
-- Name: a_mat_attest id_attest; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_mat_attest ALTER COLUMN id_attest SET DEFAULT nextval('public.a_mat_attest_id_attest_seq'::regclass);


--
-- Name: a_occasion id_attest; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_occasion ALTER COLUMN id_attest SET DEFAULT nextval('public.a_occasion_id_attest_seq'::regclass);


--
-- Name: a_pratique id_attest; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_pratique ALTER COLUMN id_attest SET DEFAULT nextval('public.a_pratique_id_attest_seq'::regclass);


--
-- Name: a_statut id_statut; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_statut ALTER COLUMN id_statut SET DEFAULT nextval('public.a_statut_id_statut_seq'::regclass);


--
-- Name: a_statut id_agent; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_statut ALTER COLUMN id_agent SET DEFAULT nextval('public.a_statut_id_agent_seq'::regclass);


--
-- Name: a_topo id_loc; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_topo ALTER COLUMN id_loc SET DEFAULT nextval('public.a_topo_id_loc_seq'::regclass);


--
-- Name: agent id; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent ALTER COLUMN id SET DEFAULT nextval('public.agent_id_agent_seq'::regclass);


--
-- Name: agent_activite id_agent; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent_activite ALTER COLUMN id_agent SET DEFAULT nextval('public.agent_activite_id_agent_seq'::regclass);


--
-- Name: agent_genre id_agent; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent_genre ALTER COLUMN id_agent SET DEFAULT nextval('public.agent_genre_id_agent_seq'::regclass);


--
-- Name: agent_nature id_agent; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent_nature ALTER COLUMN id_agent SET DEFAULT nextval('public.agent_nature_id_agent_seq'::regclass);


--
-- Name: attestation id; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.attestation ALTER COLUMN id SET DEFAULT nextval('public.attestation_id_attest_seq'::regclass);


--
-- Name: attestation version; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.attestation ALTER COLUMN version SET DEFAULT nextval('public.attestation_version_seq'::regclass);


--
-- Name: auteur id_auteur; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.auteur ALTER COLUMN id_auteur SET DEFAULT nextval('public.auteur_id_auteur_seq'::regclass);


--
-- Name: biblio id; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.biblio ALTER COLUMN id SET DEFAULT nextval('public.biblio_id_biblio_seq'::regclass);


--
-- Name: chercheur id; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.chercheur ALTER COLUMN id SET DEFAULT nextval('public.chercheur_id_chercheur_seq'::regclass);


--
-- Name: contient_elt id_elt; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.contient_elt ALTER COLUMN id_elt SET DEFAULT nextval('public.contient_elt_id_elt_seq'::regclass);


--
-- Name: contient_elt id_attest; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.contient_elt ALTER COLUMN id_attest SET DEFAULT nextval('public.contient_elt_id_attest_seq'::regclass);


--
-- Name: corpus id; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.corpus ALTER COLUMN id SET DEFAULT nextval('public.corpus_id_corpus_seq'::regclass);


--
-- Name: datation id; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.datation ALTER COLUMN id SET DEFAULT nextval('public.datation_id_datation_seq'::regclass);


--
-- Name: ecrit id_source; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.ecrit ALTER COLUMN id_source SET DEFAULT nextval('public.ecrit_id_source_seq'::regclass);


--
-- Name: ecrit id_auteur; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.ecrit ALTER COLUMN id_auteur SET DEFAULT nextval('public.ecrit_id_auteur_seq'::regclass);


--
-- Name: elements id; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.elements ALTER COLUMN id SET DEFAULT nextval('public.elements_id_elt_seq'::regclass);


--
-- Name: entite_pol id; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.entite_pol ALTER COLUMN id SET DEFAULT nextval('public.entite_pol_id_seq'::regclass);


--
-- Name: formules id_formule; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.formules ALTER COLUMN id_formule SET DEFAULT nextval('public.formules_id_formule_seq'::regclass);


--
-- Name: formules id_attest; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.formules ALTER COLUMN id_attest SET DEFAULT nextval('public.formules_id_attest_seq'::regclass);


--
-- Name: formules id_chercheur; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.formules ALTER COLUMN id_chercheur SET DEFAULT nextval('public.formules_id_chercheur_seq'::regclass);


--
-- Name: lien_loc id_loc; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.lien_loc ALTER COLUMN id_loc SET DEFAULT nextval('public.lien_loc_id_loc_seq'::regclass);


--
-- Name: lien_loc id_source; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.lien_loc ALTER COLUMN id_source SET DEFAULT nextval('public.lien_loc_id_source_seq'::regclass);


--
-- Name: localisation id; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.localisation ALTER COLUMN id SET DEFAULT nextval('public.localisation_id_loc_seq'::regclass);


--
-- Name: reference_elts id_elt; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.reference_elts ALTER COLUMN id_elt SET DEFAULT nextval('public.reference_elts_id_elt_seq'::regclass);


--
-- Name: reference_elts id_elt_1; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.reference_elts ALTER COLUMN id_elt_1 SET DEFAULT nextval('public.reference_elts_id_elt_1_seq'::regclass);


--
-- Name: source id; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.source ALTER COLUMN id SET DEFAULT nextval('public.source_id_source_seq'::regclass);


--
-- Name: source version; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.source ALTER COLUMN version SET DEFAULT nextval('public.source_version_seq'::regclass);


--
-- Name: source id_titre; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.source ALTER COLUMN id_titre SET DEFAULT nextval('public.source_id_titre_seq'::regclass);


--
-- Name: titre id_titre; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.titre ALTER COLUMN id_titre SET DEFAULT nextval('public.titre_id_titre_seq'::regclass);


--
-- Name: titre_cite id_source; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.titre_cite ALTER COLUMN id_source SET DEFAULT nextval('public.titre_cite_id_source_seq'::regclass);


--
-- Name: titre_cite id_titre; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.titre_cite ALTER COLUMN id_titre SET DEFAULT nextval('public.titre_cite_id_titre_seq'::regclass);


--
-- Name: trad_elt id_trad_elt; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.trad_elt ALTER COLUMN id_trad_elt SET DEFAULT nextval('public.trad_elt_id_trad_elt_seq'::regclass);


--
-- Name: trad_elt id_elt; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.trad_elt ALTER COLUMN id_elt SET DEFAULT nextval('public.trad_elt_id_elt_seq'::regclass);


--
-- Name: trouve_elt id_biblio; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.trouve_elt ALTER COLUMN id_biblio SET DEFAULT nextval('public.trouve_elt_id_biblio_seq'::regclass);


--
-- Name: trouve_elt id_elt; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.trouve_elt ALTER COLUMN id_elt SET DEFAULT nextval('public.trouve_elt_id_elt_seq'::regclass);


--
-- Name: trouve_source id_biblio; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.trouve_source ALTER COLUMN id_biblio SET DEFAULT nextval('public.trouve_source_id_biblio_seq'::regclass);


--
-- Name: trouve_source id_source; Type: DEFAULT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.trouve_source ALTER COLUMN id_source SET DEFAULT nextval('public.trouve_source_id_source_seq'::regclass);


--
-- Name: a_agentivite a_agentivite_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_agentivite
    ADD CONSTRAINT a_agentivite_pkey PRIMARY KEY (id_agentivite, id_agent);


--
-- Name: a_cat_occasion a_cat_occasion_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_cat_occasion
    ADD CONSTRAINT a_cat_occasion_pkey PRIMARY KEY (id_cat_occ, id);


--
-- Name: a_catgeorie a_categorie_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_catgeorie
    ADD CONSTRAINT a_categorie_pkey PRIMARY KEY (id_cat_elt, id_elt);


--
-- Name: a_ecrit a_ecrit_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_ecrit
    ADD CONSTRAINT a_ecrit_pkey PRIMARY KEY (id_titre, id_auteur);


--
-- Name: a_fonc a_fonc_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_fonc
    ADD CONSTRAINT a_fonc_pkey PRIMARY KEY (id_fonc, id_loc);


--
-- Name: agent_genre a_genre_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent_genre
    ADD CONSTRAINT a_genre_pkey PRIMARY KEY (id_genre, id_agent);


--
-- Name: a_langue a_langue_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_langue
    ADD CONSTRAINT a_langue_pkey PRIMARY KEY (id_langue, id_source);


--
-- Name: a_mat_attest a_mat_attest_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_mat_attest
    ADD CONSTRAINT a_mat_attest_pkey PRIMARY KEY (id_attest, id_mat);


--
-- Name: a_mat a_mat_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_mat
    ADD CONSTRAINT a_mat_pkey PRIMARY KEY (id_mat);


--
-- Name: a_matx a_matx_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_matx
    ADD CONSTRAINT a_matx_pkey PRIMARY KEY (id_matx);


--
-- Name: agent_nature a_nature_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent_nature
    ADD CONSTRAINT a_nature_pkey PRIMARY KEY (id_nat, id_agent);


--
-- Name: a_occasion a_occasion_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_occasion
    ADD CONSTRAINT a_occasion_pkey PRIMARY KEY (id_occ, id_attest);


--
-- Name: a_statut a_statut_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_statut
    ADD CONSTRAINT a_statut_pkey PRIMARY KEY (id_statut, id_agent);


--
-- Name: a_topo a_topo_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_topo
    ADD CONSTRAINT a_topo_pkey PRIMARY KEY (id_topo, id_loc);


--
-- Name: activite_agent activite_agent_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.activite_agent
    ADD CONSTRAINT activite_agent_pkey PRIMARY KEY (id_activite);


--
-- Name: agent_activite agent_activite_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent_activite
    ADD CONSTRAINT agent_activite_pkey PRIMARY KEY (id_activite, id_agent);


--
-- Name: agent agent_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent
    ADD CONSTRAINT agent_pkey PRIMARY KEY (id);


--
-- Name: agentivite agentivite_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agentivite
    ADD CONSTRAINT agentivite_pkey PRIMARY KEY (id_agentivite);


--
-- Name: a_pratique attest_pratique; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_pratique
    ADD CONSTRAINT attest_pratique PRIMARY KEY (id_pratique, id_attest);


--
-- Name: attestation attestation_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.attestation
    ADD CONSTRAINT attestation_pkey PRIMARY KEY (id);


--
-- Name: auteur auteur_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.auteur
    ADD CONSTRAINT auteur_pkey PRIMARY KEY (id_auteur);


--
-- Name: biblio biblio_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.biblio
    ADD CONSTRAINT biblio_pkey PRIMARY KEY (id);


--
-- Name: cat_mat cat_mat_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.cat_mat
    ADD CONSTRAINT cat_mat_pkey PRIMARY KEY (id);


--
-- Name: cat_matx cat_matx_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.cat_matx
    ADD CONSTRAINT cat_matx_pkey PRIMARY KEY (id_cat_matx);


--
-- Name: cat_occasion cat_occasion_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.cat_occasion
    ADD CONSTRAINT cat_occasion_pkey PRIMARY KEY (id_cat_occ);


--
-- Name: cat_support cat_support_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.cat_support
    ADD CONSTRAINT cat_support_pkey PRIMARY KEY (id);


--
-- Name: categorie_elt categorie_elt_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.categorie_elt
    ADD CONSTRAINT categorie_elt_pkey PRIMARY KEY (id_cat_elt);


--
-- Name: categorie_source categorie_source_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.categorie_source
    ADD CONSTRAINT categorie_source_pkey PRIMARY KEY (id);


--
-- Name: chercheur chercheur_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.chercheur
    ADD CONSTRAINT chercheur_pkey PRIMARY KEY (id);


--
-- Name: contient_elt contient_elts_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.contient_elt
    ADD CONSTRAINT contient_elts_pkey PRIMARY KEY (id_elt, id_attest, position_elt);


--
-- Name: corpus corpus_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.corpus
    ADD CONSTRAINT corpus_pkey PRIMARY KEY (id);


--
-- Name: datation datation_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.datation
    ADD CONSTRAINT datation_pkey PRIMARY KEY (id);


--
-- Name: ecrit ecrit_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.ecrit
    ADD CONSTRAINT ecrit_pkey PRIMARY KEY (id_source, id_auteur);


--
-- Name: elements elements_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.elements
    ADD CONSTRAINT elements_pkey PRIMARY KEY (id);


--
-- Name: entite_pol entite_pol_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.entite_pol
    ADD CONSTRAINT entite_pol_pkey PRIMARY KEY (id);


--
-- Name: etat_fiche etat_fiche_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.etat_fiche
    ADD CONSTRAINT etat_fiche_pkey PRIMARY KEY (id);


--
-- Name: formules formules_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.formules
    ADD CONSTRAINT formules_pkey PRIMARY KEY (id_formule);


--
-- Name: gde_reg gde_reg_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.gde_reg
    ADD CONSTRAINT gde_reg_pkey PRIMARY KEY (gid_reg);


--
-- Name: genre_elt genre_elt_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.genre_elt
    ADD CONSTRAINT genre_elt_pkey PRIMARY KEY (id_genre);


--
-- Name: genre genre_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.genre
    ADD CONSTRAINT genre_pkey PRIMARY KEY (id_genre);


--
-- Name: langue langue_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.langue
    ADD CONSTRAINT langue_pkey PRIMARY KEY (id);


--
-- Name: lien_loc lien_loc_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.lien_loc
    ADD CONSTRAINT lien_loc_pkey PRIMARY KEY (id_loc, id_source);


--
-- Name: loc_type loc_type_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.loc_type
    ADD CONSTRAINT loc_type_pkey PRIMARY KEY (id_loc_type);


--
-- Name: localisation localisation_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.localisation
    ADD CONSTRAINT localisation_pkey PRIMARY KEY (id);


--
-- Name: nature_elt nature_elt_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.nature_elt
    ADD CONSTRAINT nature_elt_pkey PRIMARY KEY (id_nature);


--
-- Name: nature nature_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.nature
    ADD CONSTRAINT nature_pkey PRIMARY KEY (id_nat);


--
-- Name: nombre_elt nombre_elt_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.nombre_elt
    ADD CONSTRAINT nombre_elt_pkey PRIMARY KEY (id_nombre);


--
-- Name: occasion occasion_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.occasion
    ADD CONSTRAINT occasion_pkey PRIMARY KEY (id_occ);


--
-- Name: suivi pk_suivi; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.suivi
    ADD CONSTRAINT pk_suivi PRIMARY KEY (id);


--
-- Name: pratique pratique_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.pratique
    ADD CONSTRAINT pratique_pkey PRIMARY KEY (id_pratique);


--
-- Name: q_fonc q_fonc_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.q_fonc
    ADD CONSTRAINT q_fonc_pkey PRIMARY KEY (id);


--
-- Name: q_topo q_topo_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.q_topo
    ADD CONSTRAINT q_topo_pkey PRIMARY KEY (id);


--
-- Name: reference_elts reference_elts_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.reference_elts
    ADD CONSTRAINT reference_elts_pkey PRIMARY KEY (id_elt, id_elt_1);


--
-- Name: source source_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.source
    ADD CONSTRAINT source_pkey PRIMARY KEY (id);


--
-- Name: ss_reg ss_reg_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.ss_reg
    ADD CONSTRAINT ss_reg_pkey PRIMARY KEY (gid_ssreg);


--
-- Name: statu_aff statu_aff_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.statu_aff
    ADD CONSTRAINT statu_aff_pkey PRIMARY KEY (id);


--
-- Name: titre_cite titre_cite_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.titre_cite
    ADD CONSTRAINT titre_cite_pkey PRIMARY KEY (id_source, id_titre);


--
-- Name: titre titre_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.titre
    ADD CONSTRAINT titre_pkey PRIMARY KEY (id_titre);


--
-- Name: trad_elt trad_elt_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.trad_elt
    ADD CONSTRAINT trad_elt_pkey PRIMARY KEY (id_trad_elt);


--
-- Name: trouve_elt trouve_elt_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.trouve_elt
    ADD CONSTRAINT trouve_elt_pkey PRIMARY KEY (id_biblio, id_elt);


--
-- Name: trouve_source trouve_source_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.trouve_source
    ADD CONSTRAINT trouve_source_pkey PRIMARY KEY (id_biblio, id_source);


--
-- Name: type_source type_litt_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.type_source
    ADD CONSTRAINT type_litt_pkey PRIMARY KEY (id);


--
-- Name: type_support type_support_pkey; Type: CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.type_support
    ADD CONSTRAINT type_support_pkey PRIMARY KEY (id);


--
-- Name: fki_contient_elt_id_categorie_fkey; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX fki_contient_elt_id_categorie_fkey ON public.contient_elt USING btree (id_categorie_elt);


--
-- Name: fki_create_attest_fkey; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX fki_create_attest_fkey ON public.attestation USING btree (create_attest);


--
-- Name: fki_create_user_fkey; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX fki_create_user_fkey ON public.source USING btree (create_source);


--
-- Name: fki_id_datation_fkey; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX fki_id_datation_fkey ON public.source USING btree (id_datation);


--
-- Name: fki_modif_attest_fkey; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX fki_modif_attest_fkey ON public.attestation USING btree (modif_attest);


--
-- Name: fki_modif_user_fkey; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX fki_modif_user_fkey ON public.source USING btree (modif_source);


--
-- Name: fki_type_loc_fkey; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX fki_type_loc_fkey ON public.lien_loc USING btree (type_loc);


--
-- Name: idx_lien_loc_id_loc; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX idx_lien_loc_id_loc ON public.lien_loc USING btree (id_loc);


--
-- Name: idx_lien_loc_id_source; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX idx_lien_loc_id_source ON public.lien_loc USING btree (id_source);


--
-- Name: idx_localisation_gid_reg; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX idx_localisation_gid_reg ON public.localisation USING btree (id_reg);


--
-- Name: idx_localisation_gid_ssreg; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX idx_localisation_gid_ssreg ON public.localisation USING btree (id_ssreg);


--
-- Name: idx_ss_reg_gid_reg; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX idx_ss_reg_gid_reg ON public.ss_reg USING btree (gid_reg);


--
-- Name: index_suivi_action; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX index_suivi_action ON public.suivi USING btree (action);


--
-- Name: index_suivi_dateheure; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX index_suivi_dateheure ON public.suivi USING btree (dateheure);


--
-- Name: index_suivi_idobjet; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX index_suivi_idobjet ON public.suivi USING btree (idobjet);


--
-- Name: index_suivi_nomtable; Type: INDEX; Schema: public; Owner: 3rgo
--

CREATE INDEX index_suivi_nomtable ON public.suivi USING btree (((((schema)::text || '.'::text) || (nomtable)::text)));


--
-- Name: localisation geom_trigger_lat; Type: TRIGGER; Schema: public; Owner: 3rgo
--

CREATE TRIGGER geom_trigger_lat AFTER INSERT OR UPDATE OF lat ON public.localisation FOR EACH ROW EXECUTE PROCEDURE public.update_localisationgeom();


--
-- Name: localisation geom_trigger_long; Type: TRIGGER; Schema: public; Owner: 3rgo
--

CREATE TRIGGER geom_trigger_long AFTER INSERT OR UPDATE OF long ON public.localisation FOR EACH ROW EXECUTE PROCEDURE public.update_localisationgeom();


--
-- Name: attestation trigger_suivimaj_attestation; Type: TRIGGER; Schema: public; Owner: 3rgo
--

CREATE TRIGGER trigger_suivimaj_attestation AFTER INSERT OR DELETE OR UPDATE ON public.attestation FOR EACH ROW EXECUTE PROCEDURE public.fonction_suivi_maj();


--
-- Name: source trigger_suivimaj_source; Type: TRIGGER; Schema: public; Owner: 3rgo
--

CREATE TRIGGER trigger_suivimaj_source AFTER INSERT OR DELETE OR UPDATE ON public.source FOR EACH ROW EXECUTE PROCEDURE public.fonction_suivi_maj();


--
-- Name: a_agentivite a_agnetivite_id_agent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_agentivite
    ADD CONSTRAINT a_agnetivite_id_agent_fkey FOREIGN KEY (id_agent) REFERENCES public.agent(id);


--
-- Name: a_agentivite a_agnetivite_id_agentivite_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_agentivite
    ADD CONSTRAINT a_agnetivite_id_agentivite_fkey FOREIGN KEY (id_agentivite) REFERENCES public.agentivite(id_agentivite);


--
-- Name: a_cat_occasion a_cat_occasion_id_cat_occ_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_cat_occasion
    ADD CONSTRAINT a_cat_occasion_id_cat_occ_fkey FOREIGN KEY (id_cat_occ) REFERENCES public.cat_occasion(id_cat_occ);


--
-- Name: a_cat_occasion a_cat_occasion_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_cat_occasion
    ADD CONSTRAINT a_cat_occasion_id_fkey FOREIGN KEY (id) REFERENCES public.attestation(id);


--
-- Name: a_catgeorie a_catgeorie_id_cat_elt_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_catgeorie
    ADD CONSTRAINT a_catgeorie_id_cat_elt_fkey FOREIGN KEY (id_cat_elt) REFERENCES public.categorie_elt(id_cat_elt);


--
-- Name: a_catgeorie a_catgeorie_id_elt_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_catgeorie
    ADD CONSTRAINT a_catgeorie_id_elt_fkey FOREIGN KEY (id_elt) REFERENCES public.elements(id);


--
-- Name: a_ecrit a_ecrit_id_auteur_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_ecrit
    ADD CONSTRAINT a_ecrit_id_auteur_fkey FOREIGN KEY (id_auteur) REFERENCES public.auteur(id_auteur);


--
-- Name: a_ecrit a_ecrit_id_titre_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_ecrit
    ADD CONSTRAINT a_ecrit_id_titre_fkey FOREIGN KEY (id_titre) REFERENCES public.titre(id_titre);


--
-- Name: a_fonc a_fonc_id_fonc_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_fonc
    ADD CONSTRAINT a_fonc_id_fonc_fkey FOREIGN KEY (id_fonc) REFERENCES public.q_fonc(id);


--
-- Name: a_fonc a_fonc_id_loc_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_fonc
    ADD CONSTRAINT a_fonc_id_loc_fkey FOREIGN KEY (id_loc) REFERENCES public.localisation(id);


--
-- Name: a_mat_attest a_mat_attest_id_attest_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_mat_attest
    ADD CONSTRAINT a_mat_attest_id_attest_fkey FOREIGN KEY (id_attest) REFERENCES public.attestation(id);


--
-- Name: a_mat_attest a_mat_attest_id_mat_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_mat_attest
    ADD CONSTRAINT a_mat_attest_id_mat_fkey FOREIGN KEY (id_mat) REFERENCES public.a_matx(id_matx);


--
-- Name: a_mat a_mat_id_type_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_mat
    ADD CONSTRAINT a_mat_id_type_fkey FOREIGN KEY (id_type) REFERENCES public.cat_mat(id);


--
-- Name: a_matx a_matx_id_cat_matx_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_matx
    ADD CONSTRAINT a_matx_id_cat_matx_fkey FOREIGN KEY (id_cat_matx) REFERENCES public.cat_matx(id_cat_matx);


--
-- Name: a_occasion a_occasion_id_attest_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_occasion
    ADD CONSTRAINT a_occasion_id_attest_fkey FOREIGN KEY (id_attest) REFERENCES public.attestation(id);


--
-- Name: a_occasion a_occasion_id_occ_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_occasion
    ADD CONSTRAINT a_occasion_id_occ_fkey FOREIGN KEY (id_occ) REFERENCES public.occasion(id_occ);


--
-- Name: a_pratique a_pratique_id_attest_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_pratique
    ADD CONSTRAINT a_pratique_id_attest_fkey FOREIGN KEY (id_attest) REFERENCES public.attestation(id);


--
-- Name: a_pratique a_pratique_id_pratique_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_pratique
    ADD CONSTRAINT a_pratique_id_pratique_fkey FOREIGN KEY (id_pratique) REFERENCES public.pratique(id_pratique);


--
-- Name: a_statut a_statut_id_agent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_statut
    ADD CONSTRAINT a_statut_id_agent_fkey FOREIGN KEY (id_agent) REFERENCES public.agent(id);


--
-- Name: a_statut a_statut_id_statut_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_statut
    ADD CONSTRAINT a_statut_id_statut_fkey FOREIGN KEY (id_statut) REFERENCES public.statu_aff(id);


--
-- Name: a_topo a_topo_id_loc_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_topo
    ADD CONSTRAINT a_topo_id_loc_fkey FOREIGN KEY (id_loc) REFERENCES public.localisation(id);


--
-- Name: a_topo a_topo_id_topo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.a_topo
    ADD CONSTRAINT a_topo_id_topo_fkey FOREIGN KEY (id_topo) REFERENCES public.q_topo(id);


--
-- Name: agent_activite agent_activite_id_activite_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent_activite
    ADD CONSTRAINT agent_activite_id_activite_fkey FOREIGN KEY (id_activite) REFERENCES public.activite_agent(id_activite);


--
-- Name: agent_activite agent_activite_id_agent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent_activite
    ADD CONSTRAINT agent_activite_id_agent_fkey FOREIGN KEY (id_agent) REFERENCES public.agent(id);


--
-- Name: agent_genre agent_genre_id_agent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent_genre
    ADD CONSTRAINT agent_genre_id_agent_fkey FOREIGN KEY (id_agent) REFERENCES public.agent(id);


--
-- Name: agent_genre agent_genre_id_genre_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent_genre
    ADD CONSTRAINT agent_genre_id_genre_fkey FOREIGN KEY (id_genre) REFERENCES public.genre(id_genre);


--
-- Name: agent agent_id_attest_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent
    ADD CONSTRAINT agent_id_attest_fkey FOREIGN KEY (id_attest) REFERENCES public.attestation(id);


--
-- Name: agent_nature agent_nature_id_agent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent_nature
    ADD CONSTRAINT agent_nature_id_agent_fkey FOREIGN KEY (id_agent) REFERENCES public.agent(id);


--
-- Name: agent_nature agent_nature_id_nat_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent_nature
    ADD CONSTRAINT agent_nature_id_nat_fkey FOREIGN KEY (id_nat) REFERENCES public.nature(id_nat);


--
-- Name: attestation attestation_id_etat_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.attestation
    ADD CONSTRAINT attestation_id_etat_fkey FOREIGN KEY (id_etat) REFERENCES public.etat_fiche(id);


--
-- Name: biblio biblio_corpus_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.biblio
    ADD CONSTRAINT biblio_corpus_id_fkey FOREIGN KEY (corpus_id) REFERENCES public.corpus(id);


--
-- Name: contient_elt contient_elt_id_attest_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.contient_elt
    ADD CONSTRAINT contient_elt_id_attest_fkey FOREIGN KEY (id_attest) REFERENCES public.attestation(id);


--
-- Name: contient_elt contient_elt_id_categorie_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.contient_elt
    ADD CONSTRAINT contient_elt_id_categorie_fkey FOREIGN KEY (id_categorie_elt) REFERENCES public.categorie_elt(id_cat_elt);


--
-- Name: contient_elt contient_elt_id_elt_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.contient_elt
    ADD CONSTRAINT contient_elt_id_elt_fkey FOREIGN KEY (id_elt) REFERENCES public.elements(id);


--
-- Name: contient_elt contient_elt_id_genre_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.contient_elt
    ADD CONSTRAINT contient_elt_id_genre_fkey FOREIGN KEY (id_genre) REFERENCES public.genre_elt(id_genre);


--
-- Name: contient_elt contient_elt_id_nombre_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.contient_elt
    ADD CONSTRAINT contient_elt_id_nombre_fkey FOREIGN KEY (id_nombre) REFERENCES public.nombre_elt(id_nombre);


--
-- Name: attestation create_attest_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.attestation
    ADD CONSTRAINT create_attest_fkey FOREIGN KEY (create_attest) REFERENCES public.chercheur(id);


--
-- Name: source create_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.source
    ADD CONSTRAINT create_user_fkey FOREIGN KEY (create_source) REFERENCES public.chercheur(id);


--
-- Name: attestation datation_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.attestation
    ADD CONSTRAINT datation_id_fkey FOREIGN KEY (id_datation) REFERENCES public.datation(id);


--
-- Name: ecrit ecrit_id_auteur_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.ecrit
    ADD CONSTRAINT ecrit_id_auteur_fkey FOREIGN KEY (id_auteur) REFERENCES public.auteur(id_auteur);


--
-- Name: ecrit ecrit_source_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.ecrit
    ADD CONSTRAINT ecrit_source_fkey FOREIGN KEY (id_source) REFERENCES public.source(id);


--
-- Name: formules formules_id_attest_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.formules
    ADD CONSTRAINT formules_id_attest_fkey FOREIGN KEY (id_attest) REFERENCES public.attestation(id);


--
-- Name: formules formules_id_chercheur_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.formules
    ADD CONSTRAINT formules_id_chercheur_fkey FOREIGN KEY (id_chercheur) REFERENCES public.chercheur(id);


--
-- Name: source id_datation_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.source
    ADD CONSTRAINT id_datation_fkey FOREIGN KEY (id_datation) REFERENCES public.datation(id);


--
-- Name: lien_loc lien_loc_id_loc_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.lien_loc
    ADD CONSTRAINT lien_loc_id_loc_fkey FOREIGN KEY (id_loc) REFERENCES public.localisation(id);


--
-- Name: attestation loc_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.attestation
    ADD CONSTRAINT loc_id_fkey FOREIGN KEY (id_loc) REFERENCES public.localisation(id);


--
-- Name: agent loc_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.agent
    ADD CONSTRAINT loc_id_fkey FOREIGN KEY (id_loc) REFERENCES public.localisation(id);


--
-- Name: elements loc_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.elements
    ADD CONSTRAINT loc_id_fkey FOREIGN KEY (id_loc) REFERENCES public.localisation(id);


--
-- Name: localisation localisation_entite_pol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.localisation
    ADD CONSTRAINT localisation_entite_pol_fkey FOREIGN KEY (entite_pol) REFERENCES public.entite_pol(id);


--
-- Name: localisation localisation_gid_reg_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.localisation
    ADD CONSTRAINT localisation_gid_reg_fkey FOREIGN KEY (id_reg) REFERENCES public.gde_reg(gid_reg);


--
-- Name: localisation localisation_gid_ssreg_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.localisation
    ADD CONSTRAINT localisation_gid_ssreg_fkey FOREIGN KEY (id_ssreg) REFERENCES public.ss_reg(gid_ssreg);


--
-- Name: attestation modif_attest_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.attestation
    ADD CONSTRAINT modif_attest_fkey FOREIGN KEY (modif_attest) REFERENCES public.chercheur(id);


--
-- Name: source modif_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.source
    ADD CONSTRAINT modif_user_fkey FOREIGN KEY (modif_source) REFERENCES public.chercheur(id);


--
-- Name: elements nature_elt_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.elements
    ADD CONSTRAINT nature_elt_fkey FOREIGN KEY (id_nature) REFERENCES public.nature_elt(id_nature);


--
-- Name: occasion occasion_id_cat_occ_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.occasion
    ADD CONSTRAINT occasion_id_cat_occ_fkey FOREIGN KEY (id_cat_occ) REFERENCES public.cat_occasion(id_cat_occ);


--
-- Name: reference_elts reference_elts_id_elt_1_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.reference_elts
    ADD CONSTRAINT reference_elts_id_elt_1_fkey FOREIGN KEY (id_elt_1) REFERENCES public.elements(id);


--
-- Name: reference_elts reference_elts_id_elt_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.reference_elts
    ADD CONSTRAINT reference_elts_id_elt_fkey FOREIGN KEY (id_elt) REFERENCES public.elements(id);


--
-- Name: lien_loc source_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.lien_loc
    ADD CONSTRAINT source_id_fkey FOREIGN KEY (id_source) REFERENCES public.source(id);


--
-- Name: ss_reg ss_reg_gid_reg_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.ss_reg
    ADD CONSTRAINT ss_reg_gid_reg_fkey FOREIGN KEY (gid_reg) REFERENCES public.gde_reg(gid_reg);


--
-- Name: titre_cite titre_cite_id_source_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.titre_cite
    ADD CONSTRAINT titre_cite_id_source_fkey FOREIGN KEY (id_source) REFERENCES public.source(id);


--
-- Name: titre_cite titre_cite_id_titre_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.titre_cite
    ADD CONSTRAINT titre_cite_id_titre_fkey FOREIGN KEY (id_titre) REFERENCES public.titre(id_titre);


--
-- Name: trad_elt trad_elt_id_elt_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.trad_elt
    ADD CONSTRAINT trad_elt_id_elt_fkey FOREIGN KEY (id_elt) REFERENCES public.elements(id);


--
-- Name: trouve_elt trouve_elt_id_biblio_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.trouve_elt
    ADD CONSTRAINT trouve_elt_id_biblio_fkey FOREIGN KEY (id_biblio) REFERENCES public.biblio(id);


--
-- Name: trouve_elt trouve_elt_id_elt_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.trouve_elt
    ADD CONSTRAINT trouve_elt_id_elt_fkey FOREIGN KEY (id_elt) REFERENCES public.elements(id);


--
-- Name: trouve_source trouve_source_id_biblio_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.trouve_source
    ADD CONSTRAINT trouve_source_id_biblio_fkey FOREIGN KEY (id_biblio) REFERENCES public.biblio(id);


--
-- Name: lien_loc type_loc_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.lien_loc
    ADD CONSTRAINT type_loc_fkey FOREIGN KEY (type_loc) REFERENCES public.loc_type(id_loc_type);


--
-- Name: type_support type_support_id_cat_supp_fkey; Type: FK CONSTRAINT; Schema: public; Owner: 3rgo
--

ALTER TABLE ONLY public.type_support
    ADD CONSTRAINT type_support_id_cat_supp_fkey FOREIGN KEY (id_cat_supp) REFERENCES public.cat_support(id);


--
-- PostgreSQL database dump complete
--

