<?php
require_once __DIR__ . '/bootstrap.php';

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];

if (empty($username)) {
    echo json_encode(['error' => 'Pseudo requis']);
    exit;
}

$player = new Player($pdo, $username);
$player->findOrCreate();


$game = new Game(6);
$gameId = uniqid();

$_SESSION['games'][$gameId] = [
    'game' => $game,
    'player' => [
        'id' => $player->id,
        'username' => $player->username
    ]
];


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
    'gameId' => $gameId,
    'playerId' => $player->id,
    'username' => $player->username,
    'cards' => $cardsArray
]);
exit;
