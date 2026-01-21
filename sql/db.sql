-- Création de la db
CREATE DATABASE tmt_db;

-- Création des tables
CREATE TABLE comptes (
    id_compte INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);

CREATE TABLE profils (
    id_profil INT PRIMARY KEY AUTO_INCREMENT,
    id_compte INT,
    nom_utilisateur VARCHAR(255) NOT NULL UNIQUE,
    url_photo_profil VARCHAR(500) NOT NULL,
    url_banniere VARCHAR(500) NOT NULL,
    description_profil VARCHAR(255) NOT NULL,
    numero_telephone VARCHAR(20),
    FOREIGN KEY (id_compte) REFERENCES comptes(id_compte)
);

CREATE TABLE traductions (
    id_traduction INT PRIMARY KEY AUTO_INCREMENT,
    id_profil INT,
    nom_traduction VARCHAR(255) NOT NULL,
    url_file_format_img VARCHAR(500) NOT NULL,
    lien_lecteur VARCHAR(255) NOT NULL,
    type_traduction VARCHAR(255) NOT NULL,
    style_traduction VARCHAR(255) NOT NULL,
    auteur VARCHAR(255) NOT NULL,
    langue VARCHAR(255) NOT NULL,
    date_publication DATE NOT NULL,
    FOREIGN KEY (id_profil) REFERENCES profils(id_profil)
);