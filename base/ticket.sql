CREATE TABLE groupes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT
);

ALTER TABLE utilisateurs
MODIFY role ENUM('admin', 'utilisateur', 'agent') DEFAULT 'utilisateur';

ALTER TABLE utilisateurs
ADD COLUMN id_groupe INT DEFAULT NULL,
ADD FOREIGN KEY (id_groupe) REFERENCES groupes(id);


CREATE TABLE ticket_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    id_client INT NOT NULL,
    id_categorie INT NOT NULL,
    id_agent INT DEFAULT NULL,         -- pour gestion individuelle
    id_groupe INT DEFAULT NULL,        -- pour gestion en équipe
    statut ENUM('nouveau', 'en_attente', 'en_cours', 'resolu', 'ferme') DEFAULT 'nouveau',
    priorite ENUM('basse', 'moyenne', 'haute') DEFAULT 'moyenne',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES clients(id),
    FOREIGN KEY (id_categorie) REFERENCES ticket_categories(id),
    FOREIGN KEY (id_agent) REFERENCES utilisateurs(id),
    FOREIGN KEY (id_groupe) REFERENCES groupes(id)
);


CREATE TABLE ticket_reponses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT NOT NULL,
    id_utilisateur INT NOT NULL,
    auteur ENUM('client', 'agent') NOT NULL,
    message TEXT NOT NULL,
    date_reponse TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ticket) REFERENCES tickets(id),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id)
);
