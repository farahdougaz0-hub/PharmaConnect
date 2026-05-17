

CREATE DATABASE IF NOT EXISTS pharmaconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('client', 'admin') DEFAULT 'client',
    telephone VARCHAR(20),
    adresse TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS medicaments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    quantite INT DEFAULT 0,
    image VARCHAR(255),
    categorie_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE SET NULL
);



CREATE TABLE IF NOT EXISTS commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'confirmee', 'livree', 'annulee') DEFAULT 'en_attente',
    total DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS details_commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    medicament_id INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
    FOREIGN KEY (medicament_id) REFERENCES medicaments(id) ON DELETE CASCADE
);



CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    medicament_id INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_souhaitee DATE,
    statut ENUM('en_attente', 'confirmee', 'annulee') DEFAULT 'en_attente',
    notes TEXT,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (medicament_id) REFERENCES medicaments(id) ON DELETE CASCADE
);




INSERT INTO utilisateurs (nom, email, password, role) VALUES
('Admin PharmaConnect', 'admin@pharma.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');


INSERT INTO categories (nom, description) VALUES
('Antibiotiques', 'Médicaments contre les infections bactériennes'),
('Antidouleurs', 'Médicaments contre la douleur'),
('Vitamines', 'Suppléments vitaminiques'),
('Antiallergiques', 'Médicaments contre les allergies'),
('Gastroentérologie', 'Médicaments digestifs');


INSERT INTO medicaments (nom, description, prix, quantite, categorie_id) VALUES
('Amoxicilline 500mg', 'Antibiotique à large spectre', 8.50, 100, 1),
('Paracétamol 1g', 'Antidouleur et antipyrétique', 3.20, 200, 2),
('Ibuprofène 400mg', 'Anti-inflammatoire non stéroïdien', 5.80, 150, 2),
('Vitamine C 1000mg', 'Supplément en vitamine C', 12.00, 80, 3),
('Loratadine 10mg', 'Antihistaminique contre les allergies', 7.50, 60, 4),
('Oméprazole 20mg', 'Inhibiteur de la pompe à protons', 9.90, 90, 5);
