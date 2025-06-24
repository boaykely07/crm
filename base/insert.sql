INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, id_departement) VALUES
('Admin', 'ADMN', 'admin@gmail.com', '$2y$10$abc123', 'admin', 1),
('Razafindrakoto', 'Marie', 'marie.raza@company.mg', '$2y$10$def456', 'utilisateur', 2),
('Randrianarisoa', 'Paul', 'paul.randria@company.mg', '$2y$10$ghi789', 'utilisateur', 1);

INSERT INTO categorieProduit (nom) VALUES
('Ordinateurs portables'),
('Périphériques'),
('Composants PC'),
('Réseaux et connectiques');

INSERT INTO categoriesClient (nom, description) VALUES
('Particulier', 'Clients individuels'),
('Entreprise', 'Clients professionnels'),
('Revendeur', 'Distributeurs et revendeurs');

INSERT INTO produits (nom, id_categorie, prix_unitaire) VALUES
-- Ordinateurs portables (catégorie 1)
('Laptop HP ProBook', 1, 1200000),
('Laptop Dell Latitude', 1, 1500000),
('Laptop Lenovo ThinkPad', 1, 1800000),
('MacBook Air', 1, 2500000),
('Laptop Asus ZenBook', 1, 1600000),
('Laptop Acer Swift', 1, 1300000),
('MacBook Pro', 1, 3500000),
('Laptop Gaming MSI', 1, 2200000),

-- Périphériques (catégorie 2)
('Souris sans fil Logitech', 2, 45000),
('Clavier mécanique', 2, 120000),
('Écran 24" HP', 2, 450000),
('Imprimante Canon', 2, 350000),
('Webcam HD', 2, 85000),
('Scanner Epson', 2, 280000),
('Casque Gaming', 2, 150000),
('Haut-parleurs', 2, 95000),

-- Composants PC (catégorie 3)
('Processeur Intel i5', 3, 550000),
('RAM 8GB DDR4', 3, 120000),
('SSD 500GB', 3, 180000),
('Carte graphique GTX 1660', 3, 850000),
('Alimentation 650W', 3, 200000),
('Carte mère ASUS', 3, 450000),
('Boitier PC Gaming', 3, 180000),
('Ventilateur CPU', 3, 75000),

-- Réseaux et connectiques (catégorie 4)
('Routeur WiFi', 4, 150000),
('Switch 8 ports', 4, 85000),
('Câble réseau 5m', 4, 15000),
('Adaptateur WiFi USB', 4, 45000),
('Onduleur 1000VA', 4, 350000),
('Hub USB 3.0', 4, 65000),
('Point d accès WiFi', 4, 180000),
('Carte réseau PCIe', 4, 95000);

INSERT INTO stocks (id_produit, quantite)
SELECT id, 50 FROM produits;

INSERT INTO clients (nom, email, telephone, id_categorie, adresse) VALUES
('tsiory', 'tsioryvahyarabearivony@email.com', '0341234567', 1, 'Analakely, Antananarivo'),
('SARL TechPlus', 'contact@techplus.mg', '0331234567', 2, 'Andraharo, Antananarivo'),
('InfoStore', 'info@infostore.mg', '0321234567', 3, 'Ankorondrano, Antananarivo'),
('Rasoa Marie', 'rasoa.marie@email.com', '0344567890', 1, 'Tsaralalàna, Antananarivo'),
('Digital Solutions', 'contact@digitalsolutions.mg', '0334567890', 2, 'Tanjombato, Antananarivo'),
('Rabe William', 'rabe.william@email.com', '0342345678', 1, 'Ivandry, Antananarivo'),
('Cyber Services', 'info@cyberservices.mg', '0332345678', 2, 'Antanimena, Antananarivo'),
('Tech Distribution', 'contact@techdistribution.mg', '0333456789', 3, 'Antsirabe'),
('Informatique Plus', 'info@infplus.mg', '0341234568', 3, 'Toamasina'),
('Randria Sophie', 'randria.sophie@email.com', '0347890123', 1, 'Majunga');


-- Ventes pour Juillet 2022
INSERT INTO ventes (id_client, date_vente, total) VALUES
(1, '2022-07-03 09:15:00', 1800000),
(3, '2022-07-03 14:30:00', 2500000),
(2, '2022-07-04 10:45:00', 450000),
(4, '2022-07-05 11:20:00', 1650000),
(5, '2022-07-06 09:30:00', 3500000),
(2, '2022-07-07 16:15:00', 180000),
(6, '2022-07-10 13:45:00', 2200000),
(1, '2022-07-11 10:30:00', 95000),
(7, '2022-07-12 14:20:00', 850000),
(8, '2022-07-13 15:40:00', 1500000);

-- Détails des ventes pour Juillet 2022
INSERT INTO detailsVentes (id_vente, id_produit, quantite, prix_unitaire) VALUES
(1, 1, 2, 1200000),  -- 1 Laptop HP ProBook
(1, 9, 3, 45000),    -- 2 Souris Logitech
(2, 4, 5, 2500000),  -- 1 MacBook Air
(3, 11, 1, 450000),  -- 1 Écran 24" HP
(4, 2, 2, 1500000),  -- 1 Laptop Dell Latitude
(4, 10, 9, 120000),  -- 1 Clavier mécanique
(5, 7, 1, 3500000),  -- 1 MacBook Pro
(6, 19, 7, 180000),  -- 1 SSD 500GB
(7, 8, 1, 2200000),  -- 1 Laptop Gaming MSI
(8, 16, 4, 95000),   -- 1 Haut-parleurs
(9, 20, 1, 850000),  -- 1 Carte graphique GTX 1660
(10, 2, 1, 1500000); -- 1 Laptop Dell Latitude

-- Ventes pour Aout 2022
INSERT INTO ventes (id_client, date_vente, total) VALUES
(3, '2022-08-01 09:00:00', 2850000),
(5, '2022-08-02 10:30:00', 1600000),
(2, '2022-08-03 11:15:00', 350000),
(7, '2022-08-04 14:20:00', 1300000),
(4, '2022-08-07 09:45:00', 2200000),
(9, '2022-08-08 16:30:00', 180000);


-- Détails des ventes pour Février 2022
INSERT INTO detailsVentes (id_vente, id_produit, quantite, prix_unitaire) VALUES
(11, 3, 4, 1800000), -- 1 Laptop Lenovo ThinkPad
(11, 13, 1, 85000),  -- 1 Webcam HD
(12, 5, 3, 1600000), -- 1 Laptop Asus ZenBook
(13, 12, 2, 350000), -- 1 Imprimante Canon
(14, 6, 1, 1300000), -- 1 Laptop Acer Swift
(15, 8, 1, 2200000), -- 1 Laptop Gaming MSI
(16, 19, 10, 180000); -- 1 SSD 500GB

-- Mettre à jour les stocks après les ventes
UPDATE stocks s
INNER JOIN (
    SELECT id_produit, SUM(quantite) as total_vendu
    FROM detailsVentes
    GROUP BY id_produit
) v ON s.id_produit = v.id_produit
SET s.quantite = s.quantite - v.total_vendu;


INSERT INTO actions (titre, id_client, date_action, status, description) VALUES
-- Actions pour les clients entreprises
('Promotion pack Laptop et Périphériques', 2, '2022-03-10', 'En attente', 
'SARL TechPlus a fait plusieurs achats de laptops. Proposition d''une offre combinée : Laptop Dell Latitude + Périphériques avec 15% de remise.'),

('Programme fidélité Entreprise', 5, '2022-03-15', 'En attente', 
'Digital Solutions est un client régulier. Proposer une remise de 20% sur leur prochaine commande d''équipements réseau.'),

('Offre spéciale Composants PC', 8, '2022-03-20', 'En attente', 
'Tech Distribution, en tant que revendeur, pourrait être intéressé par une offre groupée sur les composants PC avec 12% de remise.'),

-- Actions pour les clients particuliers
('Bienvenue nouveau client', 4, '2022-03-25', 'En attente', 
'Rasoa Marie est une nouvelle cliente. Proposer une remise de 10% sur sa prochaine commande de périphériques.'),

('Promotion accessoires gaming', 6, '2022-04-05', 'En attente', 
'Rabe William a acheté un Laptop Gaming MSI. Proposer une offre sur les accessoires gaming (casque, souris, clavier).'),

-- Actions pour les revendeurs
('Offre stock revendeur', 3, '2022-04-10', 'En attente', 
'InfoStore peut bénéficier d''une remise de 18% sur les commandes en gros de plus de 10 laptops.'),

('Programme partenaire revendeur', 9, '2022-04-15', 'En attente', 
'Informatique Plus pourrait être intéressé par notre nouveau programme de partenariat avec des remises progressives.'),

-- Suivis clients
('Suivi satisfaction client', 1, '2022-04-20', 'En attente', 
'Contacter Rakoto Jean pour un retour sur sa dernière commande d''ordinateur portable.'),

('Proposition maintenance préventive', 5, '2022-04-25', 'En attente', 
'Digital Solutions a un parc important. Proposer un contrat de maintenance préventive.'),

('Renouvellement parc informatique', 2, '2022-05-01', 'En attente', 
'SARL TechPlus approche de la date anniversaire de ses achats. Proposer un plan de renouvellement de leur parc informatique.');

-- Actions pour les événements spéciaux
INSERT INTO actions (titre, id_client, date_action, status, description) VALUES
('Promotion rentrée scolaire', 7, '2022-05-05', 'En attente', 
'Université et Ecole pourrait être intéressé par nos offres spéciales pour la rentrée scolaire sur les laptops d''entrée de gamme.'),

('Black Friday - Offres spéciales', 10, '2022-05-10', 'En attente', 
'Préparer une offre personnalisée pour Randria Sophie sur les produits high-tech pendant le Black Friday.'),

('Promotion fin d''année', 3, '2022-05-15', 'En attente', 
'Proposer à InfoStore des remises importantes sur les stocks de fin d''année pour les périphériques et composants.'),

('Lancement nouvelle gamme', 8, '2022-05-20', 'En attente', 
'Informer Tech Distribution de l''arrivée de notre nouvelle gamme de produits gaming avec tarifs préférentiels.');
