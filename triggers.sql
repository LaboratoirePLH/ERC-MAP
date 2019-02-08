CREATE FUNCTION public.fonction_suivi_maj() RETURNS trigger
    LANGUAGE plpgsql SECURITY DEFINER
    SET search_path TO 'pg_catalog', 'public'
    AS $$
DECLARE
variable_ancienne_valeur TEXT;
variable_nouvelle_valeur TEXT;
identifiant INTEGER;
BEGIN
IF (TG_OP = 'UPDATE') THEN
variable_ancienne_valeur := ROW(OLD.*);
variable_nouvelle_valeur := ROW(NEW.*);
identifiant := OLD.id;
INSERT INTO public.suivi (schema,nomtable, utilisateur, action, dataorigine, datanouvelle, detailmaj, idobjet)
VALUES (TG_TABLE_SCHEMA::TEXT, TG_TABLE_NAME::TEXT, session_user::TEXT, substring(TG_OP,1,1),
variable_ancienne_valeur, variable_nouvelle_valeur, current_query(), identifiant);
RETURN NEW;
ELSIF (TG_OP = 'DELETE') THEN
variable_ancienne_valeur := ROW(OLD.*);
identifiant := OLD.id;
INSERT INTO public.suivi (schema, nomtable, utilisateur, action, dataorigine, detailmaj, idobjet)
VALUES (TG_TABLE_SCHEMA::TEXT, TG_TABLE_NAME::TEXT, session_user::TEXT, substring(TG_OP,1,1),
variable_ancienne_valeur, current_query(), identifiant);
RETURN OLD;
ELSIF (TG_OP = 'INSERT') THEN
variable_nouvelle_valeur := ROW(NEW.*);
identifiant := NEW.id;
INSERT INTO public.suivi (schema, nomtable, utilisateur, action, datanouvelle, detailmaj, idobjet)
VALUES (TG_TABLE_SCHEMA::TEXT, TG_TABLE_NAME::TEXT, session_user::TEXT, substring(TG_OP,1,1),
variable_nouvelle_valeur, current_query(), identifiant);
RETURN NEW;
ELSE
RAISE WARNING '[public.fonction_suivi_maj] - Other action occurred: %, at %', TG_OP,now();
RETURN NULL;
END IF;
END;
$$;


ALTER FUNCTION public.fonction_suivi_maj() OWNER TO "3rgo";

--
-- Name: maj_biblio_complet(); Type: FUNCTION; Schema: public; Owner: 3rgo
--

CREATE FUNCTION public.maj_biblio_complet() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
   BEGIN
		IF New.bib_ref = '' Then
		new.titre_com = NEW.auteur_biblio || ', ' || NEW.titre_abr || ' (' || NEW.annee || ')';
		Return NEW;

		ELSIF New.bib_ref <> '' Then
		new.titre_com = NEW.titre_abr || ' (' || NEW.corpus_id  ||'), ' || new.bib_ref ;
		Return NEW;
		-- Select corpus.nom from corpus, biblio where biblio.corpus_id = corpus.id
		END IF;
   END;
  $$;


ALTER FUNCTION public.maj_biblio_complet() OWNER TO "3rgo";

--
-- Name: update_fiab_datation(); Type: FUNCTION; Schema: public; Owner: 3rgo
--

CREATE FUNCTION public.update_fiab_datation() RETURNS trigger
    LANGUAGE plpgsql
    AS $$

Declare
fiab_datation integer;

BEGIN
New.fiab_datation := (Select(abs(new.post_quem - new.ante_quem))From datation);
Return New ;
End;
$$;


ALTER FUNCTION public.update_fiab_datation() OWNER TO "3rgo";

--
-- Name: update_localisationgeom(); Type: FUNCTION; Schema: public; Owner: 3rgo
--

CREATE FUNCTION public.update_localisationgeom() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE localisation SET geom = ST_SetSRID(ST_Makepoint(long,lat),4326) where lat IS NOT null and long IS NOT NULL and lat <> 0.0 and long <> 0.0;
        RETURN null;
END;
$$;


ALTER FUNCTION public.update_localisationgeom() OWNER TO "3rgo";