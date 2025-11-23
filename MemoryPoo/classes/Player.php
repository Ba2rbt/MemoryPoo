<?php
class Player {
    public $id;
    public $username;
    private $pdo;
    
    public function __construct($pdo, $username) {
        $this->pdo = $pdo;
        $this->username = $username;
    }
    
    public function findOrCreate() {
        
        $query = "SELECT id FROM players WHERE username = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$this->username]);
        $result = $stmt->fetch();
        
        if ($result) {
            $this->id = $result['id'];
        } else {
            
            $query = "INSERT INTO players (username) VALUES (?)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$this->username]);
            $this->id = $this->pdo->lastInsertId();
        }
    }
    
    public function saveGame($duration, $pairsCount, $won) {
        
        $query = "INSERT INTO games (player_id, pairs_count, time_seconds, is_won) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$this->id, $pairsCount, $duration, $won ? 1 : 0]);
        
        
        $query = "UPDATE players SET 
                  total_games = total_games + 1,
                  total_wins = total_wins + ?,
                  total_time = total_time + ?
                  WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$won ? 1 : 0, $duration, $this->id]);
        
        
        if ($won) {
            $query = "UPDATE players SET best_score = ? 
                      WHERE id = ? AND (best_score = 0 OR ? < best_score)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$duration, $this->id, $duration]);
        }
    }
}
