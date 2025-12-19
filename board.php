<html lang='fr'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Jeu de Mémoire</title>
    <link rel='stylesheet' href='./style.css'>
</head>

<body>
    <?php
    require_once 'Card.php';
    session_start();
    
    if (isset($_GET['username'])) {
        $_SESSION['username'] = htmlspecialchars($_GET['username']);
    }
    if (isset($_GET['pairs'])) {
        $_SESSION['pairs'] = intval($_GET['pairs']);
        unset($_SESSION['deck']);
    }
    
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Joueur';
    $pairs = isset($_SESSION['pairs']) ? $_SESSION['pairs'] : 8;
    
    $pairs = max(2, min(16, $pairs));
    ?>
    
    <h2>Joueur : <?php echo $username; ?> | Paires : <?php echo $pairs; ?></h2>
    <a href="index.php">Retour à l'accueil</a>
    
    <form method="post" class='game-board'>
        <?php
        if (!isset($_SESSION['deck'])) {
            $deck = [];
            for ($i = 1; $i <= $pairs; $i++) {
                $imagePath = "./assets/card" . $i . ".png";
                $deck[] = new Card($i * 2 - 1, $imagePath);
                $deck[] = new Card($i * 2, $imagePath);
            }
            shuffle($deck);
            $_SESSION['deck'] = $deck;
            $_SESSION['turn'] = true;
        };
        $allMatched = true;
        $deck = ($_SESSION['deck']);
        foreach ($deck as $card) {
            if ($card->matched === false) {
                $allMatched = false;
            }
        }



        if (isset($_POST['cardId'])) {
            for ($i = 0; $i < count($deck); $i++) {
                if ($deck[$i]->getId() == $_POST['cardId']) {
                    $deck[$i]->flipped = true;
                    $flippedCard = $deck[$i]->getImage();
                    if ($_SESSION['turn'] === false) {
                        for ($j = 0; $j < count($deck); $j++) {
                            if ($i !== $j && $deck[$j]->getImage() === $flippedCard && $deck[$j]->flipped === true) {
                                $deck[$j]->matched = true;
                                $deck[$i]->matched = true;
                            }
                        }
                    }
                } elseif ($_SESSION['turn']) {
                    $deck[$i]->flipped = false;
                }
            }
            $_SESSION['turn'] = !$_SESSION['turn'];
            $_SESSION['deck'] = $deck;
        }



        if ($allMatched) {
            echo "<div><h2>Félicitations " . $username . " ! Vous avez gagné !</h2><button type='submit' class='restart'>Recommencer</button></div>";
            echo "<a href='index.php'>Nouvelle partie</a>";
            unset($_SESSION['deck']);
            unset($_SESSION['turn']);
            echo "";
        } else {
            foreach ($deck as $card) {
                if ($card->flipped || $card->matched) {
                    echo "<div class='card'>
                <img src='" . $card->getImage() . "' alt='Card Image'>
                </div>";
                } else {
                    echo "<button type='submit' class='card' name='cardId' value='" . $card->getId() . "'>
                <img src='./assets/backside.png' alt='Card Back'>
                </button>";
                }
            }
        }


        ?>
    </form>
</body>