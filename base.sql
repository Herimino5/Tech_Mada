CREATE DATABASE bibliotheque
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE bibliotheque;

CREATE TABLE livres (
    id INT AUTO_INCREMENT PRIMARY KEY,

    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(255) NOT NULL,

    isbn VARCHAR(50) NOT NULL UNIQUE,

    annee INT NOT NULL,
    categorie VARCHAR(100),

    resume TEXT,

    couverture VARCHAR(255),

    statut ENUM('disponible', 'prete') DEFAULT 'disponible',

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE emprunts (
    id INT AUTO_INCREMENT PRIMARY KEY,

    livre_id INT NOT NULL,
    nom_emprunteur VARCHAR(255) NOT NULL,

    date_emprunt DATE NOT NULL,
    date_retour DATE NULL,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (livre_id) REFERENCES livres(id)
    ON DELETE CASCADE
);