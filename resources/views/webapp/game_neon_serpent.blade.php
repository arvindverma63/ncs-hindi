<x-webapp-layout
    title="{{ $seo['title'] ?? 'NCS Neon Serpent | Retro Arcade Snake Game' }}"
    description="{{ $seo['description'] ?? 'Play Neon Serpent and earn rewards.' }}"
    keywords="{{ $seo['keywords'] ?? 'snake game, retro game' }}"
>
    <style>
        .board-container {
            position: relative;
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            background: #09090c;
            border: 2px solid #27272a;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }
        #snake-canvas {
            display: block;
            width: 100%;
            aspect-ratio: 1;
            background: #09090c;
        }

        /* Mobile controls grid layout */
        .controls-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(3, 1fr);
            gap: 10px;
            width: 160px;
            height: 160px;
            margin: 20px auto 0;
        }
        .ctrl-btn {
            background: #18181b;
            border: 1px solid #27272a;
            border-radius: 16px;
            color: #a1a1aa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.1s ease-in-out;
            cursor: pointer;
        }
        .ctrl-btn:active {
            background: #f59e0b;
            color: #000;
            border-color: #f59e0b;
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.4);
            transform: scale(0.95);
        }
        .ctrl-up { grid-column: 2; grid-row: 1; }
        .ctrl-left { grid-column: 1; grid-row: 2; }
        .ctrl-right { grid-column: 3; grid-row: 2; }
        .ctrl-down { grid-column: 2; grid-row: 3; }
    </style>

    <div class="max-w-xl mx-auto py-8 px-4 text-center">
        {{-- Header / Breadcrumbs --}}
        <div class="flex items-center justify-between gap-4 mb-8">
            <div class="text-left">
                <p class="text-[10px] text-amber-500 font-black uppercase tracking-widest">Retro Arcade</p>
                <h1 class="font-brand text-3xl sm:text-4xl font-black text-white uppercase tracking-tighter">Neon Serpent</h1>
            </div>
            <a href="{{ route('webapp.game') }}" class="btn-vault px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest">
                Back to Arcade
            </a>
        </div>

        {{-- Score Panel --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-zinc-900/60 border border-zinc-800 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500">Current Score</p>
                <h3 id="score-val" class="text-xl font-black text-white mt-1">0</h3>
            </div>
            <div class="bg-zinc-900/60 border border-zinc-800 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500">Goal to Win</p>
                <h3 class="text-xl font-black text-amber-500 mt-1">150 Points</h3>
            </div>
        </div>

        {{-- Instructions --}}
        <div class="bg-zinc-950 border border-zinc-900 rounded-2xl p-4 mb-6">
            <p class="text-xs text-zinc-400 font-medium leading-relaxed">
                Use your <strong class="text-white">Arrow keys / WASD</strong> or tap the <strong class="text-white">on-screen D-Pad</strong>. Avoid hitting the walls or your own tail. Collect glowing nodes to grow!
            </p>
        </div>

        {{-- Visualizer Canvas --}}
        <div class="board-container">
            <canvas id="snake-canvas" width="400" height="400"></canvas>
        </div>

        {{-- On-Screen Mobile Controller D-Pad --}}
        <div class="controls-grid md:hidden">
            <button class="ctrl-btn ctrl-up" id="btn-up" aria-label="Up"><i class="fa-solid fa-caret-up"></i></button>
            <button class="ctrl-btn ctrl-left" id="btn-left" aria-label="Left"><i class="fa-solid fa-caret-left"></i></button>
            <button class="ctrl-btn ctrl-right" id="btn-right" aria-label="Right"><i class="fa-solid fa-caret-right"></i></button>
            <button class="ctrl-btn ctrl-down" id="btn-down" aria-label="Down"><i class="fa-solid fa-caret-down"></i></button>
        </div>

        {{-- Game Over Panel --}}
        <div id="game-over-panel" class="mt-8 hidden">
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6">
                <h3 id="game-status-text" class="text-2xl font-black font-brand uppercase tracking-tight text-white mb-2">Game Over</h3>
                <p id="game-credits-text" class="text-xs text-zinc-500 mb-6 uppercase tracking-wider"></p>
                <button type="button" id="play-again-btn" class="btn-vault w-full sm:w-auto px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest">
                    Play Again
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('snake-canvas');
            const ctx = canvas.getContext('2d');
            const scoreDisplay = document.getElementById('score-val');
            const statusPanel = document.getElementById('game-over-panel');
            const statusText = document.getElementById('game-status-text');
            const creditsText = document.getElementById('game-credits-text');
            const playAgainBtn = document.getElementById('play-again-btn');

            // Sound Effects Generator (Web Audio API)
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            let audioCtx;
            function playSound(type) {
                if (!audioCtx) audioCtx = new AudioContext();
                if (audioCtx.state === 'suspended') audioCtx.resume();
                
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                
                if (type === 'eat') {
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(523.25, audioCtx.currentTime); // C5
                    osc.frequency.exponentialRampToValueAtTime(880, audioCtx.currentTime + 0.1); // A5
                    gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.12);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.12);
                } else if (type === 'die') {
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(220, audioCtx.currentTime);
                    osc.frequency.linearRampToValueAtTime(80, audioCtx.currentTime + 0.4);
                    gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.4);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.4);
                }
            }

            // Grid variables
            const gridSize = 20;
            const tileCount = canvas.width / gridSize;

            let snake = [];
            let dx = gridSize;
            let dy = 0;
            let foodX;
            let foodY;
            let score = 0;
            let gameInterval;
            let isRunning = false;
            let earnedCredits = false;
            let particles = [];

            // Spawn neon particles
            function spawnParticles(x, y, color) {
                for (let i = 0; i < 15; i++) {
                    particles.push({
                        x: x + gridSize / 2,
                        y: y + gridSize / 2,
                        vx: (Math.random() - 0.5) * 6,
                        vy: (Math.random() - 0.5) * 6,
                        alpha: 1.0,
                        color: color,
                        size: Math.random() * 3 + 1
                    });
                }
            }

            function updateParticles() {
                for (let i = particles.length - 1; i >= 0; i--) {
                    let p = particles[i];
                    p.x += p.vx;
                    p.y += p.vy;
                    p.alpha -= 0.04;
                    if (p.alpha <= 0) particles.splice(i, 1);
                }
            }

            function drawParticles() {
                particles.forEach(p => {
                    ctx.save();
                    ctx.globalAlpha = p.alpha;
                    ctx.fillStyle = p.color;
                    ctx.shadowBlur = 10;
                    ctx.shadowColor = p.color;
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.restore();
                });
            }

            function initGame() {
                snake = [
                    { x: 160, y: 200 },
                    { x: 140, y: 200 },
                    { x: 120, y: 200 }
                ];
                dx = gridSize;
                dy = 0;
                score = 0;
                earnedCredits = false;
                scoreDisplay.textContent = score;
                statusPanel.classList.add('hidden');
                spawnFood();
                isRunning = true;
                if (gameInterval) clearInterval(gameInterval);
                gameInterval = setInterval(mainLoop, 110);
            }

            function spawnFood() {
                foodX = Math.floor(Math.random() * tileCount) * gridSize;
                foodY = Math.floor(Math.random() * tileCount) * gridSize;
                // Don't spawn food inside the snake
                snake.forEach(part => {
                    if (part.x === foodX && part.y === foodY) spawnFood();
                });
            }

            function mainLoop() {
                if (!isRunning) return;
                
                // Move snake head
                const head = { x: snake[0].x + dx, y: snake[0].y + dy };
                snake.unshift(head);

                // Check food collection
                if (head.x === foodX && head.y === foodY) {
                    score += 10;
                    scoreDisplay.textContent = score;
                    playSound('eat');
                    spawnParticles(foodX, foodY, '#ff007f');
                    spawnFood();
                    
                    // Award credits check (Award at 150 points)
                    if (score >= 150 && !earnedCredits) {
                        earnedCredits = true;
                        awardGameCredits(score);
                    }
                } else {
                    snake.pop();
                }

                // Check collisions (Wall or Self)
                if (head.x < 0 || head.x >= canvas.width || head.y < 0 || head.y >= canvas.height || checkSelfCollision(head)) {
                    gameOver();
                }

                updateParticles();
                draw();
            }

            function checkSelfCollision(head) {
                for (let i = 1; i < snake.length; i++) {
                    if (snake[i].x === head.x && snake[i].y === head.y) return true;
                }
                return false;
            }

            function draw() {
                // Background
                ctx.fillStyle = '#09090c';
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Grid background guidelines
                ctx.strokeStyle = 'rgba(255, 255, 255, 0.02)';
                ctx.lineWidth = 1;
                for (let i = 0; i < canvas.width; i += gridSize) {
                    ctx.beginPath();
                    ctx.moveTo(i, 0); ctx.lineTo(i, canvas.height);
                    ctx.moveTo(0, i); ctx.lineTo(canvas.width, i);
                    ctx.stroke();
                }

                // Food (glowing red/pink sphere)
                ctx.save();
                ctx.fillStyle = '#ff007f';
                ctx.shadowBlur = 18;
                ctx.shadowColor = '#ff007f';
                ctx.beginPath();
                ctx.arc(foodX + gridSize / 2, foodY + gridSize / 2, gridSize / 2.5, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();

                // Snake (glowing neon cyan body)
                snake.forEach((part, index) => {
                    ctx.save();
                    const isHead = index === 0;
                    ctx.fillStyle = isHead ? '#ffffff' : '#00ffcc';
                    ctx.shadowBlur = isHead ? 20 : 12;
                    ctx.shadowColor = '#00ffcc';
                    ctx.beginPath();
                    ctx.roundRect(part.x + 2, part.y + 2, gridSize - 4, gridSize - 4, 6);
                    ctx.fill();
                    ctx.restore();
                });

                drawParticles();
            }

            function gameOver() {
                isRunning = false;
                clearInterval(gameInterval);
                playSound('die');
                
                statusText.textContent = "Game Over";
                creditsText.textContent = `Final score: ${score} points.`;
                statusPanel.classList.remove('hidden');
            }

            function awardGameCredits(finalScore) {
                @auth
                $.post('{{ route('webapp.game.award-credits') }}', { 
                    score: finalScore, 
                    game: 'Neon Serpent', 
                    _token: '{{ csrf_token() }}' 
                }, function(res) {
                    if (res.success) {
                        statusText.textContent = "Victory!";
                        creditsText.innerHTML = `You scored ${finalScore} points and earned <span class="text-amber-500 font-bold">50 NCS Credits</span>!`;
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

            // Keyboard controls
            window.addEventListener('keydown', (e) => {
                const key = e.key.toLowerCase();
                if (['arrowleft', 'a', 'arrowright', 'd', 'arrowup', 'w', 'arrowdown', 's'].includes(key)) {
                    e.preventDefault();
                }
                if ((key === 'arrowleft' || key === 'a') && dx === 0) {
                    dx = -gridSize; dy = 0;
                } else if ((key === 'arrowright' || key === 'd') && dx === 0) {
                    dx = gridSize; dy = 0;
                } else if ((key === 'arrowup' || key === 'w') && dy === 0) {
                    dx = 0; dy = -gridSize;
                } else if ((key === 'arrowdown' || key === 's') && dy === 0) {
                    dx = 0; dy = gridSize;
                }
            });

            // Touch swipe controls directly on canvas
            let touchStartX = 0;
            let touchStartY = 0;

            canvas.addEventListener('touchstart', (e) => {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
            }, { passive: false });

            canvas.addEventListener('touchmove', (e) => {
                e.preventDefault();
            }, { passive: false });

            canvas.addEventListener('touchend', (e) => {
                if (!touchStartX || !touchStartY) return;

                let diffX = e.changedTouches[0].clientX - touchStartX;
                let diffY = e.changedTouches[0].clientY - touchStartY;

                const threshold = 30;

                if (Math.abs(diffX) > Math.abs(diffY)) {
                    if (Math.abs(diffX) > threshold) {
                        if (diffX > 0 && dx === 0) {
                            dx = gridSize; dy = 0;
                        } else if (diffX < 0 && dx === 0) {
                            dx = -gridSize; dy = 0;
                        }
                    }
                } else {
                    if (Math.abs(diffY) > threshold) {
                        if (diffY > 0 && dy === 0) {
                            dx = 0; dy = gridSize;
                        } else if (diffY < 0 && dy === 0) {
                            dx = 0; dy = -gridSize;
                        }
                    }
                }

                touchStartX = 0;
                touchStartY = 0;
            }, { passive: false });

            // On-screen D-Pad controls
            document.getElementById('btn-up').addEventListener('click', () => { if (dy === 0) { dx = 0; dy = -gridSize; } });
            document.getElementById('btn-left').addEventListener('click', () => { if (dx === 0) { dx = -gridSize; dy = 0; } });
            document.getElementById('btn-right').addEventListener('click', () => { if (dx === 0) { dx = gridSize; dy = 0; } });
            document.getElementById('btn-down').addEventListener('click', () => { if (dy === 0) { dx = 0; dy = gridSize; } });

            playAgainBtn.addEventListener('click', initGame);

            initGame();
        });
    </script>
    @endpush
</x-webapp-layout>
