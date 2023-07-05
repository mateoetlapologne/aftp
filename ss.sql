CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photovictim VARCHAR(255),
    preuve1 VARCHAR(255),
    preuve2 VARCHAR(255),
    preuve3 VARCHAR(255),
    nom VARCHAR(100),
    prenom VARCHAR(100),
    date_naissance DATE,
    ville VARCHAR(100),
    adresse VARCHAR(255),
    numero VARCHAR(10),
    reseaux_sociaux VARCHAR(255),
    pseudo VARCHAR(20),
    infos TEXT
    ip VARCHAR(255),
);
