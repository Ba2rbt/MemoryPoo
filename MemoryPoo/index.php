<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game - Jeu des Paires</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div id="app">
        <header>
            <h1>🎮 Memory Game</h1>
            <div id="stats" class="hidden">
                <span>⏱️ <span id="timer">0:00</span></span>
                <span>🔄 Coups: <span id="moves">0</span></span>
            </div>
        </header>

        <main>
            <section id="login-screen">
                <div class="card-form">
                    <h2>Bienvenue !</h2>
                    <input type="text" id="username" placeholder="Votre pseudo" maxlength="20" autocomplete="off">
                    <button id="start-btn">Démarrer le jeu</button>
                </div>
            </section>

            <section id="game-screen" class="hidden">
                <div id="game-board"></div>
                <button id="quit-btn" class="secondary-btn">Quitter</button>
            </section>

            <section id="result-screen" class="hidden">
                <div class="card-form">
                    <h2 id="result-title">Bravo ! 🎉</h2>
                    <p id="result-stats"></p>
                    <button id="replay-btn">Rejouer</button>
                    <button id="leaderboard-btn" class="secondary-btn">Voir le classement</button>
                </div>
            </section>

            <section id="leaderboard-screen" class="hidden">
                <div class="card-form">
                    <h2>🏆 Classement</h2>
                    <div id="leaderboard-list"></div>
                    <button id="back-btn" class="secondary-btn">Retour</button>
                </div>
            </section>
        </main>
    </div>

    <script src="public/js/app.js"></script>
</body>
</html>
