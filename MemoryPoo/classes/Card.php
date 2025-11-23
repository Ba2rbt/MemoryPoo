<?php
class Card {
    public $id;
    public $value;
    public $isFlipped;
    public $isMatched;
    
    public function __construct($id, $value) {
        $this->id = $id;
        $this->value = $value;
        $this->isFlipped = false;
        $this->isMatched = false;
    }
}
