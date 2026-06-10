<x-webapp-layout
    title="{{ $seo['title'] ?? 'NCS 2048 | Sliding Puzzle Game' }}"
    description="{{ $seo['description'] ?? 'Play 2048 online and earn credits.' }}"
    keywords="{{ $seo['keywords'] ?? '2048 puzzle, ncs game' }}"
>
    <style>
        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            background: #111115;
            border: 2px solid #27272a;
            border-radius: 20px;
            padding: 12px;
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            aspect-ratio: 1;
            position: relative;
        }
        .grid-cell {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 900;
            color: #fff;
            position: relative;
            transition: all 0.15s ease-in-out;
        }
        
        /* Tile styling based on value */
        .tile-2 { background: #18181b; color: #a1a1aa; border: 1px solid #27272a; box-shadow: 0 0 10px rgba(255, 255, 255, 0.05); }
        .tile-4 { background: #27272a; color: #e4e4e7; border: 1px solid #3f3f46; box-shadow: 0 0 10px rgba(255, 255, 255, 0.08); }
        .tile-8 { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); box-shadow: 0 0 15px rgba(245, 158, 11, 0.2); }
        .tile-16 { background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.5); box-shadow: 0 0 15px rgba(245, 158, 11, 0.3); }
        .tile-32 { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); box-shadow: 0 0 15px rgba(239, 68, 68, 0.2); }
        .tile-64 { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.5); box-shadow: 0 0 15px rgba(239, 68, 68, 0.3); }
        .tile-128 { background: rgba(59, 130, 246, 0.15); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.4); box-shadow: 0 0 20px rgba(59, 130, 246, 0.3); }
        .tile-256 { background: rgba(59, 130, 246, 0.25); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.6); box-shadow: 0 0 25px rgba(59, 130, 246, 0.4); }
        .tile-512 { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.4); box-shadow: 0 0 20px rgba(16, 185, 129, 0.3); }
        .tile-1024 { background: rgba(16, 185, 129, 0.25); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.6); box-shadow: 0 0 25px rgba(16, 185, 129, 0.4); }
        .tile-2048 { background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%); color: #000; font-weight: 900; box-shadow: 0 0 35px rgba(245, 158, 11, 0.6); }

        .scale-animation {
            animation: popTile 0.2s ease-in-out forwards;
        }

        @keyframes popTile {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>

    <div class="max-w-xl mx-auto py-8 px-4 text-center">
        {{-- Header / Breadcrumbs --}}
        <div class="flex items-center justify-between gap-4 mb-8">
            <div class="text-left">
                <p class="text-[10px] text-amber-500 font-black uppercase tracking-widest">Puzzle Game</p>
                <h1 class="font-brand text-3xl sm:text-4xl font-black text-white uppercase tracking-tighter">NCS 2048</h1>
            </div>
            <a href="{{ route('webapp.game') }}" class="btn-vault px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest">
                Back to Arcade
            </a>
        </div>

        {{-- Score & Goal Panel --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-zinc-900/60 border border-zinc-800 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500">Current Score</p>
                <h3 id="current-score" class="text-xl font-black text-white mt-1">0</h3>
            </div>
            <div class="bg-zinc-900/60 border border-zinc-800 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500">Best Tile</p>
                <h3 id="best-tile" class="text-xl font-black text-amber-500 mt-1">2</h3>
            </div>
        </div>

        {{-- Instructions --}}
        <div class="bg-zinc-950 border border-zinc-900 rounded-2xl p-4 mb-6">
            <p class="text-xs text-zinc-400 font-medium leading-relaxed">
                Use your <strong class="text-white">Arrow keys</strong>, <strong class="text-white">WASD</strong>, or <strong class="text-white">Swipe</strong> on the board to slide tiles. Merge same numbers to reach <strong class="text-amber-500">2048</strong>!
            </p>
        </div>

        {{-- Grid Board --}}
        <div class="grid-container" id="grid-board">
            {{-- Grid Cells populated by JS --}}
        </div>

        {{-- Game Over / Win Overlay --}}
        <div id="game-over-panel" class="mt-8 hidden">
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6">
                <h3 id="game-status-text" class="text-2xl font-black font-brand uppercase tracking-tight text-white mb-2">Game Over</h3>
                <p id="game-credits-text" class="text-xs text-zinc-500 mb-6 uppercase tracking-wider"></p>
                <button type="button" onclick="resetGame()" class="btn-vault w-full sm:w-auto px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest">
                    Play Again
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const board = document.getElementById('grid-board');
            const scoreDisplay = document.getElementById('current-score');
            const bestTileDisplay = document.getElementById('best-tile');
            const statusPanel = document.getElementById('game-over-panel');
            const statusText = document.getElementById('game-status-text');
            const creditsText = document.getElementById('game-credits-text');

            let grid = Array(16).fill(0);
            let score = 0;
            let earnedCredits = false;

            // Generate initial empty board grid cells
            function renderEmptyBoard() {
                board.innerHTML = '';
                for (let i = 0; i < 16; i++) {
                    const cell = document.createElement('div');
                    cell.className = 'grid-cell';
                    cell.dataset.index = i;
                    board.appendChild(cell);
                }
            }

            // Sync visual grid with internal state
            function drawBoard() {
                const cells = board.getElementsByClassName('grid-cell');
                let maxTile = 2;
                for (let i = 0; i < 16; i++) {
                    const value = grid[i];
                    const cell = cells[i];
                    cell.className = 'grid-cell';
                    cell.textContent = value > 0 ? value : '';
                    if (value > 0) {
                        cell.classList.add(`tile-${value}`, 'scale-animation');
                        if (value > maxTile) maxTile = value;
                    }
                }
                scoreDisplay.textContent = score;
                bestTileDisplay.textContent = maxTile;

                // Award credits check (Award at 2048 tile)
                if (maxTile >= 2048 && !earnedCredits) {
                    earnedCredits = true;
                    awardGameCredits(maxTile);
                }
            }

            // Spawn a random 2 or 4 tile in an empty cell
            function spawnTile() {
                const emptyIndexes = [];
                for (let i = 0; i < 16; i++) {
                    if (grid[i] === 0) emptyIndexes.push(i);
                }
                if (emptyIndexes.length === 0) return;
                const randomIndex = emptyIndexes[Math.floor(Math.random() * emptyIndexes.length)];
                grid[randomIndex] = Math.random() < 0.9 ? 2 : 4;
            }

            // Slide and Merge Logic
            function compress(row) {
                let temp = row.filter(val => val > 0);
                while (temp.length < 4) temp.push(0);
                return temp;
            }

            function merge(row) {
                for (let i = 0; i < 3; i++) {
                    if (row[i] !== 0 && row[i] === row[i + 1]) {
                        row[i] = row[i] * 2;
                        score += row[i];
                        row[i + 1] = 0;
                    }
                }
                return row;
            }

            function processRow(row) {
                return compress(merge(compress(row)));
            }

            function slideLeft() {
                let changed = false;
                for (let i = 0; i < 16; i += 4) {
                    const oldRow = grid.slice(i, i + 4);
                    const newRow = processRow(oldRow);
                    for (let j = 0; j < 4; j++) {
                        if (grid[i + j] !== newRow[j]) changed = true;
                        grid[i + j] = newRow[j];
                    }
                }
                return changed;
            }

            function rotate90() {
                let newGrid = Array(16).fill(0);
                for (let r = 0; r < 4; r++) {
                    for (let c = 0; c < 4; c++) {
                        newGrid[c * 4 + (3 - r)] = grid[r * 4 + c];
                    }
                }
                grid = newGrid;
            }

            function slide(direction) {
                let changed = false;
                // Left: 0 rotations
                // Down: 1 rotation
                // Right: 2 rotations
                // Up: 3 rotations
                if (direction === 'left') {
                    changed = slideLeft();
                } else if (direction === 'down') {
                    rotate90();
                    changed = slideLeft();
                    rotate90(); rotate90(); rotate90();
                } else if (direction === 'right') {
                    rotate90(); rotate90();
                    changed = slideLeft();
                    rotate90(); rotate90();
                } else if (direction === 'up') {
                    rotate90(); rotate90(); rotate90();
                    changed = slideLeft();
                    rotate90();
                }
                return changed;
            }

            function checkGameOver() {
                // Check if any empty cell exists
                if (grid.includes(0)) return false;
                // Check if any horizontally adjacent cells can merge
                for (let i = 0; i < 16; i++) {
                    if (i % 4 < 3 && grid[i] === grid[i + 1]) return false;
                    if (i < 12 && grid[i] === grid[i + 4]) return false;
                }
                return true;
            }

            function awardGameCredits(highestTile) {
                @auth
                $.post('{{ route('webapp.game.award-credits') }}', { 
                    score: highestTile, 
                    game: '2048', 
                    _token: '{{ csrf_token() }}' 
                }, function(res) {
                    if (res.success) {
                        statusText.textContent = "Victory!";
                        creditsText.innerHTML = `You reached the 2048 tile and earned <span class="text-amber-500 font-bold">50 NCS Credits</span>!`;
                        statusPanel.classList.remove('hidden');
                        if (window.toastr) toastr.success(res.message);
                    }
                });
                @else
                statusText.textContent = "Victory!";
                creditsText.textContent = "Sign in to save your score and earn NCS Credits.";
                statusPanel.classList.remove('hidden');
                @endauth
            }

            function handleMove(direction) {
                if (statusPanel.classList.contains('hidden') === false && statusText.textContent === "Victory!") {
                    // Let the user keep playing after winning if they want to
                    statusPanel.classList.add('hidden');
                }
                
                const changed = slide(direction);
                if (changed) {
                    spawnTile();
                    drawBoard();
                    if (checkGameOver()) {
                        statusText.textContent = "Game Over";
                        creditsText.textContent = `Final score: ${score} points.`;
                        statusPanel.classList.remove('hidden');
                    }
                }
            }

            // Keyboard input handlers
            window.addEventListener('keydown', (e) => {
                const key = e.key.toLowerCase();
                if (key === 'arrowleft' || key === 'a') {
                    e.preventDefault();
                    handleMove('left');
                } else if (key === 'arrowright' || key === 'd') {
                    e.preventDefault();
                    handleMove('right');
                } else if (key === 'arrowup' || key === 'w') {
                    e.preventDefault();
                    handleMove('up');
                } else if (key === 'arrowdown' || key === 's') {
                    e.preventDefault();
                    handleMove('down');
                }
            });

            // Touch swipe handlers
            let touchStartX = 0;
            let touchStartY = 0;

            board.addEventListener('touchstart', (e) => {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
            }, { passive: false });

            board.addEventListener('touchmove', (e) => {
                e.preventDefault();
            }, { passive: false });

            board.addEventListener('touchend', (e) => {
                if (!touchStartX || !touchStartY) return;
                
                let diffX = e.changedTouches[0].clientX - touchStartX;
                let diffY = e.changedTouches[0].clientY - touchStartY;
                
                const threshold = 30;
                
                if (Math.abs(diffX) > Math.abs(diffY)) {
                    if (Math.abs(diffX) > threshold) {
                        handleMove(diffX > 0 ? 'right' : 'left');
                    }
                } else {
                    if (Math.abs(diffY) > threshold) {
                        handleMove(diffY > 0 ? 'down' : 'up');
                    }
                }
                
                touchStartX = 0;
                touchStartY = 0;
            }, { passive: false });

            window.resetGame = function() {
                grid = Array(16).fill(0);
                score = 0;
                earnedCredits = false;
                statusPanel.classList.add('hidden');
                renderEmptyBoard();
                spawnTile();
                spawnTile();
                drawBoard();
            };

            resetGame();
        });
    </script>
    @endpush
</x-webapp-layout>
