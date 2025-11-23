<?php
require_once __DIR__ . '/bootstrap.php';

$playerId = isset($_GET['playerId']) ? (int)$_GET['playerId'] : null;

if ($playerId === null) {
    echo json_encode([
        'success' => false,
        'message' => 'ID du joueur requis'
    ]);
    exit;
}

try {
    
    $player = new Player($pdo, '');
    if ($player->loadById($playerId)) {
        
        $sql = "SELECT * FROM games 
                WHERE player_id = ? 
                ORDER BY played_at DESC 
                LIMIT 50";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$playerId]);
        $games = $stmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'player' => $player->toArray(),
            'games' => $games
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Joueur non trouvé'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur serveur'
    ]);
}
exit;
