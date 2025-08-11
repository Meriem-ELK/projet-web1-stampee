-- =====================================================
-- BASE DE DONNÉES POUR STAMPEE
-- =====================================================

-- Création de la base de données
CREATE DATABASE stampee_base;
USE stampee_base;

-- =====================================================
-- 1. TABLE PAYS
-- =====================================================
CREATE TABLE pays (
    id_pays INT AUTO_INCREMENT PRIMARY KEY,
    nom_pays VARCHAR(100) UNIQUE NOT NULL
);

-- =====================================================
-- 2. TABLE COULEURS
-- =====================================================
CREATE TABLE couleurs (
    id_couleur INT AUTO_INCREMENT PRIMARY KEY,
    nom_couleur VARCHAR(50) UNIQUE NOT NULL
);

-- =====================================================
-- 3. TABLE CONDITIONS
-- =====================================================
CREATE TABLE conditions (
    id_condition INT AUTO_INCREMENT PRIMARY KEY,
    nom_condition VARCHAR(50) UNIQUE NOT NULL
);

-- =====================================================
-- 4. TABLE UTILISATEURS
-- =====================================================
CREATE TABLE utilisateurs (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- 5. TABLE TIMBRES
-- =====================================================
CREATE TABLE timbres (
    id_timbre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL,
    date_creation YEAR NOT NULL,
    tirage INT,
    dimensions VARCHAR(50),
    certifie BOOLEAN DEFAULT FALSE,
    id_utilisateur_createur INT NOT NULL,
	id_pays_origine INT NOT NULL,
    id_condition INT NOT NULL,
    id_couleur INT NOT NULL,
    description TEXT,
    FOREIGN KEY (id_utilisateur_createur) REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (id_pays_origine) REFERENCES pays(id_pays),
    FOREIGN KEY (id_condition) REFERENCES conditions(id_condition),
    FOREIGN KEY (id_couleur) REFERENCES couleurs(id_couleur)
);


-- =====================================================
-- 6. TABLE IMAGES_TIMBRES
-- =====================================================
CREATE TABLE images_timbres (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_timbre INT NOT NULL,
    chemin_image VARCHAR(255) NOT NULL,
    ordre_affichage INT DEFAULT 1,
    FOREIGN KEY (id_timbre) REFERENCES timbres(id_timbre) ON DELETE CASCADE
);

-- =====================================================
-- 7. TABLE ENCHÈRES
-- =====================================================
CREATE TABLE encheres (
    id_enchere INT AUTO_INCREMENT PRIMARY KEY,
    id_timbre INT NOT NULL,
    prix_plancher DECIMAL(10,2) NOT NULL,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    coup_coeur_lord BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_timbre) REFERENCES timbres(id_timbre) ON DELETE CASCADE
);

-- =====================================================
-- 8. TABLE MISES
-- =====================================================
CREATE TABLE mises (
    id_mise INT AUTO_INCREMENT PRIMARY KEY,
    id_enchere INT NOT NULL,
    id_utilisateur INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    date_mise TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_enchere) REFERENCES encheres(id_enchere) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE
);

-- =====================================================
-- 9. TABLE FAVORIS
-- =====================================================
CREATE TABLE favoris (
    id_utilisateur INT NOT NULL,
    id_enchere INT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_utilisateur, id_enchere),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (id_enchere) REFERENCES encheres(id_enchere) ON DELETE CASCADE
);

-- =====================================================
-- 10. TABLE COMMENTAIRES
-- =====================================================
CREATE TABLE commentaires (
    id_commentaire INT AUTO_INCREMENT PRIMARY KEY,
    id_enchere INT NOT NULL,
    id_utilisateur INT NOT NULL,
    contenu TEXT NOT NULL,
    date_commentaire TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_enchere) REFERENCES encheres(id_enchere) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE
);


-- =====================================================
-- INSERTION DE DONNÉES DE BASE
-- =====================================================

INSERT INTO pays (nom_pays) VALUES
('France'), ('Canada'), ('États-Unis'), ('Japon'), ('Allemagne');

INSERT INTO couleurs (nom_couleur) VALUES
('Rouge'), ('Bleu'), ('Vert'), ('Jaune'), ('Noir');

INSERT INTO conditions (nom_condition) VALUES
('Parfaite'), ('Excellente'), ('Bonne'), ('Moyenne'), ('Endommagé');
