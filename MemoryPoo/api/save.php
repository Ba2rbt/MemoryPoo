<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../classes/Player.php';

$data = json_decode(file_get_contents('php://input'), true);
$gameId = $data['gameId'];

if (!isset($_SESSION['games'][$gameId])) {
    echo json_encode(['error' => 'Partie non trouvée']);
    exit;
}

$playerData = $_SESSION['games'][$gameId]['player'];
$game = $_SESSION['games'][$gameId]['game'];


$player = new Player($pdo, $playerData['username']);
$player->id = $playerData['id'];

$duration = $game->getDuration();
$moves = $game->moves;
$won = $game->isGameWon();


$pairsCount = (int)(count($game->cards) / 2);
$player->saveGame($duration, $pairsCount, $won);

unset($_SESSION['games'][$gameId]);

echo json_encode([
    'success' => true,
    'duration' => $duration,
    'moves' => $moves,
    'won' => $won
]);

exit;
