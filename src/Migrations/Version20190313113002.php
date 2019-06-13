<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190313113002 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        // Nature d'élément
        $this->addSql("INSERT INTO public.nature_element (id, nom_fr, nom_en) VALUES (7, 'Indéterminé', 'Undetermined');");
        $this->addSql("INSERT INTO public.nature_element (id, nom_fr, nom_en) VALUES (2, 'Verbe', 'Verb');");
        $this->addSql("INSERT INTO public.nature_element (id, nom_fr, nom_en) VALUES (1, 'Adjectif', 'Adjective');");
        $this->addSql("INSERT INTO public.nature_element (id, nom_fr, nom_en) VALUES (4, 'Substantif / Syntagme nominal', 'Substantive / Noun phrase');");
        $this->addSql("INSERT INTO public.nature_element (id, nom_fr, nom_en) VALUES (3, 'Pronom', 'Pronoun');");
        $this->addSql("INSERT INTO public.nature_element (id, nom_fr, nom_en) VALUES (6, 'Proposition subordonnée', 'Subordinate clause');");
        $this->addSql("INSERT INTO public.nature_element (id, nom_fr, nom_en) VALUES (5, 'Participe', 'Participle');");

        // Genre d'élément
        $this->addSql("INSERT INTO public.genre_element (id, nom_fr, nom_en) VALUES (4, 'Indéterminé', 'Unditermined');");
        $this->addSql("INSERT INTO public.genre_element (id, nom_fr, nom_en) VALUES (1, 'Masculin', 'Masculine');");
        $this->addSql("INSERT INTO public.genre_element (id, nom_fr, nom_en) VALUES (2, 'Féminin', 'Feminine');");
        $this->addSql("INSERT INTO public.genre_element (id, nom_fr, nom_en) VALUES (3, 'Neutre', 'Neuter');");

        // Nombre d'élément
        $this->addSql("INSERT INTO public.nombre_element (id, nom_fr, nom_en) VALUES (4, 'Indéterminé', 'Undetermined');");
        $this->addSql("INSERT INTO public.nombre_element (id, nom_fr, nom_en) VALUES (2, 'Pluriel', 'Plural');");
        $this->addSql("INSERT INTO public.nombre_element (id, nom_fr, nom_en) VALUES (1, 'Singulier', 'Singular');");
        $this->addSql("INSERT INTO public.nombre_element (id, nom_fr, nom_en) VALUES (3, 'Duel', 'Dual');");

        // Catégorie d'élément
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (1, 'Alimentation', 'Food');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (2, 'Animal', 'Animal');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (3, 'Perception', 'Perception');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (4, 'Artisanat', 'Craft');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (5, 'Commerce', 'Trade');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (6, 'Construction / Fondation', 'Construction / Foundation');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (7, 'Culte / Rituel', 'Cult / Ritual');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (8, 'Louange', 'Praise');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (9, 'Génération / Croissance', 'Generation / Growth');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (10, 'Genre', 'Gender');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (11, 'Guerre / Violence', 'War / Violence');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (12, 'Savoir', 'Knowledge');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (13, 'Justice', 'Justice');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (14, 'Signe divin', 'Divine sign');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (15, 'Mobilité', 'Mobility');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (16, 'Objets', 'Objects');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (17, 'Phénomène naturel', 'Natural phenomenon');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (18, 'Politique', 'Political');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (19, 'Social', 'Social');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (20, 'Protection / Bienfaisance', 'Protection / Beneficience');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (21, 'Relationnel', 'Relational');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (22, 'Santé', 'Health');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (23, 'Séduction / Sexualité', 'Seduction / Sexuality');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (24, 'Sport / Spectacle', 'Sport / Show');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (25, 'Temporalité', 'Temporality');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (26, 'Titre', 'Title');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (27, 'Espace', 'Space');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (28, 'Toponyme', 'Toponym');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (29, 'Végétal', 'Plant');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (30, 'Propriété / Abondance / Richesse', 'Property / Abundance / Richness');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (31, 'Famille / Domestique', 'Family / Domestic');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (32, 'Funéraire / Au-delà', 'Funerary / Netherworld');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (33, 'Destin / Fortune', 'Fate / Fortune');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (34, 'Limite / Passage', 'Limit / Passage');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (36, 'Nom barbare', 'Barbaric name');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (37, 'Quantité / Nombre', 'Quantity / Number');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (38, 'Malveillance', 'Malevolence');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (39, 'Incertain', 'Unclear');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (35, 'Agriculture', 'Agriculture');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (40, 'Elevage', 'Animal husbandry');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (41, 'Extraction', 'Extraction');");
        $this->addSql("INSERT INTO public.categorie_element (id, nom_fr, nom_en) VALUES (42, 'Pêche / Chasse', 'Fishing / Hunting');");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("TRUNCATE nature_element RESTART IDENTITY;");
        $this->addSql("TRUNCATE genre_element RESTART IDENTITY;");
        $this->addSql("TRUNCATE nombre_element RESTART IDENTITY;");
        $this->addSql("TRUNCATE categorie_element RESTART IDENTITY;");
    }
}
