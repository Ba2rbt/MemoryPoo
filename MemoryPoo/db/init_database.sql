-- =================================================
-- SCRIPT DE CRÉATION DE LA BASE DE DONNÉES MEMORY
-- =================================================
-- 
-- Ce script crée la structure complète de la base
-- Exécutez-le dans phpMyAdmin ou MySQL Workbench
--

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS memory_poo;
USE memory_poo;

-- =================================================
-- TABLE: PLAYERS (Profils des joueurs)
-- =================================================
-- 
-- Cette table stocke les informations de chaque joueur
-- Explications des colonnes :
-- - id : Identifiant unique (PRIMARY KEY, AUTO_INCREMENT)
-- - username : Nom d'utilisateur unique
-- - total_games : Nombre total de parties jouées
-- - total_wins : Nombre de victoires
-- - best_score : Meilleur temps (en secondes)
-- - total_time : Temps total joué (en secondes)
-- - created_at : Date de création du compte
-- - updated_at : Dernière mise à jour
--

CREATE TABLE IF NOT EXISTS players (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    total_games INT DEFAULT 0,
    total_wins INT DEFAULT 0,
    best_score INT DEFAULT 0,
    total_time INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Index pour accélérer les recherches
    INDEX idx_username (username),
    INDEX idx_best_score (best_score)
);

-- =================================================
-- TABLE: GAMES (Historique des parties)
-- =================================================
-- 
-- Chaque ligne représente une partie jouée
-- Cela permet de voir l'historique complet d'un joueur
-- 
-- Explications :
-- - player_id : Référence au joueur (FOREIGN KEY)
-- - pairs_count : Nombre de paires dans cette partie
-- - time_seconds : Temps pour finir la partie
-- - is_won : 1 si gagné, 0 si perdu
-- - played_at : Quand la partie a été jouée
--

CREATE TABLE IF NOT EXISTS games (
    id INT PRIMARY KEY AUTO_INCREMENT,
    player_id INT NOT NULL,
    pairs_count INT NOT NULL,
    time_seconds INT NOT NULL,
    is_won BOOLEAN DEFAULT 1,
    played_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    -- Clé étrangère : lie games à players
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE,
    
    -- Index pour les recherches rapides
    INDEX idx_player_id (player_id),
    INDEX idx_played_at (played_at)
);

-- =================================================
-- TABLE: LEADERBOARD (Top 10 des meilleurs joueurs)
-- =================================================
-- 
-- Vue créée automatiquement à partir des données de players
-- Cette table est générée à partir de la table players
-- Elle affiche les 10 meilleurs joueurs classés par score
--

-- Cette est une VIEW (vue) MySQL qui crée automatiquement
-- le classement des 10 meilleurs joueurs
-- Compatible avec MySQL 5.7+ (utilise @rank au lieu de RANK() OVER)
CREATE VIEW leaderboard AS
SELECT 
    id,
    username,
    total_games,
    total_wins,
    best_score,
    total_time,
    created_at
FROM players
WHERE best_score > 0  -- Exclut les joueurs sans parties gagnées
ORDER BY best_score ASC, total_wins DESC
LIMIT 10;

-- =================================================
-- DONNÉES D'EXEMPLE (optionnel)
-- =================================================
-- 
-- Décommentez pour tester avec des données d'exemple
--

/*
INSERT INTO players (username, total_games, total_wins, best_score, total_time) VALUES
('Alice', 15, 12, 45, 850),
('Bob', 20, 18, 52, 1200),
('Charlie', 10, 9, 60, 600),
('Diana', 25, 22, 38, 1500),
('Evan', 12, 10, 55, 720);

INSERT INTO games (player_id, pairs_count, time_seconds, is_won, played_at) VALUES
(1, 6, 45, 1, NOW()),
(1, 8, 60, 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(2, 6, 52, 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 8, 65, 0, DATE_SUB(NOW(), INTERVAL 3 DAY));
*/

-- =================================================
-- FIN DU SCRIPT
-- =================================================
