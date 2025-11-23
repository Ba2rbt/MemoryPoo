<?php
require_once 'Card.php';

class Game {
    public $cards = [];
    public $moves = 0;
    public $startTime;
    
    public function __construct($pairsCount = 6) {
        $this->startTime = time();
        $this->createCards($pairsCount);
    }
    
    public function createCards($pairsCount) {
        $emojis = ['🐱', '🐶', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼', '🐨', '🐯', '🦁', '🐮'];
        $selectedEmojis = array_slice($emojis, 0, $pairsCount);
        
        $id = 0;
        foreach ($selectedEmojis as $emoji) {
            $this->cards[] = new Card($id++, $emoji);
            $this->cards[] = new Card($id++, $emoji);
        }
        
        shuffle($this->cards);
    }
    
    public function checkMatch($cardId1, $cardId2) {
        $card1 = $this->cards[$cardId1];
        $card2 = $this->cards[$cardId2];
        
        $card1->isFlipped = true;
        $card2->isFlipped = true;
        
        $this->moves++;
        
        $match = false;
        if ($card1->value === $card2->value) {
            $card1->isMatched = true;
            $card2->isMatched = true;
            $match = true;
        }
        
        $won = $this->isGameWon();
        
        return [
            'match' => $match,
            'won' => $won
        ];
    }
    
    public function isGameWon() {
        foreach ($this->cards as $card) {
            if (!$card->isMatched) {
                return false;
            }
        }
        return true;
    }
    
    public function getDuration() {
        return time() - $this->startTime;
    }
}
