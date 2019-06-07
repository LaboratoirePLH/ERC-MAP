<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190312163110 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE agentivite ALTER id TYPE INT');
        $this->addSql('ALTER TABLE agentivite ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE grande_region ALTER id TYPE INT');
        $this->addSql('ALTER TABLE grande_region ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE localisation ALTER grande_region_id TYPE INT');
        $this->addSql('ALTER TABLE localisation ALTER grande_region_id DROP DEFAULT');
        $this->addSql('ALTER TABLE localisation ALTER sous_region_id TYPE INT');
        $this->addSql('ALTER TABLE localisation ALTER sous_region_id DROP DEFAULT');
        $this->addSql('ALTER TABLE localisation_q_topographie ALTER id_q_topographie TYPE INT');
        $this->addSql('ALTER TABLE localisation_q_topographie ALTER id_q_topographie DROP DEFAULT');
        $this->addSql('ALTER TABLE localisation_q_fonction ALTER id_q_fonction TYPE INT');
        $this->addSql('ALTER TABLE localisation_q_fonction ALTER id_q_fonction DROP DEFAULT');
        $this->addSql('ALTER TABLE agent_statut ALTER id_statut TYPE INT');
        $this->addSql('ALTER TABLE agent_statut ALTER id_statut DROP DEFAULT');
        $this->addSql('ALTER TABLE agent_nature ALTER id_nature TYPE INT');
        $this->addSql('ALTER TABLE agent_nature ALTER id_nature DROP DEFAULT');
        $this->addSql('ALTER TABLE agent_genre ALTER id_genre TYPE INT');
        $this->addSql('ALTER TABLE agent_genre ALTER id_genre DROP DEFAULT');
        $this->addSql('ALTER TABLE agent_activite ALTER id_activite TYPE INT');
        $this->addSql('ALTER TABLE agent_activite ALTER id_activite DROP DEFAULT');
        $this->addSql('ALTER TABLE agent_agentivite ALTER id_agentivite TYPE INT');
        $this->addSql('ALTER TABLE agent_agentivite ALTER id_agentivite DROP DEFAULT');
        $this->addSql('ALTER TABLE activite_agent ALTER id TYPE INT');
        $this->addSql('ALTER TABLE activite_agent ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE pratique ALTER id TYPE INT');
        $this->addSql('ALTER TABLE pratique ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE projet ALTER id TYPE INT');
        $this->addSql('ALTER TABLE projet ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE projet_chercheur ALTER id_projet TYPE INT');
        $this->addSql('ALTER TABLE projet_chercheur ALTER id_projet DROP DEFAULT');
        $this->addSql('ALTER TABLE categorie_source ALTER id TYPE INT');
        $this->addSql('ALTER TABLE categorie_source ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE type_source ALTER id TYPE INT');
        $this->addSql('ALTER TABLE type_source ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE type_source ALTER categorie_source_id TYPE INT');
        $this->addSql('ALTER TABLE type_source ALTER categorie_source_id DROP DEFAULT');
        $this->addSql('ALTER TABLE categorie_occasion ALTER id TYPE INT');
        $this->addSql('ALTER TABLE categorie_occasion ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE statut_affiche ALTER id TYPE INT');
        $this->addSql('ALTER TABLE statut_affiche ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE materiel ALTER id TYPE INT');
        $this->addSql('ALTER TABLE materiel ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE materiel ALTER categorie_materiel_id TYPE INT');
        $this->addSql('ALTER TABLE materiel ALTER categorie_materiel_id DROP DEFAULT');
        $this->addSql('ALTER TABLE genre ALTER id TYPE INT');
        $this->addSql('ALTER TABLE genre ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE q_topographie ALTER id TYPE INT');
        $this->addSql('ALTER TABLE q_topographie ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE categorie_materiau ALTER id TYPE INT');
        $this->addSql('ALTER TABLE categorie_materiau ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE nature ALTER id TYPE INT');
        $this->addSql('ALTER TABLE nature ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE sous_region ALTER id TYPE INT');
        $this->addSql('ALTER TABLE sous_region ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE sous_region ALTER grande_region_id TYPE INT');
        $this->addSql('ALTER TABLE sous_region ALTER grande_region_id DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation ALTER id_etat_fiche TYPE INT');
        $this->addSql('ALTER TABLE attestation ALTER id_etat_fiche DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation ALTER id_categorie_occasion TYPE INT');
        $this->addSql('ALTER TABLE attestation ALTER id_categorie_occasion DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation ALTER id_occasion TYPE INT');
        $this->addSql('ALTER TABLE attestation ALTER id_occasion DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation_pratique ALTER id_pratique TYPE INT');
        $this->addSql('ALTER TABLE attestation_pratique ALTER id_pratique DROP DEFAULT');
        $this->addSql('ALTER TABLE langue ALTER id TYPE INT');
        $this->addSql('ALTER TABLE langue ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE categorie_materiel ALTER id TYPE INT');
        $this->addSql('ALTER TABLE categorie_materiel ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE occasion ALTER id TYPE INT');
        $this->addSql('ALTER TABLE occasion ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE occasion ALTER categorie_occasion_id TYPE INT');
        $this->addSql('ALTER TABLE occasion ALTER categorie_occasion_id DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER type_support_id TYPE INT');
        $this->addSql('ALTER TABLE source ALTER type_support_id DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER categorie_support_id TYPE INT');
        $this->addSql('ALTER TABLE source ALTER categorie_support_id DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER materiau_id TYPE INT');
        $this->addSql('ALTER TABLE source ALTER materiau_id DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER categorie_materiau_id TYPE INT');
        $this->addSql('ALTER TABLE source ALTER categorie_materiau_id DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER type_source_id TYPE INT');
        $this->addSql('ALTER TABLE source ALTER type_source_id DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER categorie_source_id TYPE INT');
        $this->addSql('ALTER TABLE source ALTER categorie_source_id DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER id_projet TYPE INT');
        $this->addSql('ALTER TABLE source ALTER id_projet DROP DEFAULT');
        $this->addSql('ALTER TABLE source_langue ALTER id_langue TYPE INT');
        $this->addSql('ALTER TABLE source_langue ALTER id_langue DROP DEFAULT');
        $this->addSql('ALTER TABLE categorie_support ALTER id TYPE INT');
        $this->addSql('ALTER TABLE categorie_support ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation_materiel ALTER id_materiel TYPE INT');
        $this->addSql('ALTER TABLE attestation_materiel ALTER id_materiel DROP DEFAULT');
        $this->addSql('ALTER TABLE type_support ALTER id TYPE INT');
        $this->addSql('ALTER TABLE type_support ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE type_support ALTER categorie_support_id TYPE INT');
        $this->addSql('ALTER TABLE type_support ALTER categorie_support_id DROP DEFAULT');
        $this->addSql('ALTER TABLE q_fonction ALTER id TYPE INT');
        $this->addSql('ALTER TABLE q_fonction ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE etat_fiche ALTER id TYPE INT');
        $this->addSql('ALTER TABLE etat_fiche ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE materiau ALTER id TYPE INT');
        $this->addSql('ALTER TABLE materiau ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE materiau ALTER categorie_materiau_id TYPE INT');
        $this->addSql('ALTER TABLE materiau ALTER categorie_materiau_id DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE projet_chercheur ALTER id_projet TYPE SMALLINT');
        $this->addSql('ALTER TABLE projet_chercheur ALTER id_projet DROP DEFAULT');
        $this->addSql('ALTER TABLE projet ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE projet ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE q_topographie ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE q_topographie ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE localisation_q_topographie ALTER id_q_topographie TYPE SMALLINT');
        $this->addSql('ALTER TABLE localisation_q_topographie ALTER id_q_topographie DROP DEFAULT');
        $this->addSql('ALTER TABLE q_fonction ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE q_fonction ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE localisation_q_fonction ALTER id_q_fonction TYPE SMALLINT');
        $this->addSql('ALTER TABLE localisation_q_fonction ALTER id_q_fonction DROP DEFAULT');
        $this->addSql('ALTER TABLE grande_region ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE grande_region ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE sous_region ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE sous_region ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE sous_region ALTER grande_region_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE sous_region ALTER grande_region_id DROP DEFAULT');
        $this->addSql('ALTER TABLE type_source ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE type_source ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE type_source ALTER categorie_source_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE type_source ALTER categorie_source_id DROP DEFAULT');
        $this->addSql('ALTER TABLE categorie_source ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE categorie_source ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE statut_affiche ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE statut_affiche ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE agent_statut ALTER id_statut TYPE SMALLINT');
        $this->addSql('ALTER TABLE agent_statut ALTER id_statut DROP DEFAULT');
        $this->addSql('ALTER TABLE nature ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE nature ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE agent_nature ALTER id_nature TYPE SMALLINT');
        $this->addSql('ALTER TABLE agent_nature ALTER id_nature DROP DEFAULT');
        $this->addSql('ALTER TABLE langue ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE langue ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE genre ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE genre ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE source_langue ALTER id_langue TYPE SMALLINT');
        $this->addSql('ALTER TABLE source_langue ALTER id_langue DROP DEFAULT');
        $this->addSql('ALTER TABLE agent_genre ALTER id_genre TYPE SMALLINT');
        $this->addSql('ALTER TABLE agent_genre ALTER id_genre DROP DEFAULT');
        $this->addSql('ALTER TABLE categorie_support ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE categorie_support ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE activite_agent ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE activite_agent ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE agent_activite ALTER id_activite TYPE SMALLINT');
        $this->addSql('ALTER TABLE agent_activite ALTER id_activite DROP DEFAULT');
        $this->addSql('ALTER TABLE type_support ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE type_support ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE type_support ALTER categorie_support_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE type_support ALTER categorie_support_id DROP DEFAULT');
        $this->addSql('ALTER TABLE agentivite ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE agentivite ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE categorie_materiau ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE categorie_materiau ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE agent_agentivite ALTER id_agentivite TYPE SMALLINT');
        $this->addSql('ALTER TABLE agent_agentivite ALTER id_agentivite DROP DEFAULT');
        $this->addSql('ALTER TABLE materiau ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE materiau ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE materiau ALTER categorie_materiau_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE materiau ALTER categorie_materiau_id DROP DEFAULT');
        $this->addSql('ALTER TABLE categorie_materiel ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE categorie_materiel ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE pratique ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE pratique ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE localisation ALTER grande_region_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE localisation ALTER grande_region_id DROP DEFAULT');
        $this->addSql('ALTER TABLE localisation ALTER sous_region_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE localisation ALTER sous_region_id DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation_pratique ALTER id_pratique TYPE SMALLINT');
        $this->addSql('ALTER TABLE attestation_pratique ALTER id_pratique DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER type_support_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE source ALTER type_support_id DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER categorie_support_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE source ALTER categorie_support_id DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER materiau_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE source ALTER materiau_id DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER categorie_materiau_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE source ALTER categorie_materiau_id DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER type_source_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE source ALTER type_source_id DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER categorie_source_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE source ALTER categorie_source_id DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER id_projet TYPE SMALLINT');
        $this->addSql('ALTER TABLE source ALTER id_projet DROP DEFAULT');
        $this->addSql('ALTER TABLE etat_fiche ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE etat_fiche ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation ALTER id_etat_fiche TYPE SMALLINT');
        $this->addSql('ALTER TABLE attestation ALTER id_etat_fiche DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation ALTER id_categorie_occasion TYPE SMALLINT');
        $this->addSql('ALTER TABLE attestation ALTER id_categorie_occasion DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation ALTER id_occasion TYPE SMALLINT');
        $this->addSql('ALTER TABLE attestation ALTER id_occasion DROP DEFAULT');
        $this->addSql('ALTER TABLE materiel ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE materiel ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE materiel ALTER categorie_materiel_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE materiel ALTER categorie_materiel_id DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation_materiel ALTER id_materiel TYPE SMALLINT');
        $this->addSql('ALTER TABLE attestation_materiel ALTER id_materiel DROP DEFAULT');
        $this->addSql('ALTER TABLE categorie_occasion ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE categorie_occasion ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE occasion ALTER id TYPE SMALLINT');
        $this->addSql('ALTER TABLE occasion ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE occasion ALTER categorie_occasion_id TYPE SMALLINT');
        $this->addSql('ALTER TABLE occasion ALTER categorie_occasion_id DROP DEFAULT');
    }
}
