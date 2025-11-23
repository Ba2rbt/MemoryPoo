const API_BASE = '/MemoryPoo/api';
let gameState = {
    gameId: null,
    playerId: null,
    username: null,
    flippedCards: [],
    isChecking: false,
    startTime: null,
    timerInterval: null,
    cards: []
};

const screens = {
    login: document.getElementById('login-screen'),
    game: document.getElementById('game-screen'),
    result: document.getElementById('result-screen'),
    leaderboard: document.getElementById('leaderboard-screen')
};

const elements = {
    username: document.getElementById('username'),
    startBtn: document.getElementById('start-btn'),
    gameBoard: document.getElementById('game-board'),
    quitBtn: document.getElementById('quit-btn'),
    replayBtn: document.getElementById('replay-btn'),
    leaderboardBtn: document.getElementById('leaderboard-btn'),
    backBtn: document.getElementById('back-btn'),
    timer: document.getElementById('timer'),
    moves: document.getElementById('moves'),
    stats: document.getElementById('stats'),
    resultTitle: document.getElementById('result-title'),
    resultStats: document.getElementById('result-stats'),
    leaderboardList: document.getElementById('leaderboard-list')
};

function showScreen(screen) {
    Object.values(screens).forEach(s => s.classList.add('hidden'));
    screens[screen].classList.remove('hidden');
    if (screen === 'game') {
        elements.stats.classList.remove('hidden');
    } else {
        elements.stats.classList.add('hidden');
    }
}

async function startGame() {
    const username = elements.username.value.trim();
    if (!username) {
        alert('Veuillez entrer un pseudo');
        return;
    }
    try {
        const response = await fetch(`${API_BASE}/start.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username })
        });
        const data = await response.json();
        if (data.success) {
            gameState.gameId = data.gameId;
            gameState.playerId = data.playerId || null;
            gameState.username = data.username || username;
            gameState.startTime = Date.now();
            gameState.cards = data.cards;
            renderGameBoard(data.cards);
            showScreen('game');
            startTimer();
        } else {
            alert(data.error || 'Erreur lors du démarrage');
        }
    } catch (error) {
        alert('Erreur réseau: ' + error.message);
    }
}

function renderGameBoard(cards) {
    elements.gameBoard.innerHTML = '';
    elements.moves.textContent = '0';
    cards.forEach(card => {
        const cardEl = document.createElement('div');
        cardEl.className = 'card';
        cardEl.dataset.id = card.id;
        if (card.isMatched) {
            cardEl.classList.add('matched');
            cardEl.textContent = card.value;
        } else if (card.isFlipped) {
            cardEl.classList.add('flipped');
            cardEl.textContent = card.value;
        }
        cardEl.addEventListener('click', () => flipCard(card.id, cardEl));
        elements.gameBoard.appendChild(cardEl);
    });
}

function flipCard(cardId, cardEl) {
    if (gameState.isChecking || cardEl.classList.contains('matched') ||
        cardEl.classList.contains('flipped') || gameState.flippedCards.length >= 2) {
        return;
    }
    const cards = gameState.cards || [];
    const cardData = cards.find(c => c.id === cardId);
    if (cardData) {
        cardEl.textContent = cardData.value;
    }
    gameState.flippedCards.push({ id: cardId, element: cardEl });
    cardEl.classList.add('flipped');
    if (gameState.flippedCards.length === 2) {
        checkMatch();
    }
}

async function checkMatch() {
    gameState.isChecking = true;
    const [card1, card2] = gameState.flippedCards;
    try {
        const response = await fetch(`${API_BASE}/check.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                gameId: gameState.gameId,
                card1: card1.id,
                card2: card2.id
            })
        });
        const data = await response.json();
        if (data.success) {
            gameState.cards = data.cards;
            elements.moves.textContent = data.moves;
            if (data.match) {
                card1.element.classList.add('matched');
                card2.element.classList.add('matched');
                if (data.won) {
                    setTimeout(() => endGame(true), 500);
                }
            } else {
                setTimeout(() => {
                    card1.element.classList.remove('flipped');
                    card2.element.classList.remove('flipped');
                    card1.element.textContent = '';
                    card2.element.textContent = '';
                }, 1000);
            }
            gameState.flippedCards = [];
            gameState.isChecking = false;
        }
    } catch (error) {
        alert('Erreur: ' + error.message);
        gameState.isChecking = false;
    }
}

async function endGame(won) {
    stopTimer();
    try {
        const response = await fetch(`${API_BASE}/save.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ gameId: gameState.gameId })
        });
        const data = await response.json();
        if (data.success) {
            const minutes = Math.floor(data.duration / 60);
            const seconds = data.duration % 60;
            elements.resultTitle.textContent = won ? 'Bravo ! 🎉' : 'Partie terminée';
            elements.resultStats.innerHTML = `⏱️ Temps: ${minutes}:${seconds.toString().padStart(2, '0')}<br>🔄 Coups: ${data.moves}<br>${won ? '✅ Victoire !' : ''}`;
            showScreen('result');
        }
    } catch (error) {
        alert('Erreur lors de la sauvegarde: ' + error.message);
    }
}

function startTimer() {
    gameState.timerInterval = setInterval(() => {
        const elapsed = Math.floor((Date.now() - gameState.startTime) / 1000);
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        elements.timer.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }, 1000);
}

function stopTimer() {
    if (gameState.timerInterval) {
        clearInterval(gameState.timerInterval);
        gameState.timerInterval = null;
    }
}

async function showLeaderboard() {
    try {
        const response = await fetch(`${API_BASE}/leaderboard.php`);
        const data = await response.json();
        if (data.success) {
            elements.leaderboardList.innerHTML = data.leaderboard.map((player, index) => `
                <div class="leaderboard-item">
                    <span class="rank">#${index + 1}</span>
                    <span class="player">${player.username}</span>
                    <span class="score">${player.best_score} pts</span>
                </div>
            `).join('');
            showScreen('leaderboard');
        }
    } catch (error) {
        alert('Erreur: ' + error.message);
    }
}

elements.startBtn.addEventListener('click', startGame);
elements.username.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') startGame();
});

elements.quitBtn.addEventListener('click', () => {
    if (confirm('Voulez-vous vraiment quitter ?')) {
        stopTimer();
        endGame(false);
    }
});

elements.replayBtn.addEventListener('click', () => {
    showScreen('login');
});

elements.leaderboardBtn.addEventListener('click', showLeaderboard);
elements.backBtn.addEventListener('click', () => showScreen('login'));
