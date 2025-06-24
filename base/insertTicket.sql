INSERT INTO groupes (nom, description) VALUES
('Support Technique', 'equipe chargee de resoudre les problèmes techniques'),
('Facturation', 'equipe chargee de la facturation et des paiements');

INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, id_departement, id_groupe) VALUES
('RijaT', 'RijaT', 'rijatecnique@gmail.com', '$2y$10$bI9mmoDr3ca11JgQe7Wfk.HffdoypH.RBILQ51crz0jwUoQk47SLq', 'agent', 1, 1);

INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, id_departement, id_groupe) VALUES
('FaraFacturation', 'FaraFacturation', 'FaraFacturationecnique@gmail.com', '$2y$10$bI9mmoDr3ca11JgQe7Wfk.HffdoypH.RBILQ51crz0jwUoQk47SLq', 'agent', 1, 2);

INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, id_departement, id_groupe) VALUES
('MamyFacturation', 'MamyFacturation', 'MamyFacturationecnique@gmail.com', '$2y$10$bI9mmoDr3ca11JgQe7Wfk.HffdoypH.RBILQ51crz0jwUoQk47SLq', 'agent', 1, 2);

INSERT INTO ticket_categories (nom, description)
VALUES ('Problème de facturation', 'Erreurs sur les montants ou les paiements');

INSERT INTO tickets (
    titre, description, id_client, id_categorie, id_groupe, priorite, statut
) VALUES (
    'Impossible de lancer l application',
    'L application plante dès le demarrage sur Windows 11.',
    5,  -- client id
    1,  -- Problème technique
    1,  -- Groupe Support Technique
    'haute',
    'nouveau'
);

INSERT INTO tickets (
    titre, description, id_client, id_categorie, priorite, statut
) VALUES (
    'test',
    'test',
    6,  -- client id
    1,  -- Problème de facturation
    'moyenne',
    'nouveau'
);


-- Agent Fara (id = 3) repond au ticket de facturation (id = 2)
INSERT INTO ticket_reponses (id_ticket, id_utilisateur, auteur, message)
VALUES (2, 3, 'agent', 'Nous analysons votre facture, merci de patienter.');

-- Agent Mamy (id = 4) complète
INSERT INTO ticket_reponses (id_ticket, id_utilisateur, auteur, message)
VALUES (2, 4, 'agent', 'Problème identifie. Nous allons corriger cela immediatement.');

ALTER TABLE tickets 
MODIFY statut ENUM('nouveau', 'en_attente', 'en_cours', 'resolu', 'ferme') DEFAULT 'nouveau';
