
INSERT INTO ventes (id_client, date_vente, total) VALUES
(1, '2022-09-03 09:15:00', 1800000),
(3, '2022-09-03 14:30:00', 2500000),
(2, '2022-09-04 10:45:00', 450000),
(4, '2022-09-05 11:20:00', 1650000),
(5, '2022-09-06 09:30:00', 3500000),
(2, '2022-09-07 16:15:00', 180000),
(6, '2022-09-10 13:45:00', 2200000),
(1, '2022-09-11 10:30:00', 95000),
(7, '2022-09-12 14:20:00', 850000),
(8, '2022-09-13 15:40:00', 1500000);

-- Détails des ventes pour Septembre 2022
INSERT INTO detailsVentes (id_vente, id_produit, quantite, prix_unitaire) VALUES
(17, 1, 1, 1200000),  -- 1 Laptop HP ProBook
(18, 9, 15, 45000),    -- 2 Souris Logitech
(19, 4, 1, 2500000),  -- 1 MacBook Air
(20, 11, 10, 450000),  -- 1 Écran 24" HP
(21, 2, 30, 1500000),  -- 1 Laptop Dell Latitude
(22, 10, 15, 120000),  -- 1 Clavier mécanique
(23, 7, 1, 3500000),  -- 1 MacBook Pro
(24, 19, 7, 180000),  -- 1 SSD 500GB
(25, 8, 1, 2200000),  -- 1 Laptop Gaming MSI
(26, 16, 13, 95000);   -- 1 Haut-parleurs

UPDATE stocks s
INNER JOIN (
    SELECT id_produit, SUM(quantite) as total_vendu
    FROM detailsVentes
    GROUP BY id_produit
) v ON s.id_produit = v.id_produit
SET s.quantite = s.quantite - v.total_vendu;

