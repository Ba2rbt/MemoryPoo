<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory - Accueil</title>
</head>
<body>
    <h1>Jeu de Memory</h1>
    
    <form action="board.php" method="get">
        <div>
            <label for="username">Votre nom :</label><br>
            <input type="text" id="username" name="username" required placeholder="Entrez votre nom">
        </div>
        
        <br>
        
        <div>
            <label for="pairs">Nombre de paires de cartes :</label><br>
            <select id="pairs" name="pairs">
                <option value="2">2 paires</option>
                <option value="4">4 paires</option>
                <option value="6">6 paires</option>
                <option value="8" selected>8 paires</option>
                <option value="10">10 paires</option>
                <option value="12">12 paires</option>
                <option value="14">14 paires</option>
                <option value="16">16 paires</option>
            </select>
        </div>
        
        <br>
        
        <button type="submit">Jouer</button>
    </form>
</body>
</html>
