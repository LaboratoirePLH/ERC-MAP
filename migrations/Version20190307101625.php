<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190307101625 extends AbstractMigration
{

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        // Agentivité
        $this->addSql("INSERT INTO public.agentivite (id, nom_fr, nom_en) VALUES (1, 'Énonciateur', 'Utterer');");
        $this->addSql("INSERT INTO public.agentivite (id, nom_fr, nom_en) VALUES (2, 'Bénéficiaire / Cible', 'Beneficiary / Target');");
        $this->addSql("INSERT INTO public.agentivite (id, nom_fr, nom_en) VALUES (3, 'Opérateur rituel', 'Ritual operator');");
        $this->addSql("INSERT INTO public.agentivite (id, nom_fr, nom_en) VALUES (4, 'Autre', 'Other');");

        // Catégorie Occasion
        $this->addSql("INSERT INTO public.categorie_occasion (id, nom_fr, nom_en) VALUES (1, 'Vie collective', 'Collective life');");
        $this->addSql("INSERT INTO public.categorie_occasion (id, nom_fr, nom_en) VALUES (2, 'Vie personnelle', 'Personal life');");
        $this->addSql("INSERT INTO public.categorie_occasion (id, nom_fr, nom_en) VALUES (3, 'Mobilité', 'Mobility');");
        $this->addSql("INSERT INTO public.categorie_occasion (id, nom_fr, nom_en) VALUES (4, 'Exploitation des ressources', 'Exploitation of resources');");
        $this->addSql("INSERT INTO public.categorie_occasion (id, nom_fr, nom_en) VALUES (5, 'Artisanat / Commerce', 'Craft / Trade');");
        $this->addSql("INSERT INTO public.categorie_occasion (id, nom_fr, nom_en) VALUES (6, 'Guerre', 'War');");
        $this->addSql("INSERT INTO public.categorie_occasion (id, nom_fr, nom_en) VALUES (7, 'Signe divin', 'Divine sign');");
        $this->addSql("INSERT INTO public.categorie_occasion (id, nom_fr, nom_en) VALUES (8, 'Phénomène naturel', 'Natural phenomenon');");

        // Catégorie Matériel
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (2, 'Animal', 'Animal');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (3, 'Architecture', 'Architecture');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (4, 'Armement', 'Weaponry');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (5, 'Attribut iconographique', 'Iconographic attribute');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (6, 'Conteneur', 'Container');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (7, 'Bien(s)', 'Good(s)');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (8, 'Support graphique', 'Graphic medium');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (9, 'Effigie', 'Effigy');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (10, 'Funéraire', 'Funerary');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (12, 'Meuble', 'Furniture');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (13, 'Navigation', 'Navigation');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (14, 'Outil', 'Tool');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (16, 'Sport', 'Sport');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (18, 'Vaisselle', 'Crockery');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (19, 'Végétal', 'Plant');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (20, 'Instrument de musique', 'Musical instrument');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (21, 'Jouet', 'Toy');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (1, 'Alimentation / Parfum', 'Food / Perfume');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (11, 'Harnachement / Attelage', 'Harness / Yoke');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (15, 'Parure / Habillement', 'Finery');");
        $this->addSql("INSERT INTO public.categorie_materiel (id, nom_fr, nom_en) VALUES (17, 'Stèle / Cippe', 'Stele / Cippus');");

        // Matériel
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (1, 'Boisson', 'Drink', 1);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (2, 'Épice / Aromata', 'Spice / Aromata', 1);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (3, 'Gâteau / Pâtisserie', 'Cake / Pastry', 1);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (4, 'Eau', 'Water', 1);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (5, 'Encens / Parfum', 'Incense / Perfume', 1);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (6, 'Humain', 'Human', 2);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (7, 'Mammifère', 'Mammal', 2);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (8, 'Insecte', 'Insect', 2);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (9, 'Oiseau', 'Bird', 2);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (10, 'Poisson', 'Fish', 2);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (11, 'Reptile', 'Reptile', 2);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (12, 'Architrave', 'Architrave', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (13, 'Autel', 'Altar', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (14, 'Banc / Banquette', 'Bench / Couch', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (15, 'Base', 'Base', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (16, 'Bassin', 'Basin', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (17, 'Bloc', 'Block', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (18, 'Brique', 'Brick', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (19, 'Chapiteau ', 'Capital', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (20, 'Colonne', 'Column', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (21, 'Entablement', 'Entablature', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (22, 'Escalier', 'Stairs', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (23, 'Fondation (mur de)', 'Foundation wall', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (24, 'Fontaine', 'Fountain', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (25, 'Fronton', 'Pediment', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (26, 'Linteau', 'Lintel', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (27, 'Mosaïque', 'Mosaic', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (28, 'Mur', 'Wall', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (29, 'Muraille', 'City wall', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (30, 'Naïskos', 'Naiskos', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (31, 'Obélisque', 'Obelisc', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (32, 'Orthostate', 'Orthostate', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (33, 'Pavement', 'Pavement', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (34, 'Podium', 'Podium', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (35, 'Porte', 'Door / Gate', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (36, 'Portique', 'Portico / Stoa', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (37, 'Pylône', 'Pylon', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (38, 'Pyramide', 'Pyramid', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (39, 'Seuil', 'Threshold', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (40, 'Siège', 'Seat', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (41, 'Temple / Chapelle', 'Temple / Shrine', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (42, 'Tuile', 'Tile', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (43, 'Balle de fronde', 'Sling bullet', 4);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (44, 'Bouclier', 'Shield', 4);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (45, 'Casque', 'Helmet', 4);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (46, 'Couteau', 'Knife', 4);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (47, 'Cuirasse', 'Cuirass', 4);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (48, 'Épée', 'Sword', 4);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (49, 'Flèche', 'Arrow', 4);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (50, 'Hache', 'Axe', 4);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (51, 'Jambière', 'Greave', 4);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (52, 'Lance', 'Spear', 4);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (53, 'Massue', 'Club', 4);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (54, 'Trophée', 'Trophy', 4);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (55, 'Amphore', 'Amphora', 6);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (56, 'Pithos / Dolium', 'Pithos / Dolium', 6);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (57, 'Silo', 'Silo', 6);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (58, 'Citerne', 'Cistern', 6);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (59, 'Codex ', 'Codex', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (60, 'Crétule', 'Cretula', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (61, 'Lamelle', 'Lamella', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (62, 'Oscillum', 'Oscillum', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (63, 'Ostrakon', 'Ostrakon', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (64, 'Pinax', 'Pinax', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (65, 'Plaque', 'Plaque', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (66, 'Plaquette', 'Board', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (67, 'Rouleau', 'Scroll', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (68, 'Sceau', 'Seal', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (69, 'Tablette', 'Tablet', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (70, 'Tessère', 'Tessera', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (71, 'Jeton', 'Tocken', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (72, 'Pierre / Élément rocheux', 'Stone / Rock surface', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (73, 'Emblème', 'Emblem', 9);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (74, 'Figurine', 'Figurine', 9);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (75, 'Image divine', 'Divine image', 9);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (76, 'Masque', 'Mask', 9);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (77, 'Partie du corps ', 'Body part', 9);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (78, 'Statue', 'Statue', 9);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (79, 'Statuette', 'Statuette', 9);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (80, 'Bétyle / Monolithe', 'Betyl / Monolith', 9);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (81, 'Cartonnage', 'Cartonnage', 10);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (82, 'Sarcophage', 'Sarcophagus', 10);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (83, 'Urne', 'Cinerary urn', 10);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (84, 'Hipposandale', 'Hipposandal', 11);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (85, 'Œillère', 'Blinker', 11);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (86, 'Mors', 'Bit', 11);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (87, 'Pièce de char', 'Chariot component', 11);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (88, 'Roue', 'Wheel', 11);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (89, 'Harnais', 'Harness', 11);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (90, 'Joug', 'Yoke', 11);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (91, 'Siège', 'Seat', 12);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (92, 'Table', 'Table', 12);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (93, 'Trépied', 'Tripod', 12);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (94, 'Tronc', 'Collecting box', 12);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (95, 'Trône', 'Throne', 12);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (96, 'Banc / Banquette', 'Bench / Couch', 12);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (97, 'Lit', 'Bed', 12);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (98, 'Ancre', 'Anchor', 13);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (99, 'Bateau', 'Boat', 13);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (100, 'Rame', 'Paddle', 13);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (101, 'Rostre', 'Naval ram', 13);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (102, 'Burin', 'Chisel', 14);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (103, 'Clou', 'Nail', 14);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (104, 'Faucille', 'Sickle', 14);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (105, 'Marteau', 'Hammer', 14);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (106, 'Peson', 'Loom weight', 14);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (107, 'Poids', 'Balance weight', 14);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (108, 'Fuseau', 'Spindle', 14);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (109, 'Anneau', 'Ring', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (110, 'Bague', 'Finger ring', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (111, 'Bijou', 'Jewel / Ornament', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (112, 'Boucle d''oreille', 'Earring', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (113, 'Bracelet', 'Bracelet', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (114, 'Broche', 'Brooch / Pin', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (115, 'Camée', 'Cameo', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (116, 'Ceinture', 'Belt', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (117, 'Collier', 'Necklace / Pendant', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (118, 'Couronne', 'Wreath / Crown', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (119, 'Fibule', 'Fibula', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (120, 'Gemme / Joyau', 'Gem', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (121, 'Intaille', 'Intaglio', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (122, 'Médaille ', 'Medal', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (123, 'Miroir', 'Mirror', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (124, 'Peigne', 'Comb', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (125, 'Scarabée', 'Scarab', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (126, 'Vêtement / Tissu', 'Cloth', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (127, 'Disque', 'Disk', 16);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (128, 'Haltère', 'Halter', 16);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (129, 'Strigile', 'Strigil', 16);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (130, 'Borne', 'Milestone / Boundary stone', 17);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (131, 'Cippe', 'Cippus', 17);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (132, 'Stèle', 'Stele', 17);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (133, 'Aryballe', 'Aryballos', 18);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (134, 'Assiette', 'Plate', 18);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (135, 'Bol', 'Bowl', 18);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (136, 'Brûle-parfum', 'Incense-burner ', 18);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (137, 'Canthare ', 'Cantharus', 18);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (138, 'Coupe', 'Cup', 18);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (139, 'Cratère', 'Crater', 18);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (140, 'Cruche', 'Jug', 18);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (141, 'Lampe', 'Lamp', 18);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (142, 'Lécythe', 'Lekythos', 18);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (143, 'Pyxide', 'Pyxis', 18);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (144, 'Vase', 'Vase', 18);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (145, 'Arbre / Élément boisé', 'Tree / Wooden surface', 19);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (146, 'Fleur', 'Flower', 19);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (147, 'Fruit', 'Fruit', 19);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (148, 'Huile', 'Oil', 19);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (149, 'Cloche', 'Bell', 20);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (150, 'Poupée', 'Doll', 21);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (151, 'Toupie', 'Spinning top', 21);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (152, 'Perle', 'Bead', 21);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (153, 'Dé', 'Dice', 21);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (154, 'Indéterminé', 'Undetermined', 1);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (155, 'Indéterminé', 'Undetermined', 2);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (156, 'Indéterminé', 'Undetermined', 3);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (157, 'Indéterminé', 'Undetermined', 4);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (158, 'Indéterminé', 'Undetermined', 5);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (159, 'Indéterminé', 'Undetermined', 6);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (160, 'Indéterminé', 'Undetermined', 7);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (161, 'Indéterminé', 'Undetermined', 8);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (162, 'Indéterminé', 'Undetermined', 9);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (163, 'Indéterminé', 'Undetermined', 10);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (164, 'Indéterminé', 'Undetermined', 11);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (165, 'Indéterminé', 'Undetermined', 12);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (166, 'Indéterminé', 'Undetermined', 13);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (167, 'Indéterminé', 'Undetermined', 14);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (168, 'Indéterminé', 'Undetermined', 15);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (169, 'Indéterminé', 'Undetermined', 16);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (170, 'Indéterminé', 'Undetermined', 17);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (171, 'Indéterminé', 'Undetermined', 18);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (172, 'Indéterminé', 'Undetermined', 19);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (173, 'Indéterminé', 'Undetermined', 20);");
        $this->addSql("INSERT INTO public.materiel(id, nom_fr, nom_en, categorie_materiel_id) VALUES (174, 'Indéterminé', 'Undetermined', 21);");

        // Occasion
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (1, 'Diplomatie', 'Diplomacy', 1);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (2, 'Visite de souverain', 'Sovereign’s visit', 1);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (3, 'Entrée en charge', 'Assumption of office', 1);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (4, 'Sortie de charge', 'Term of office', 1);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (5, 'Assemblée', 'Assembly', 1);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (6, 'Législation', 'Legislation', 1);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (7, 'Conflit territorial / Arbitrage', 'Territorial conflict / Arbitration', 1);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (8, 'Justice', 'Justice', 1);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (9, 'Emprisonnement / Libération', 'Imprisonment / Release', 1);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (10, 'Construction / Fondation', 'Construction / Foundation', 1);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (11, 'Sport / Spectacle', 'Sport / Spectacle', 1);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (12, 'Fête / Banquet', 'Festival / Banquet', 1);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (13, 'Changement de classe d''âge', 'Change of age group', 1);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (14, 'Naissance / Reproduction', 'Birth / Reproduction', 2);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (15, 'Mariage', 'Wedding', 2);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (16, 'Funéraire', 'Funeral', 2);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (17, 'Santé', 'Health', 2);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (18, 'Sexe / Séduction', 'Sex / Seduction', 2);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (19, 'Éducation / Enseignement', 'Education / Teaching', 2);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (20, 'Vol / Perte d''un bien', 'Robbery / Loss of property', 2);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (21, 'Violence', 'Violence', 2);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (22, 'Affranchissement', 'Freeing', 2);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (23, 'Navigation', 'Navigation', 3);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (24, 'Déplacement terrestre', 'Land travel', 3);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (25, 'Semailles / Plantation', 'Sowing / Planting', 4);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (26, 'Labour', 'Ploughing', 4);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (27, 'Récolte / Vendanges', 'Harvest / Grape harvest', 4);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (28, 'Vinification', 'Vinification', 4);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (29, 'Élevage', 'Animal husbandry', 4);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (30, 'Chasse', 'Hunting', 4);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (31, 'Pêche', 'Fishing', 4);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (32, 'Artisanat', 'Craft', 5);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (33, 'Commerce', 'Trade', 5);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (34, 'Achat / Vente / Contrat', 'Buying / Selling / Contract', 5);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (35, 'Bataille', 'Battle', 6);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (36, 'Préparatifs / Entraînement', 'Preparation / Training', 6);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (37, 'Victoire', 'Victory', 6);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (38, 'Défaite', 'Defeat', 6);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (39, 'Commémoration', 'Commemoration', 6);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (40, 'Rêve', 'Dream', 7);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (41, 'Oracle', 'Oracle', 7);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (42, 'Épiphanie', 'Epiphany', 7);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (43, 'Phénomène atmosphérique', 'Atmospheric phenomenon', 8);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (44, 'Éclipse', 'Eclipse', 8);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (45, 'Sécheresse', 'Drought', 8);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (46, 'Éruption volcanique', 'Volcanic eruption', 8);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (47, 'Séisme', 'Earthquake', 8);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (48, 'Inondation', 'Flood', 8);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (49, 'Incendie', 'Fire', 8);");
        $this->addSql("INSERT INTO public.occasion (id, nom_fr, nom_en, categorie_occasion_id) VALUES (50, 'Épidémie / Fléau', 'Epidemic / Plague', 8);");

        // Pratique
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (1, 'Onction', 'Ointment');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (2, 'Arétalogie', 'Aretalogy');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (3, 'Aspersion', 'Aspersion');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (4, 'Asylie', 'Asylia');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (5, 'Chant / Hymne / Incantation', 'Song / Hymn / Incantation');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (6, 'Circumambulation', 'Circumambulatio');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (7, 'Commentaire / Déclaration', 'Commentary / Statement');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (8, 'Consécration', 'Consecration');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (9, 'Construction', 'Construction');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (10, 'Cri rituel', 'Ritual shouting');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (11, 'Danse', 'Dance');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (12, 'Divination', 'Divination');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (13, 'Drame rituel', 'Ritual drama');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (14, 'Exaltation', 'Exaltation');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (15, 'Expiation', 'Expiation');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (16, 'Fabrication d''objet', 'Making of object');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (17, 'Fumigation / Offrande de parfum', 'Fumigation / Perfume offering');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (18, 'Imprécation / Malédiction', 'Imprecation / Curse');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (19, 'Incubation', 'Incubatio');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (20, 'Initiation', 'Initiation');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (21, 'Interdit / Prohibition', 'Prohibition');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (22, 'Jeux / Concours', 'Games / Competition');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (23, 'Juron', 'Swearing');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (24, 'Libation / Offrande liquide', 'Libation / Liquid offering');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (25, 'Mise à mort rituelle', 'Ritual slaughter');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (26, 'Mot d''ordre / Mot de passe', 'Watchword / Password');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (27, 'Musique', 'Music');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (28, 'Offrande', 'Offering');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (29, 'Pèlerinage / Théorie', 'Pilgrimage / Theoria');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (30, 'Possession', 'Possession');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (31, 'Prescription / Injonction', 'Prescription / Command');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (32, 'Procession', 'Procession');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (33, 'Proskynème', 'Proskynema');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (34, 'Purification', 'Purification');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (35, 'Repas', 'Meal');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (36, 'Serment', 'Oath');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (37, 'Supplication', 'Supplication');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (38, 'Théoxénie', 'Theoxenia');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (39, 'Placement / Déplacement', 'Placing / Deplacing');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (40, 'Vœu', 'Vow');");
        $this->addSql("INSERT INTO public.pratique (id, nom_fr, nom_en) VALUES (41, 'Action de grâce', 'Thanksgiving');");

        // Statut affiché
        $this->addSql("INSERT INTO public.statut_affiche (id, nom_fr, nom_en) VALUES (2, 'Esclave', 'Slave');");
        $this->addSql("INSERT INTO public.statut_affiche (id, nom_fr, nom_en) VALUES (1, 'Affranchi', 'Freedman');");
        $this->addSql("INSERT INTO public.statut_affiche (id, nom_fr, nom_en) VALUES (3, 'Étranger', 'Foreigner');");
        $this->addSql("INSERT INTO public.statut_affiche (id, nom_fr, nom_en) VALUES (4, 'Citoyen', 'Citizen');");
        $this->addSql("INSERT INTO public.statut_affiche (id, nom_fr, nom_en) VALUES (5, 'Familial', 'Family');");
        $this->addSql("INSERT INTO public.statut_affiche (id, nom_fr, nom_en) VALUES (6, 'Associatif', 'Associative');");

        // Activité Agent
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (2, 'Artisanat', 'Craft');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (3, 'Commerce', 'Trade');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (4, 'Culte', 'Cult');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (5, 'Guerre', 'War');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (6, 'Justice', 'Justice');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (7, 'Mobilité', 'Mobility');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (8, 'Musique / Théâtre', 'Music / Theatre');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (9, 'Pouvoir / Politique', 'Authority / Politics');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (10, 'Sexualité', 'Sexuality');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (11, 'Santé', 'Health');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (12, 'Savoir', 'Knowledge');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (13, 'Sport', 'Sport');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (14, 'Navigation', 'Navigation');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (1, 'Agriculture', 'Agriculture');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (15, 'Elevage', 'Animal husbandry');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (16, 'Extraction', 'Extraction');");
        $this->addSql("INSERT INTO public.activite_agent (id, nom_fr, nom_en) VALUES (17, 'Pêche / Chasse', 'Fishing / Hunting');");

        // Genre Agent
        $this->addSql("INSERT INTO public.genre (id, nom_fr, nom_en) VALUES (1, 'Masculin', 'Male');");
        $this->addSql("INSERT INTO public.genre (id, nom_fr, nom_en) VALUES (2, 'Féminin', 'Female');");
        $this->addSql("INSERT INTO public.genre (id, nom_fr, nom_en) VALUES (3, 'Non binaire', 'Non binary');");
        $this->addSql("INSERT INTO public.genre (id, nom_fr, nom_en) VALUES (4, 'Indéterminé', 'Undetermined');");

        // Nature Agent
        $this->addSql("INSERT INTO public.nature (id, nom_fr, nom_en) VALUES (1, 'Humain', 'Human');");
        $this->addSql("INSERT INTO public.nature (id, nom_fr, nom_en) VALUES (2, 'Non-humain', 'Non-Human');");
        $this->addSql("INSERT INTO public.nature (id, nom_fr, nom_en) VALUES (3, 'Surhumain', 'Superhuman');");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        // Remove data and reset sequences
        $this->addSql("TRUNCATE agentivite RESTART IDENTITY;");
        $this->addSql("TRUNCATE categorie_occasion RESTART IDENTITY;");
        $this->addSql("TRUNCATE categorie_materiel RESTART IDENTITY;");
        $this->addSql("TRUNCATE materiel RESTART IDENTITY;");
        $this->addSql("TRUNCATE occasion RESTART IDENTITY;");
        $this->addSql("TRUNCATE pratique RESTART IDENTITY;");
        $this->addSql("TRUNCATE statut_affiche RESTART IDENTITY;");
        $this->addSql("TRUNCATE activite_agent RESTART IDENTITY;");
        $this->addSql("TRUNCATE genre RESTART IDENTITY;");
        $this->addSql("TRUNCATE nature RESTART IDENTITY;");
    }
}
