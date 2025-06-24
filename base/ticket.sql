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
    statut ENUM('ouvert', 'en_cours', 'resolu', 'ferme') DEFAULT 'ouvert',
    priorite ENUM('basse', 'moyenne', 'haute') DEFAULT 'basse',
    date_ouverture TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    THoraire DOUBLE NOT NULL,
    etoiles INT DEFAULT NULL,
    date_heure_debut DATETIME DEFAULT NOT NULL,
    date_heure_fin DATETIME DEFAULT NOT NULL,
    fichier_url VARCHAR(500) DEFAULT NULL;
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

CREATE TABLE message_client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    id_ticket INT,
    message TEXT NOT NULL,
    fichier_url VARCHAR(500) DEFAULT NULL;
    date_message TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES clients(id)
);

CREATE TABLE fermetureticket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jour INT NOT NULL
);

CREATE TABLE commentaire_message (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_message_client INT,
    id_utilisateur INT,
    auteur ENUM('client', 'agent') NOT NULL,
    commentaire TEXT NOT NULL,
    date_commentaire TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_message_client) REFERENCES message_client(id),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id)
);

