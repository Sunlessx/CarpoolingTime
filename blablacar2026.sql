-- ==========================================================================
-- Base BLACLACAR pour le projet 2026
-- Corrigé pour Imad and Huy
-- ==========================================================================

DROP TABLE IF EXISTS `reservation`;
DROP TABLE IF EXISTS `trajet`;
DROP TABLE IF EXISTS `ville`;
DROP TABLE IF EXISTS `vehicule`;
DROP TABLE IF EXISTS `utilisateur`;

-- ==========================================================================
-- Table pour les utilisateurs
-- ==========================================================================

create table if not exists utilisateur (
 id int not null,
 nom varchar(40) not null,
 prenom varchar(40) not null,
 role enum('administrateur', 'conducteur', 'passager'), 
 login varchar(20) not null,
 password varchar(20) not null,
 solde decimal(8,2), 
 primary key (id) 
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Balances initiales augmentées à 1000.00 pour éviter les soldes négatifs au départ
insert into utilisateur values (0, 'Le', 'Boss', 'administrateur', 'boss', 'secret', 0.0);
insert into utilisateur values (101, 'Tris', 'Prior', 'conducteur', 'trisprior', 'secret', 1000.0);
insert into utilisateur values (102, 'Four', 'Eaton', 'conducteur', 'foureaton', 'secret', 1000.0);
insert into utilisateur values (103, 'Jeanine', 'Matthews', 'conducteur', 'jeaninematthews', 'secret', 1000.0);
insert into utilisateur values (104, 'Marc', 'Lem', 'conducteur', 'marclem', 'secret', 1000.0);

insert into utilisateur values (201, 'Caleb', 'Prior', 'passager', 'calebprior', 'secret', 1000.0);
insert into utilisateur values (202, 'Christina', 'Nobody', 'passager', 'christinanobody', 'secret', 1000.0);
insert into utilisateur values (203, 'Peter', 'Hayes', 'passager', 'peterhayes', 'secret', 1000.0);
insert into utilisateur values (204, 'Eric', 'Coulter', 'passager', 'ericcoulter', 'secret', 1000.0);
insert into utilisateur values (205, 'Tori', 'Wu', 'passager', 'toriwu', 'secret', 1000.0);
insert into utilisateur values (206, 'Marcus', 'Eaton', 'passager', 'marcuseaton', 'secret', 1000.0);
insert into utilisateur values (207, 'Kang', 'Jack', 'passager', 'jackkang', 'secret', 1000.0);

insert into utilisateur values (301,  'GAILLARD', 'Paul', 'passager', 'paulgaillard', 'secret', 1000.0);
insert into utilisateur values (302,  'LERMINIAUX', 'Christian', 'passager', 'christianlerminiaux', 'secret', 1000.0);
insert into utilisateur values (303,  'KOCH', 'Pierre', 'passager', 'pierrekoch', 'secret', 1000.0);
insert into utilisateur values (304,  'COLLET', 'Christophe', 'passager', 'christophecollet', 'secret', 1000.0);

insert into utilisateur values (401, 'VENTURA', 'Lino', 'passager', 'ventura', 'secret', 1500.0);
insert into utilisateur values (402, 'DELON', 'Alain', 'passager', 'delon', 'secret', 1500.0);
insert into utilisateur values (403, 'GABIN', 'Jean', 'passager', 'gabin', 'secret', 1500.0);
insert into utilisateur values (404, 'HUPPERT', 'Isabelle', 'passager', 'huppert', 'secret', 1500.0);
insert into utilisateur values (405, 'COTILLARD', 'Marion', 'passager', 'cotillard', 'secret', 1500.0);
insert into utilisateur values (406, 'DEPP', 'Lily Rose', 'passager', 'depp', 'secret', 1500.0);

-- ==========================================================================
-- Table pour les véhicules
-- ==========================================================================

create table if not exists vehicule (
    id int primary key,
    marque varchar(255) not null,
    modele varchar(255) not null,
    annee int not null,
    immatriculation varchar(20) not null,
    proprietaire_id integer not null,
    foreign key (proprietaire_id) references utilisateur(id)
);

insert into vehicule values (1, 'peugeot', 'p3008', 2020, 'ab-123-cd', 101); 
insert into vehicule values (2, 'renault', 'clio', 2019, 'cd-456-ef', 101); 
insert into vehicule values (3, 'citroën', 'c4', 2018, 'ef-789-gh', 101); 
insert into vehicule values (4, 'toyota', 'yaris', 2017, 'gh-123-ij', 102); 
insert into vehicule values (5, 'volkswagen', 'golf', 2016, 'ij-456-kl', 102); 
insert into vehicule values (6, 'bmw', 'série 3', 2015, 'kl-789-mn', 102); 
insert into vehicule values (7, 'mercedes', 'classe c', 2014, 'mn-123-op', 103); 
insert into vehicule values (8, 'audi', 'a4', 2013, 'op-456-qr', 103); 
insert into vehicule values (9, 'ford', 'focus', 2012, 'qr-789-st', 103); 
insert into vehicule values(10, 'opel', 'astra', 2011, 'st-123-uv', 103);

-- ==========================================================================
-- Table pour les villes
-- ==========================================================================

create table if not exists ville (
    id int primary key,
    nom varchar(255) not null unique
);

insert into ville values (1, "paris");
insert into ville values (2, "marseille");
insert into ville values (3, "lyon");
insert into ville values (4, "toulouse");
insert into ville values (5, "nice");
insert into ville values (6, "nantes");
insert into ville values (7, "montpellier");
insert into ville values (8, "strasbourg");
insert into ville values (9, "bordeaux");
insert into ville values (10, "lille");
insert into ville values (11, "rennes");
insert into ville values (12, "reims");
insert into ville values (13, "le havre");
insert into ville values (14, "saint-étienne");
insert into ville values (15, "toulon");
insert into ville values (16, "grenoble");
insert into ville values (17, "dijon");
insert into ville values (18, "angers");
insert into ville values (19, "villeurbanne");
insert into ville values (20, "saint-denis");
insert into ville values (21, "le mans");
insert into ville values (22, "aix-en-provence");
insert into ville values (23, "clermont-ferrand");
insert into ville values (24, "brest");
insert into ville values (25, "limoges");
insert into ville values (26, "tours");
insert into ville values (27, "amiens");
insert into ville values (28, "perpignan");
insert into ville values (29, "metz");
insert into ville values (30, "besançon");
insert into ville values (31, "boulogne-billancourt");
insert into ville values (32, "orléans");
insert into ville values (33, "mulhouse");
insert into ville values (34, "rouen");
insert into ville values (35, "caen");
insert into ville values (36, "argenteuil");
insert into ville values (37, "montreuil");
insert into ville values (38, "nancy");
insert into ville values (39, "roubaix");
insert into ville values (40, "tourcoing");
insert into ville values (41, "nanterre");
insert into ville values (42, "avignon");
insert into ville values (43, "vitry-sur-seine");
insert into ville values (44, "créteil");
insert into ville values (45, "dunkerque");
insert into ville values (46, "poitiers");
insert into ville values (47, "asnières-sur-seine");
insert into ville values (48, "courbevoie");
insert into ville values (49, "fort-de-france");
insert into ville values (50, "colombes");

-- ==========================================================================
-- Table pour les trajets
-- ==========================================================================

create table if not exists trajet (
    id int primary key,
    ville_depart int not null,
    ville_arrivee int not null,
    conducteur_id int not null,
    vehicule_id int not null,
    prix decimal(10, 2) not null,
    date_depart date not null,
    heure_depart time not null,
    statut enum('actif', 'passif') not null,
    foreign key (ville_depart) references ville(id),
    foreign key (ville_arrivee) references ville(id),
    foreign key (conducteur_id) references utilisateur(id),
    foreign key (vehicule_id) references vehicule(id)
);

-- Trajets actifs pour permettre les réservations immédiates
INSERT INTO trajet  VALUES (1, 28, 36, 103, 1, 10.0, '2026-07-10', '13:00:00', 'actif');
INSERT INTO trajet  VALUES (2, 44, 39, 102, 8,  15.0, '2026-07-15', '08:00:00', 'actif');
INSERT INTO trajet  VALUES (3, 39, 40, 103, 1,  20.0, '2026-07-20', '11:00:00', 'actif');
INSERT INTO trajet  VALUES (4, 30, 15, 101, 5,  25.0, '2026-07-25', '13:00:00', 'actif');
INSERT INTO trajet  VALUES (5, 31, 22, 103, 5, 30.0, '2026-07-30', '17:00:00', 'actif');

-- ==========================================================================
-- Table pour les réservations
-- ==========================================================================

create table if not exists reservation (
    id int primary key,
    trajet_id int not null,
    passager_id int  not null,
    foreign key (trajet_id) references trajet(id),
    foreign key (passager_id) references utilisateur(id)
);

-- Note: Aucune réservation initiale pour que Solde Réel == Solde Virtuel au départ.
