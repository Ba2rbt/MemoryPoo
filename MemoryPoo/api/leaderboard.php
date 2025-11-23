<?php
require_once __DIR__ . '/bootstrap.php';

$query = "SELECT username, total_games, total_wins, best_score 
          FROM players 
          WHERE best_score > 0 
          ORDER BY best_score ASC 
          LIMIT 10";
          
$stmt = $pdo->query($query);
$leaderboard = $stmt->fetchAll();

echo json_encode([
    'success' => true,
    'leaderboard' => $leaderboard
]);
exit;
