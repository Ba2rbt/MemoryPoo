<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../classes/Game.php';

$body = file_get_contents('php://input');
$data = json_decode($body, true);
$gameId = $data['gameId'] ?? null;
$card1 = $data['card1'] ?? null;
$card2 = $data['card2'] ?? null;

if (empty($gameId) || !isset($_SESSION['games'][$gameId])) {
    echo json_encode(['error' => 'Partie non trouvée']);
    exit;
}

$game = $_SESSION['games'][$gameId]['game'];
$result = $game->checkMatch($card1, $card2);
$_SESSION['games'][$gameId]['game'] = $game;

$cardsArray = [];
foreach ($game->cards as $card) {
    $cardsArray[] = [
        'id' => $card->id,
        'value' => $card->value,
        'isFlipped' => $card->isFlipped,
        'isMatched' => $card->isMatched
    ];
}

echo json_encode([
    'success' => true,
    'match' => $result['match'],
    'won' => $result['won'],
    'moves' => $game->moves,
    'cards' => $cardsArray
]);
exit;
