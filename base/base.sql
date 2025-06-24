CREATE DATABASE IF NOT EXISTS CRM;

USE CRM;

-- Table des départements
CREATE TABLE departements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des utilisateurs
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'utilisateur') DEFAULT 'utilisateur',
    id_departement INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (id_departement) REFERENCES departements(id) -- à activer si la table existe
);



-- Table des catégories de transactions
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    type ENUM('depense', 'gain') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des prévisions
CREATE TABLE previsions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    id_departement INT NOT NULL,
    solde_depart DECIMAL(10, 2) NOT NULL,
    statut ENUM('en_attente', 'validee', 'rejetee') DEFAULT 'en_attente',
    mois INT NOT NULL,
    annee INT NOT NULL,
    FOREIGN KEY (id_departement) REFERENCES departements(id)
);



-- Table des détails des prévisions
CREATE TABLE detailsPrevisions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_prevision INT NOT NULL,
    id_categorie INT NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_prevision) REFERENCES previsions(id),
    FOREIGN KEY (id_categorie) REFERENCES categories(id)
);


-- Table des réalisations
CREATE TABLE realisations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    id_departement INT NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    statut ENUM('en_attente', 'validee', 'rejetee') DEFAULT 'en_attente',
    mois INT NOT NULL,
    annee INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_departement) REFERENCES departements(id)
);

-- Table des détails des réalisations
CREATE TABLE detailsRealisations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_realisation INT NOT NULL,
    id_categorie INT NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_realisation) REFERENCES realisations(id),
    FOREIGN KEY (id_categorie) REFERENCES categories(id)
);

-- Table des catégories de produits
CREATE TABLE categorieProduit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    id_categorie INT NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorieProduit(id)
);
-- Table des stocks
CREATE TABLE stocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_produit INT NOT NULL,
    quantite INT NOT NULL,
    derniere_mise_a_jour TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produit) REFERENCES produits(id)
);




-- Table des catégories de clients
CREATE TABLE categoriesClient (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des clients
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telephone VARCHAR(20),
    id_categorie INT NOT NULL,
    adresse TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES categoriesClient(id)
);




-- Table des actions (CRM)
CREATE TABLE actions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    id_client INT NOT NULL,
    date_action DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('En attente', 'En cours', 'Terminée') NOT NULL DEFAULT 'En attente',
    description TEXT,
    FOREIGN KEY (id_client) REFERENCES clients(id)
);


-- Table des ventes
CREATE TABLE ventes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    date_vente TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_client) REFERENCES clients(id)
);

-- Table des détails des ventes
CREATE TABLE detailsVentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_vente INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_vente) REFERENCES ventes(id),
    FOREIGN KEY (id_produit) REFERENCES produits(id)
);

INSERT INTO departements (nom) VALUES
  ('Entreprise'),
  ('Marketing'),
  ('Gestion'),
  ('Comptabilité'),
  ('Ressources Humaines'),
  ('Finance'),
  ('Logistique'),
  ('Communication');

-- Catégories de type 'depense'
INSERT INTO categories (nom, type) VALUES
  ('Salaire Employés', 'depense'),
  ('Achat Fournitures', 'depense'),
  ('Loyer', 'depense'),
  ('Factures', 'depense'),
  ('Maintenance', 'depense');

-- Catégories de type 'gain'
INSERT INTO categories (nom, type) VALUES
  ('Vente Produits', 'gain'),
  ('Service Rendu', 'gain'),
  ('Investissement', 'gain'),
  ('Remboursement Client', 'gain');

-- budget pour le CRM
CREATE TABLE crmBudget (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_departement INT NOT NULL,
    mois INT NOT NULL,
    annee INT NOT NULL,
    status ENUM('En attente', 'Terminée') NOT NULL DEFAULT 'En attente',
    montant DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_departement) REFERENCES departements(id)    
);


ALTER TABLE crmBudget
ADD COLUMN description TEXT,
ADD COLUMN id_action INT NOT NULL,
ADD FOREIGN KEY (id_action) REFERENCES actions(id);

ALTER TABLE actions
MODIFY COLUMN status ENUM('En attente', 'En cours', 'Terminée') NOT NULL DEFAULT 'En attente';

INSERT INTO categories (nom, type) VALUES
  ('CRM', 'depense');