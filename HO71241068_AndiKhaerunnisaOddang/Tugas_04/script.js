const game = {
    balance: 5000,
    bet: 0,
    deck: [],
    playerHand: [],
    botHand: [],
    discardPile: [],
    currentPlayer: 'player',
    direction: 1,
    selectedCard: null,
    selectedColor: null,
    isActive: false,
    unoPressed: false,
    unoTimer: null,
    botUnoPressed: false,
    botUnoTimer: null,
    drawnCard: null,
    canPlayDrawn: false
};

const COLORS = ['red', 'blue', 'green', 'yellow'];

function createDeck() {
    const deck = [];
    
    COLORS.forEach(color => {
        deck.push({ type: 'number', value: 0, color });
        for (let i = 1; i <= 9; i++) {
            deck.push({ type: 'number', value: i, color });
            deck.push({ type: 'number', value: i, color });
        }
    });

    COLORS.forEach(color => {
        for (let i = 0; i < 2; i++) {
            deck.push({ type: 'skip', color });
            deck.push({ type: 'reverse', color });
            deck.push({ type: 'draw2', color });
        }
    });

    for (let i = 0; i < 4; i++) {
        deck.push({ type: 'wild', color: 'wild' });
        deck.push({ type: 'draw4', color: 'wild' });
    }

    return shuffle(deck);
}

function shuffle(array) {
    const arr = [...array];
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
}

function startGame() {
    const betInput = document.getElementById('betInput');
    const bet = parseInt(betInput.value);
    
    if (!bet || bet < 100) {
        document.getElementById('betError').textContent = 'Taruhan minimum $100';
        return;
    }
    if (bet > game.balance) {
        document.getElementById('betError').textContent = 'Saldo tidak cukup';
        return;
    }

    game.bet = bet;
    game.deck = createDeck();
    game.playerHand = [];
    game.botHand = [];
    game.discardPile = [];
    game.currentPlayer = 'player';
    game.direction = 1;
    game.selectedCard = null;
    game.selectedColor = null;
    game.isActive = true;
    game.unoPressed = false;
    game.botUnoPressed = false;
    game.drawnCard = null;
    game.canPlayDrawn = false;

    for (let i = 0; i < 7; i++) {
        game.playerHand.push(game.deck.pop());
        game.botHand.push(game.deck.pop());
    }

    let firstCard = game.deck.pop();
    while (firstCard.type !== 'number') {
        game.deck.unshift(firstCard);
        firstCard = game.deck.pop();
    }
    game.discardPile.push(firstCard);

    document.getElementById('bettingModal').classList.remove('active');
    document.getElementById('bettingModal').classList.add('hidden');
    log('Permainan dimulai! Taruhan: $' + game.bet);
    render();
}

function render() {
    renderHand('player');
    renderHand('bot');
    renderDiscardPile();
    updateUI();
}

function renderHand(player) {
    const container = document.getElementById(player === 'player' ? 'playerHand' : 'botHand');
    const hand = player === 'player' ? game.playerHand : game.botHand;
    const isPlayerTurn = game.currentPlayer === player;
    
    container.innerHTML = '';
    
    hand.forEach((card, idx) => {
        const cardEl = document.createElement('div');
        
        if (player === 'bot') {
            cardEl.className = 'card back';
        } else {
            const displayColor = (card.type === 'wild' || card.type === 'draw4') ? 'wild' : card.color;
            cardEl.className = 'card ' + displayColor;
            
            cardEl.style.backgroundImage = `url('assets/cards/${getCardImageName(card)}')`;
            
            const canPlay = canPlayCard(card);
            
            if (!isPlayerTurn || !canPlay) {
                cardEl.classList.add('disabled');
            }
            
            if (game.selectedCard === idx) {
                cardEl.classList.add('selected');
            }
            
            cardEl.onclick = () => selectCard(idx);
        }
        
        container.appendChild(cardEl);
    });

    document.getElementById(player === 'player' ? 'playerCardCount' : 'botCardCount').textContent = hand.length;
}

function renderDiscardPile() {
    const container = document.getElementById('discardPile');
    container.innerHTML = '';
    
    if (game.discardPile.length > 0) {
        const topCard = game.discardPile[game.discardPile.length - 1];
        const cardEl = document.createElement('div');
        
        let displayColor = topCard.color;
        if ((topCard.type === 'wild' || topCard.type === 'draw4') && topCard.selectedColor) {
            displayColor = topCard.selectedColor;
        } else if (topCard.type === 'wild' || topCard.type === 'draw4') {
            displayColor = 'wild';
        }
        
        cardEl.className = 'card ' + displayColor;
        cardEl.style.backgroundImage = `url('assets/cards/${getCardImageName(topCard)}')`;
        container.appendChild(cardEl);
    }

    document.getElementById('deckCount').textContent = game.deck.length;
}

function getCardImageName(card) {
    const color = card.selectedColor || card.color;
    
    if (card.type === 'wild') {
        return `${color}_wild.png`;
    }
    
    if (card.type === 'draw4') {
        return `${color}_+4.png`;
    }
    
    if (card.type === 'number') {
        return `${color}_${card.value}.png`;
    }
    
    if (card.type === 'skip') {
        return `${color}_skip.png`;
    }
    
    if (card.type === 'reverse') {
        return `${color}_reverse.png`;
    }
    
    if (card.type === 'draw2') {
        return `${color}_+2.png`;
    }
    
    return 'back.png';
}

function updateUI() {
    document.getElementById('balance').textContent = '$' + game.balance;
    document.getElementById('bet').textContent = game.bet ? '$' + game.bet : '-';
    document.getElementById('direction').textContent = game.direction === 1 ? '➡️' : '⬅️';

    const turnEl = document.getElementById('turnIndicator');
    const deckPile = document.getElementById('deckPile');
    
    if (game.currentPlayer === 'player') {
        turnEl.textContent = 'GILIRAN ANDA';
        turnEl.className = 'text-center text-xl font-bold p-3 rounded-xl bg-green-100 border-2 border-green-500 text-green-700';
        deckPile.classList.remove('disabled');
    } else {
        turnEl.textContent = '● GILIRAN BOT';
        turnEl.className = 'text-center text-xl font-bold p-3 rounded-xl bg-red-100 border-2 border-red-500 text-red-700';
        deckPile.classList.add('disabled');
    }

    document.getElementById('playBtn').disabled = game.selectedCard === null || (game.selectedColor === null && game.playerHand[game.selectedCard] && (game.playerHand[game.selectedCard].type === 'wild' || game.playerHand[game.selectedCard].type === 'draw4'));
    document.getElementById('unoBtn').disabled = game.playerHand.length !== 1 || !game.isActive;
    document.getElementById('callBotUnoBtn').disabled = game.botHand.length !== 1 || !game.isActive || game.botUnoPressed;
}

function selectCard(idx) {
    if (game.currentPlayer !== 'player' || !game.isActive) return;
    
    if (game.canPlayDrawn && idx !== game.drawnCard) {
        log('Anda hanya bisa mainkan kartu yang baru diambil atau lewati giliran!');
        return;
    }
    
    const card = game.playerHand[idx];
    if (!canPlayCard(card)) {
        log('Kartu tidak bisa dimainkan!');
        return;
    }

    const colorPicker = document.getElementById('colorPicker');
    
    if (game.selectedCard === idx) {
        game.selectedCard = null;
        game.selectedColor = null;
        colorPicker.classList.add('hidden');
        colorPicker.classList.remove('flex');
        document.querySelectorAll('.color-btn').forEach(btn => btn.classList.remove('selected'));
    } else {
        game.selectedCard = idx;
        if (card.type === 'wild' || card.type === 'draw4') {
            colorPicker.classList.remove('hidden');
            colorPicker.classList.add('flex');
            game.selectedColor = null;
            document.querySelectorAll('.color-btn').forEach(btn => btn.classList.remove('selected'));
        } else {
            colorPicker.classList.add('hidden');
            colorPicker.classList.remove('flex');
            game.selectedColor = null;
        }
    }
    
    render();
}

function selectColor(color) {
    document.querySelectorAll('.color-btn').forEach(btn => btn.classList.remove('selected'));
    event.target.classList.add('selected');
    game.selectedColor = color;
    updateUI();
}

function canPlayCard(card) {
    if (game.discardPile.length === 0) return false;
    const topCard = game.discardPile[game.discardPile.length - 1];
    
    if (card.type === 'wild') return true;
    
    if (card.type === 'draw4') {
        const currentPlayer = game.currentPlayer;
        const hand = currentPlayer === 'player' ? game.playerHand : game.botHand;
        const hasPlayable = hand.some(c => 
            c !== card && c.type !== 'draw4' && 
            (c.color === topCard.color || 
            (c.type === topCard.type && c.type !== 'wild') ||
            (c.type === 'number' && topCard.type === 'number' && c.value === topCard.value))
        );
        return !hasPlayable;
    }
    
    const topCardColor = topCard.selectedColor || topCard.color;
    
    if (card.color === topCardColor) return true;
    if (card.type === topCard.type && card.type !== 'number') return true;
    if (card.type === 'number' && topCard.type === 'number' && card.value === topCard.value) return true;
    
    return false;
}

function playSelectedCard() {
    if (game.selectedCard === null || game.currentPlayer !== 'player') return;

    const card = game.playerHand[game.selectedCard];
    
    if (card.type === 'wild' || card.type === 'draw4') {
        if (!game.selectedColor) {
            log('Pilih warna terlebih dahulu!');
            return;
        }
        card.selectedColor = game.selectedColor;
    }

    game.playerHand.splice(game.selectedCard, 1);
    game.discardPile.push(card);
    
    game.drawnCard = null;
    game.canPlayDrawn = false;
    game.selectedCard = null;
    game.selectedColor = null;
    const colorPicker = document.getElementById('colorPicker');
    colorPicker.classList.add('hidden');
    colorPicker.classList.remove('flex');
    const skipBtn = document.getElementById('skipTurnBtn');
    if (skipBtn) skipBtn.classList.add('hidden');

    log('Anda mainkan: ' + getCardName(card));

    if (game.playerHand.length === 1) {
        log('⚡ Anda punya 1 kartu! Tekan UNO dalam 5 detik!');
        startUnoTimer('player');
    } else if (game.playerHand.length === 0) {
        endGame('player');
        return;
    }

    applyCardEffect(card, 'player');
    render();
}

function skipTurn() {
    if (game.currentPlayer !== 'player' || !game.canPlayDrawn) return;
    
    log('Anda memilih melewati giliran');
    game.drawnCard = null;
    game.canPlayDrawn = false;
    game.selectedCard = null;
    game.selectedColor = null;
    const colorPicker = document.getElementById('colorPicker');
    colorPicker.classList.add('hidden');
    colorPicker.classList.remove('flex');
    const skipBtn = document.getElementById('skipTurnBtn');
    if (skipBtn) skipBtn.classList.add('hidden');
    
    switchTurn();
    render();
}

function drawCard() {
    if (game.currentPlayer !== 'player' || !game.isActive) return;

    if (game.deck.length === 0) refillDeck();
    
    const card = game.deck.pop();
    game.playerHand.push(card);
    game.drawnCard = game.playerHand.length - 1;
    
    log('Anda mengambil 1 kartu');

    if (canPlayCard(card)) {
        log('✓ Kartu yang diambil bisa dimainkan! Anda bisa mainkan atau lewati giliran.');
        game.canPlayDrawn = true;
        game.selectedCard = game.drawnCard;
        
        if (card.type === 'wild' || card.type === 'draw4') {
            if (card.type === 'wild' || card.type === 'draw4') {
                const colorPicker = document.getElementById('colorPicker');
                colorPicker.classList.remove('hidden');
                colorPicker.classList.add('flex');
            }
        }
        
        document.getElementById('playBtn').disabled = false;
        const skipBtn = document.getElementById('skipTurnBtn');
        if (skipBtn) {
            skipBtn.classList.remove('hidden');
        }
    } else {
        log('Kartu tidak bisa dimainkan, giliran otomatis dilewati');
        game.drawnCard = null;
        game.canPlayDrawn = false;
        setTimeout(() => {
            switchTurn();
            render();
        }, 1500);
    }
    
    render();
}

function applyCardEffect(card, player) {
    const opponent = player === 'player' ? 'bot' : 'player';
    const opponentHand = opponent === 'player' ? game.playerHand : game.botHand;

    if (card.type === 'skip') {
        log((opponent === 'player' ? 'Anda' : 'Bot') + ' diskip!');
        game.currentPlayer = player;
        if (player === 'bot') {
            setTimeout(() => botTurn(), 1500);
        } else {
            render();
        }
    } else if (card.type === 'reverse') {
        game.direction *= -1;
        log('Arah permainan dibalik!');
        game.currentPlayer = player;
        if (player === 'bot') {
            setTimeout(() => botTurn(), 1500);
        } else {
            render();
        }
    } else if (card.type === 'draw2') {
        for (let i = 0; i < 2; i++) {
            if (game.deck.length === 0) refillDeck();
            opponentHand.push(game.deck.pop());
        }
        log((opponent === 'player' ? 'Anda' : 'Bot') + ' mengambil 2 kartu');
        game.currentPlayer = player;
        if (player === 'bot') {
            setTimeout(() => botTurn(), 1500);
        } else {
            render();
        }
    } else if (card.type === 'draw4') {
        for (let i = 0; i < 4; i++) {
            if (game.deck.length === 0) refillDeck();
            opponentHand.push(game.deck.pop());
        }
        log((opponent === 'player' ? 'Anda' : 'Bot') + ' mengambil 4 kartu');
        game.currentPlayer = player;
        if (player === 'bot') {
            setTimeout(() => botTurn(), 1500);
        } else {
            render();
        }
    } else {
        switchTurn();
    }
}

function switchTurn() {
    game.currentPlayer = game.currentPlayer === 'player' ? 'bot' : 'player';
    
    if (game.currentPlayer === 'bot') {
        setTimeout(botTurn, 1500);
    } else {
        render();
    }
}

function botTurn() {
    if (game.currentPlayer !== 'bot' || !game.isActive) return;

    render();

    const topCard = game.discardPile[game.discardPile.length - 1];
    let playableCards = game.botHand
        .map((card, idx) => ({ card, idx }))
        .filter(({ card }) => canPlayCard(card));

    if (playableCards.length === 0) {
        if (game.deck.length === 0) refillDeck();
        const drawnCard = game.deck.pop();
        game.botHand.push(drawnCard);
        log('📥 Bot mengambil 1 kartu');

        if (canPlayCard(drawnCard)) {
            playableCards = [{ card: drawnCard, idx: game.botHand.length - 1 }];
            log('Bot bisa mainkan kartu yang diambil!');
        } else {
            log('Bot tidak bisa mainkan kartu, giliran dilewati');
            switchTurn();
            return;
        }
    }

    setTimeout(() => {
        const { card, idx } = playableCards[Math.floor(Math.random() * playableCards.length)];
        game.botHand.splice(idx, 1);

        if (card.type === 'wild' || card.type === 'draw4') {
            const colorCounts = { red: 0, blue: 0, green: 0, yellow: 0 };
            game.botHand.forEach(c => {
                if (COLORS.includes(c.color)) {
                    colorCounts[c.color]++;
                }
            });
            
            let maxCount = 0;
            let bestColors = [];
            COLORS.forEach(color => {
                if (colorCounts[color] > maxCount) {
                    maxCount = colorCounts[color];
                    bestColors = [color];
                } else if (colorCounts[color] === maxCount) {
                    bestColors.push(color);
                }
            });
            
            card.selectedColor = bestColors[Math.floor(Math.random() * bestColors.length)];
            log('Bot memilih warna: ' + getColorName(card.selectedColor));
        }

        game.discardPile.push(card);
        log('Bot mainkan: ' + getCardName(card));

        if (game.botHand.length === 1) {
            log('Bot punya 1 kartu!');
            startUnoTimer('bot');
            setTimeout(() => {
                if (game.isActive && game.botHand.length === 1) {
                    game.botUnoPressed = true;
                    log('Bot menekan UNO!');
                }
            }, 500);
        } else if (game.botHand.length === 0) {
            endGame('bot');
            return;
        }

        applyCardEffect(card, 'bot');
    }, 800);
}

function callUNO() {
    if (game.playerHand.length === 1 && game.isActive) {
        game.unoPressed = true;
        clearTimeout(game.unoTimer);
        log('UNO! berhasil ditekan!');
        document.getElementById('unoBtn').disabled = true;
    }
}

function callBotUNO() {
    if (game.botHand.length === 1 && !game.botUnoPressed && game.isActive) {
        log('Bot lupa tekan UNO! Penalti +2 kartu untuk Bot');
        for (let i = 0; i < 2; i++) {
            if (game.deck.length === 0) refillDeck();
            game.botHand.push(game.deck.pop());
        }
        clearTimeout(game.botUnoTimer);
        render();
    }
}

function startUnoTimer(player) {
    if (player === 'player') {
        game.unoPressed = false;
        clearTimeout(game.unoTimer);
        game.unoTimer = setTimeout(() => {
            if (!game.unoPressed && game.playerHand.length === 1 && game.isActive) {
                log('Anda lupa tekan UNO! Penalti +2 kartu');
                for (let i = 0; i < 2; i++) {
                    if (game.deck.length === 0) refillDeck();
                    game.playerHand.push(game.deck.pop());
                }
                render();
            }
        }, 5000);
    } else {
        game.botUnoPressed = false;
        clearTimeout(game.botUnoTimer);
        game.botUnoTimer = setTimeout(() => {
            if (!game.botUnoPressed && game.botHand.length === 1 && game.isActive) {
                log('Bot lupa tekan UNO! Penalti +2 kartu untuk Bot');
                for (let i = 0; i < 2; i++) {
                    if (game.deck.length === 0) refillDeck();
                    game.botHand.push(game.deck.pop());
                }
                render();
            }
        }, 5000);
    }
}

function refillDeck() {
    if (game.discardPile.length <= 1) return;
    
    const topCard = game.discardPile[game.discardPile.length - 1];
    const cards = game.discardPile.slice(0, -1);
    cards.forEach(card => {
        if (card.type === 'wild' || card.type === 'draw4') {
            card.color = 'wild';
            delete card.selectedColor;
        }
    });
    game.deck = shuffle(cards);
    game.discardPile = [topCard];
    log('Deck diisi ulang dari discard pile');
}

function endGame(winner) {
    game.isActive = false;
    clearTimeout(game.unoTimer);
    clearTimeout(game.botUnoTimer);

    if (winner === 'player') {
        game.balance += game.bet;
        log('ANDA MENANG! - ' + game.bet);
        document.getElementById('gameOverTitle').textContent = 'MENANG!';
        document.getElementById('gameOverText').textContent = 'Anda memenangkan  '+ game.bet + '! Saldo:  '+ game.balance;
    } else {
        game.balance -= game.bet;
        log('BOT MENANG! - ' + game.bet);
        document.getElementById('gameOverTitle').textContent = 'KALAH';
        document.getElementById('gameOverText').textContent = 'Anda kehilangan  '+ game.bet + '. Saldo:  '+ game.balance;
    }

    render();

    setTimeout(() => {
        if (game.balance <= 0) {
            document.getElementById('gameOverTitle').textContent = 'GAME OVER';
            document.getElementById('gameOverText').textContent = 'Saldo Anda habis! Mulai ulang dengan saldo $5000';
        }
        document.getElementById('gameOverModal').classList.remove('hidden');
        document.getElementById('gameOverModal').classList.add('active');
    }, 2000);
}

function resetGame() {
    if (game.balance <= 0) {
        game.balance = 5000;
    }
    
    game.bet = 0;
    game.selectedCard = null;
    game.selectedColor = null;
    game.drawnCard = null;
    game.canPlayDrawn = false;
    document.getElementById('gameOverModal').classList.remove('active');
    document.getElementById('gameOverModal').classList.add('hidden');
    document.getElementById('betInput').value = '';
    document.getElementById('betError').textContent = '';
    document.getElementById('modalBalance').textContent = game.balance;
    document.getElementById('bettingModal').classList.add('active');
    document.getElementById('bettingModal').classList.remove('hidden');
    document.getElementById('gameLog').innerHTML = '';
    const colorPicker = document.getElementById('colorPicker');
    colorPicker.classList.add('hidden');
    colorPicker.classList.remove('flex');
    document.getElementById('skipTurnBtn').classList.add('hidden');
    render();
}

function getCardName(card) {
    const colorName = {
        red: 'Merah',
        blue: 'Biru',
        green: 'Hijau',
        yellow: 'Kuning'
    };

    let name = '';
    
    if (card.type === 'number') {
        name = card.value;
    } else if (card.type === 'skip') {
        name = 'Skip';
    } else if (card.type === 'reverse') {
        name = 'Reverse';
    } else if (card.type === 'draw2') {
        name = 'Draw 2';
    } else if (card.type === 'wild') {
        name = 'Wild';
    } else if (card.type === 'draw4') {
        name = 'Wild Draw 4';
    }

    const displayColor = card.selectedColor || card.color;
    if (displayColor && displayColor !== 'wild' && card.type !== 'wild' && card.type !== 'draw4') {
        name += ' ' + colorName[displayColor];
    } else if (card.selectedColor) {
        name += ' (' + colorName[card.selectedColor] + ')';
    }

    return name;
}

function getColorName(color) {
    const colorName = {
        red: 'Merah',
        blue: 'Biru',
        green: 'Hijau',
        yellow: 'Kuning'
    };
    return colorName[color] || color;
}

function log(message) {
    const logEl = document.getElementById('gameLog');
    const entry = document.createElement('div');
    entry.className = 'p-2 mb-1 rounded bg-gray-100 border-l-4 border-red-600 text-sm';
    const time = new Date().toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
    });
    entry.textContent = '[' + time + '] ' + message;
    logEl.appendChild(entry);
    logEl.scrollTop = logEl.scrollHeight;
}

window.onload = () => {
    document.getElementById('modalBalance').textContent = game.balance;
};