<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190703161916 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE suivi_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE suivi (id INT NOT NULL, nom_table VARCHAR(100) NOT NULL, id_entite INT DEFAULT NULL, date_heure TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, action VARCHAR(6) NOT NULL, old_data TEXT DEFAULT NULL, new_data TEXT DEFAULT NULL, detail TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql(implode(" ", [
            "CREATE FUNCTION public.fonction_suivi_maj() RETURNS trigger",
            "LANGUAGE plpgsql SECURITY DEFINER",
            "SET search_path TO 'pg_catalog', 'public'",
            "AS $$",
            "DECLARE",
            "variable_ancienne_valeur TEXT;",
            "variable_nouvelle_valeur TEXT;",
            "identifiant INTEGER;",
            "BEGIN",
            "IF (TG_OP = 'UPDATE') THEN",
            "variable_ancienne_valeur := ROW(OLD.*);",
            "variable_nouvelle_valeur := ROW(NEW.*);",
            "identifiant := OLD.id;",
            "INSERT INTO public.suivi (id, nom_table, id_entite, action, date_heure, old_data, new_data, detail)",
            "VALUES (nextval('suivi_id_seq'), TG_TABLE_NAME::TEXT, identifiant, TG_OP, now(),",
            "variable_ancienne_valeur, variable_nouvelle_valeur, current_query());",
            "RETURN NEW;",
            "ELSIF (TG_OP = 'DELETE') THEN",
            "variable_ancienne_valeur := ROW(OLD.*);",
            "identifiant := OLD.id;",
            "INSERT INTO public.suivi (id, nom_table, id_entite, action, date_heure, old_data, detail)",
            "VALUES (nextval('suivi_id_seq'), TG_TABLE_NAME::TEXT, identifiant, TG_OP, now(),",
            "variable_ancienne_valeur, current_query());",
            "RETURN OLD;",
            "ELSIF (TG_OP = 'INSERT') THEN",
            "variable_nouvelle_valeur := ROW(NEW.*);",
            "identifiant := NEW.id;",
            "INSERT INTO public.suivi (id, nom_table, id_entite, action, date_heure, new_data, detail)",
            "VALUES (nextval('suivi_id_seq'), TG_TABLE_NAME::TEXT, identifiant, TG_OP, now(),",
            "variable_nouvelle_valeur, current_query());",
            "RETURN NEW;",
            "ELSE",
            "RAISE WARNING '[public.fonction_suivi_maj] - Other action occurred: %, at %', TG_OP,now();",
            "RETURN NULL;",
            "END IF;",
            "END;",
            "$$;",
        ]));
        $this->addSql("CREATE TRIGGER trigger_suivimaj_attestation AFTER INSERT OR DELETE OR UPDATE ON public.attestation FOR EACH ROW EXECUTE PROCEDURE public.fonction_suivi_maj();");
        $this->addSql("CREATE TRIGGER trigger_suivimaj_source AFTER INSERT OR DELETE OR UPDATE ON public.source FOR EACH ROW EXECUTE PROCEDURE public.fonction_suivi_maj();");
        $this->addSql("CREATE TRIGGER trigger_suivimaj_element AFTER INSERT OR DELETE OR UPDATE ON public.element FOR EACH ROW EXECUTE PROCEDURE public.fonction_suivi_maj();");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE suivi_id_seq CASCADE');
        $this->addSql('DROP TABLE suivi');

        $this->addSql("DROP TRIGGER trigger_suivimaj_attestation ON public.attestation");
        $this->addSql("DROP TRIGGER trigger_suivimaj_source ON public.source");
        $this->addSql("DROP TRIGGER trigger_suivimaj_element ON public.element");
        $this->addSql("DROP FUNCTION public.fonction_suivi_maj()");
    }
}
