<x-webapp-layout
    title="{{ $seo['title'] ?? 'NCS Rhythm Tapper | Free Online Music Game' }}"
    description="{{ $seo['description'] ?? 'Play the NCS Rhythm Tapper! A free, online, neon-styled music rhythm game.' }}"
    keywords="{{ $seo['keywords'] ?? 'music game online, rhythm tapper' }}"
>
    <style>
        /* Override layout padding and spacing to force perfect fullscreen game */
        main {
            padding: 0 !important;
            margin: 0 !important;
            overflow: hidden !important;
            position: relative !important;
        }
        main > * + * {
            margin-top: 0 !important;
        }
    </style>

    {{-- Game Wrapper: Absolute inset-0 to perfectly fill the main container without gaps --}}
    <div id="game-theme-wrapper" class="absolute inset-0 flex flex-col bg-[#0a0a0c] text-white overflow-hidden">
        
        {{-- Game Container --}}
        <div id="canvas-container" class="relative w-full flex-1">
            
            {{-- Tour Guide Overlay (Only shows for first-time players) --}}
            <div id="tour-guide-overlay" class="absolute inset-0 z-50 hidden flex-col items-center justify-center bg-[#0a0a0c]/95 backdrop-blur-xl transition-opacity duration-500 p-4">
                <div class="max-w-md w-full bg-zinc-900 border border-zinc-800 rounded-3xl p-8 shadow-[0_0_50px_rgba(0,0,0,0.8)] relative overflow-hidden">
                    {{-- Decorative glowing orb --}}
                    <div class="absolute -top-20 -right-20 w-40 h-40 bg-amber-500/20 blur-[50px] rounded-full"></div>
                    
                    <div class="flex items-center gap-3 mb-6 relative z-10">
                        <div class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center shadow-[0_0_15px_rgba(245,158,11,0.5)]">
                            <i class="fa-solid fa-map text-black"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-amber-500 font-bold uppercase tracking-widest">Tutorial</p>
                            <h3 id="tour-title" class="text-xl font-black font-brand uppercase tracking-wider text-white">Welcome!</h3>
                        </div>
                    </div>
                    
                    <p id="tour-text" class="text-zinc-300 text-sm md:text-base leading-relaxed mb-8 min-h-[80px] relative z-10">
                        Get ready to test your reflexes and feel the beat. Let's take a quick tour!
                    </p>
                    
                    <div class="flex items-center justify-between relative z-10">
                        <div class="flex gap-1" id="tour-dots">
                            {{-- Dots injected by JS --}}
                        </div>
                        <button id="tour-next-btn" class="px-6 py-2 bg-zinc-800 hover:bg-zinc-700 text-white text-sm font-bold uppercase tracking-widest rounded-xl transition-colors border border-zinc-700">
                            Next <i class="fa-solid fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Start Screen Overlay --}}
            <div id="game-overlay" class="absolute inset-0 z-40 flex flex-col items-center justify-center bg-[#0a0a0c]/90 backdrop-blur-md transition-opacity duration-300 hidden">
                <div class="text-center mb-8">
                    <h1 class="font-brand text-5xl md:text-7xl font-black italic uppercase tracking-tighter title-text drop-shadow-[0_0_30px_rgba(255,255,255,0.2)] text-white">
                        NCS <span class="text-amber-500">Rhythm</span>
                    </h1>
                    <p class="text-zinc-400 font-bold tracking-[0.3em] uppercase mt-2 text-sm md:text-base">Tap the beats</p>
                </div>

                <div class="w-24 h-24 bg-gradient-to-br from-amber-500 to-amber-700 rounded-full flex items-center justify-center mb-8 shadow-[0_0_60px_rgba(245,158,11,0.6)] cursor-pointer hover:scale-110 transition-transform" id="start-btn-circle">
                    <i class="fa-solid fa-play text-4xl text-white ml-2"></i>
                </div>

                <h2 id="game-status" class="text-2xl md:text-3xl font-black text-white font-brand uppercase tracking-widest mb-4">Are you ready?</h2>
                <p class="text-zinc-400 text-sm md:text-base font-medium tracking-widest uppercase text-center px-4">Controls: D, F, J, K or Tap the screen</p>
            </div>

            {{-- Canvas --}}
            <canvas id="rhythm-canvas" class="block w-full h-full cursor-crosshair z-10 relative"></canvas>
        </div>

        {{-- SEO Content Section (Visually Hidden) --}}
        <div class="sr-only">
            <h2>About This Free Online Music Game</h2>
            <p>Welcome to the NCS Rhythm Tapper! This highly engaging, interactive browser game tests your reflexes and rhythm. Catch the neon beats! As the premier platform for royalty-free Hindi music and non-copyright soundtracks, NCS Hindi provides creators studio-quality audio. Our mini-games keep you entertained while discovering new beats. Play our free online rhythm game similar to piano tiles and beat making games.</p>
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const overlay = document.getElementById('game-overlay');
            const tourOverlay = document.getElementById('tour-guide-overlay');
            const canvas = document.getElementById('rhythm-canvas');
            const ctx = canvas.getContext('2d');
            const startBtnCircle = document.getElementById('start-btn-circle');

            function resizeCanvas() {
                const rect = canvas.parentElement.getBoundingClientRect();
                canvas.width = rect.width;
                canvas.height = rect.height;
            }
            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

            // Tour Guide Logic
            const tourSteps = [
                { title: "Welcome to NCS Rhythm!", text: "Get ready to test your reflexes to the beat of NCS Hindi! Let's take a quick 4-step tour to learn how to play." },
                { title: "Falling Notes", text: "During the game, glowing neon tiles will fall from the top of the screen down the 4 lanes." },
                { title: "The Strike Zone", text: "Don't tap too early! Wait patiently until the notes enter the glowing Strike Zone box at the very bottom." },
                { title: "The Controls", text: "Use the D, F, J, and K keys on your keyboard, or simply tap the lanes directly on your screen." },
                { title: "Combos", text: "Accuracy matters! Chain 'Perfect' hits together to multiply your score. Missing a note resets your combo to zero. Good luck!" }
            ];
            let currentTourStep = 0;
            const tourTitle = document.getElementById('tour-title');
            const tourText = document.getElementById('tour-text');
            const tourNextBtn = document.getElementById('tour-next-btn');
            const tourDots = document.getElementById('tour-dots');

            let isFirstTimePlaying = localStorage.getItem('ncs_rhythm_tour_completed') !== 'true';

            function updateTourUI() {
                tourTitle.textContent = tourSteps[currentTourStep].title;
                tourText.textContent = tourSteps[currentTourStep].text;
                
                if (currentTourStep === tourSteps.length - 1) {
                    tourNextBtn.innerHTML = 'Play Now <i class="fa-solid fa-gamepad ml-1"></i>';
                    tourNextBtn.classList.replace('bg-zinc-800', 'bg-amber-500');
                    tourNextBtn.classList.replace('text-white', 'text-black');
                }
                
                tourDots.innerHTML = '';
                for(let i=0; i<tourSteps.length; i++) {
                    const dot = document.createElement('div');
                    dot.className = `w-2 h-2 rounded-full ${i === currentTourStep ? 'bg-amber-500 w-4 transition-all' : 'bg-zinc-700'}`;
                    tourDots.appendChild(dot);
                }
            }

            if (isFirstTimePlaying) {
                tourOverlay.classList.remove('hidden');
                tourOverlay.classList.add('flex');
                updateTourUI();
            } else {
                overlay.classList.remove('hidden');
            }

            tourNextBtn.addEventListener('click', () => {
                if (currentTourStep < tourSteps.length - 1) {
                    currentTourStep++;
                    updateTourUI();
                } else {
                    localStorage.setItem('ncs_rhythm_tour_completed', 'true');
                    tourOverlay.style.opacity = '0';
                    setTimeout(() => {
                        tourOverlay.classList.add('hidden');
                        tourOverlay.classList.remove('flex');
                        overlay.classList.remove('hidden');
                    }, 500);
                }
            });


            // Audio Context
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            let audioCtx;
            const laneFrequencies = [261.63, 329.63, 392.00, 523.25]; // C4, E4, G4, C5
            
            function playDrumHit(laneIndex, isPerfect) {
                if (!audioCtx) audioCtx = new AudioContext();
                if (audioCtx.state === 'suspended') audioCtx.resume();
                
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                
                osc.type = isPerfect ? 'triangle' : 'sine';
                osc.frequency.setValueAtTime(laneFrequencies[laneIndex], audioCtx.currentTime);
                if (isPerfect) osc.frequency.exponentialRampToValueAtTime(40, audioCtx.currentTime + 0.15);
                
                gain.gain.setValueAtTime(isPerfect ? 0.8 : 0.4, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.15);
                
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.15);
            }

            let isPlaying = false;
            let animationId;
            let lastTime = 0;
            let score = 0;
            let combo = 0;
            let maxCombo = localStorage.getItem('ncs_rhythm_max_combo') || 0;
            let timeLeft = 45;
            
            const laneColors = ['#ef4444', '#3b82f6', '#eab308', '#22c55e'];
            const laneKeys = ['D', 'F', 'J', 'K'];
            let activeKeys = [false, false, false, false];

            let notes = [];
            let particles = [];
            let floatingTexts = [];

            let noteSpeed = canvas.height * 0.7; 
            let spawnTimer = 0;
            let spawnInterval = 550;

            function spawnNote() {
                const lane = Math.floor(Math.random() * 4);
                notes.push({
                    lane: lane,
                    y: -100,
                    h: 60,
                    active: true,
                    scored: false
                });
            }

            function spawnParticles(x, y, color) {
                for (let i = 0; i < 25; i++) {
                    particles.push({
                        x: x,
                        y: y,
                        vx: (Math.random() - 0.5) * 800,
                        vy: (Math.random() - 0.5) * 800 - 100,
                        life: 1.0,
                        color: color,
                        size: Math.random() * 5 + 2
                    });
                }
            }

            function spawnText(text, x, y, color) {
                floatingTexts.push({ text, x, y, life: 1.0, color, scale: 0 });
            }

            function hitLane(lane) {
                if (!isPlaying) return;
                
                activeKeys[lane] = true;
                setTimeout(() => activeKeys[lane] = false, 150);

                const trackWidth = Math.min(500, canvas.width * 0.9);
                const offsetX = (canvas.width - trackWidth) / 2;
                const laneWidth = trackWidth / 4;
                const laneCenter = offsetX + (lane * laneWidth) + (laneWidth / 2);
                
                const STRIKE_ZONE_Y = canvas.height * 0.82;
                const STRIKE_ZONE_H = 80;

                let targetNote = null;
                for (let i = 0; i < notes.length; i++) {
                    if (notes[i].lane === lane && notes[i].active && !notes[i].scored) {
                        targetNote = notes[i];
                        break; 
                    }
                }

                if (targetNote) {
                    const noteBottom = targetNote.y + targetNote.h;
                    const zoneCenter = STRIKE_ZONE_Y + STRIKE_ZONE_H/2;
                    const dist = Math.abs((targetNote.y + targetNote.h/2) - zoneCenter);
                    
                    const hitTolerance = 100;
                    
                    if (dist < hitTolerance) {
                        targetNote.active = false;
                        targetNote.scored = true;
                        
                        let hitType = '';
                        let points = 0;
                        let isPerfect = false;
                        
                        if (dist < 30) {
                            hitType = 'PERFECT'; points = 100; isPerfect = true; combo++;
                            spawnParticles(laneCenter, zoneCenter, laneColors[lane]);
                        } else if (dist < 65) {
                            hitType = 'GOOD'; points = 50; combo++;
                            spawnParticles(laneCenter, zoneCenter, '#ffffff');
                        } else {
                            hitType = 'OKAY'; points = 10; combo = 0;
                        }

                        score += points + (combo * 5);
                        spawnText(hitType, laneCenter, STRIKE_ZONE_Y - 50, isPerfect ? laneColors[lane] : '#ffffff');
                        playDrumHit(lane, isPerfect);
                        updateMaxCombo();
                        return;
                    }
                }
                
                combo = 0;
                spawnText('MISS', laneCenter, STRIKE_ZONE_Y - 50, '#ef4444');
            }

            function updateMaxCombo() {
                if (combo > maxCombo) {
                    maxCombo = combo;
                    localStorage.setItem('ncs_rhythm_max_combo', maxCombo);
                }
            }

            function update(dt) {
                if (!isPlaying) return;
                
                timeLeft -= dt;
                if (timeLeft <= 0) {
                    timeLeft = 0;
                    endGame();
                    return;
                }
                
                noteSpeed = canvas.height * 0.7; 

                spawnTimer += dt * 1000;
                if (spawnTimer > spawnInterval) {
                    spawnNote();
                    spawnTimer = 0;
                    spawnInterval = Math.max(300, spawnInterval - 1); 
                }

                const STRIKE_ZONE_Y = canvas.height * 0.82;
                const STRIKE_ZONE_H = 80;

                for (let i = notes.length - 1; i >= 0; i--) {
                    let note = notes[i];
                    if (!note.active) continue;
                    
                    note.y += noteSpeed * dt;
                    
                    if (note.y > STRIKE_ZONE_Y + STRIKE_ZONE_H && !note.scored) {
                        note.active = false;
                        note.scored = true;
                        combo = 0;
                        
                        const trackWidth = Math.min(500, canvas.width * 0.9);
                        const offsetX = (canvas.width - trackWidth) / 2;
                        const laneWidth = trackWidth / 4;
                        const laneCenter = offsetX + (note.lane * laneWidth) + (laneWidth / 2);
                        
                        spawnText('MISS', laneCenter, canvas.height - 80, '#ef4444');
                    }
                }
                
                notes = notes.filter(n => n.active);

                for (let i = particles.length - 1; i >= 0; i--) {
                    let p = particles[i];
                    p.x += p.vx * dt;
                    p.y += p.vy * dt;
                    p.vy += 1200 * dt; 
                    p.life -= dt * 2;
                    if (p.life <= 0) particles.splice(i, 1);
                }

                for (let i = floatingTexts.length - 1; i >= 0; i--) {
                    let t = floatingTexts[i];
                    t.y -= 80 * dt;
                    t.life -= dt * 1.5;
                    t.scale = Math.min(1.2, t.scale + dt * 5);
                    if (t.life <= 0) floatingTexts.splice(i, 1);
                }
            }

            function draw() {
                // Background
                ctx.fillStyle = '#0a0a0c'; 
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Grid/Ambient Background Effect
                ctx.strokeStyle = 'rgba(255,255,255,0.03)';
                ctx.lineWidth = 1;
                ctx.beginPath();
                for(let i=0; i<canvas.height; i+=40) {
                    ctx.moveTo(0, i + (lastTime/20)%40);
                    ctx.lineTo(canvas.width, i + (lastTime/20)%40);
                }
                ctx.stroke();

                // Track Calculation
                const trackWidth = Math.min(500, canvas.width * 0.9);
                const offsetX = (canvas.width - trackWidth) / 2;
                const laneWidth = trackWidth / 4;

                const STRIKE_ZONE_Y = canvas.height * 0.82;
                const STRIKE_ZONE_H = 80;

                // Track Background
                ctx.fillStyle = 'rgba(255,255,255,0.02)';
                ctx.fillRect(offsetX, 0, trackWidth, canvas.height);

                for (let i = 0; i < 4; i++) {
                    const laneX = offsetX + (i * laneWidth);
                    
                    // Lane separator
                    ctx.beginPath();
                    ctx.moveTo(laneX, 0);
                    ctx.lineTo(laneX, canvas.height);
                    ctx.strokeStyle = 'rgba(255,255,255,0.1)';
                    ctx.lineWidth = i === 0 ? 3 : 1;
                    ctx.stroke();

                    if (i === 3) {
                        ctx.beginPath();
                        ctx.moveTo(laneX + laneWidth, 0);
                        ctx.lineTo(laneX + laneWidth, canvas.height);
                        ctx.lineWidth = 3;
                        ctx.stroke();
                    }

                    // Strike Pad Key Text
                    const padY = STRIKE_ZONE_Y;
                    ctx.fillStyle = '#64748b';
                    ctx.font = '900 24px "Inter", sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(laneKeys[i], laneX + laneWidth/2, padY + STRIKE_ZONE_H/2 + 8);

                    // Active Lane Glow
                    if (activeKeys[i]) {
                        const grad = ctx.createLinearGradient(0, STRIKE_ZONE_Y, 0, 0);
                        grad.addColorStop(0, laneColors[i]);
                        grad.addColorStop(1, 'transparent');
                        ctx.fillStyle = grad;
                        ctx.globalAlpha = 0.4;
                        ctx.fillRect(laneX, 0, laneWidth, STRIKE_ZONE_Y + STRIKE_ZONE_H);
                        ctx.globalAlpha = 1.0;
                    }
                }

                // Strike Zone Box
                ctx.beginPath();
                ctx.roundRect(offsetX, STRIKE_ZONE_Y, trackWidth, STRIKE_ZONE_H, 12);
                ctx.strokeStyle = 'rgba(255,255,255,0.2)';
                ctx.lineWidth = 4;
                ctx.stroke();
                
                // Strike Line
                ctx.beginPath();
                ctx.moveTo(offsetX, STRIKE_ZONE_Y + STRIKE_ZONE_H/2);
                ctx.lineTo(offsetX + trackWidth, STRIKE_ZONE_Y + STRIKE_ZONE_H/2);
                ctx.strokeStyle = 'rgba(255,255,255,0.1)';
                ctx.lineWidth = 2;
                ctx.stroke();

                // Notes
                for (let note of notes) {
                    const x = offsetX + (note.lane * laneWidth) + (laneWidth * 0.15);
                    const w = laneWidth * 0.7;
                    const color = laneColors[note.lane];
                    
                    ctx.fillStyle = color;
                    ctx.shadowColor = color;
                    ctx.shadowBlur = 25;
                    
                    ctx.beginPath();
                    ctx.roundRect(x, note.y, w, note.h, 12);
                    ctx.fill();
                    
                    // Inner bright core
                    ctx.fillStyle = '#ffffff';
                    ctx.shadowBlur = 0;
                    ctx.beginPath();
                    ctx.roundRect(x + w*0.3, note.y + 4, w*0.4, note.h - 8, 8);
                    ctx.fill();
                }

                // Particles
                for (let p of particles) {
                    ctx.fillStyle = p.color;
                    ctx.globalAlpha = Math.max(0, p.life);
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    ctx.fill();
                }
                ctx.globalAlpha = 1.0;

                // Floating Texts
                for (let t of floatingTexts) {
                    ctx.save();
                    ctx.translate(t.x, t.y);
                    ctx.scale(t.scale, t.scale);
                    ctx.fillStyle = t.color;
                    ctx.globalAlpha = Math.max(0, t.life);
                    ctx.font = '900 36px "Inter", sans-serif';
                    ctx.textAlign = 'center';
                    ctx.shadowColor = 'rgba(0,0,0,0.8)';
                    ctx.shadowBlur = 8;
                    ctx.fillText(t.text, 0, 0);
                    ctx.restore();
                }

                // HUD (Score & Combo)
                ctx.fillStyle = '#ffffff';
                ctx.font = '900 32px "Inter", sans-serif';
                ctx.textAlign = 'left';
                
                // Left HUD
                const hudY = 60;
                ctx.fillText(`SCORE`, 30, hudY);
                ctx.fillStyle = '#eab308';
                ctx.fillText(score, 30, hudY + 40);
                
                // Center HUD (Time Remaining)
                ctx.textAlign = 'center';
                ctx.fillStyle = '#ffffff';
                ctx.font = '900 20px "Inter", sans-serif';
                ctx.fillText(`TIME REMAINING`, canvas.width / 2, hudY);
                ctx.fillStyle = timeLeft < 10 ? '#ef4444' : '#38bdf8';
                ctx.font = '900 32px "Inter", sans-serif';
                ctx.fillText(`${Math.ceil(timeLeft)}s`, canvas.width / 2, hudY + 40);
                
                // Right HUD
                ctx.textAlign = 'right';
                ctx.fillStyle = '#ffffff';
                ctx.fillText(`MAX COMBO: ${maxCombo}`, canvas.width - 30, hudY);
                ctx.fillStyle = combo > 10 ? '#ef4444' : '#38bdf8';
                ctx.font = `900 ${32 + Math.min(20, combo/2)}px "Inter", sans-serif`;
                ctx.fillText(`${combo}x COMBO`, canvas.width - 30, hudY + 40);
            }

            function gameLoop(timestamp) {
                if (!lastTime) lastTime = timestamp;
                const dt = (timestamp - lastTime) / 1000;
                lastTime = timestamp;

                update(dt);
                draw();

                if (isPlaying) {
                    animationId = requestAnimationFrame(gameLoop);
                }
            }

            function endGame() {
                isPlaying = false;
                cancelAnimationFrame(animationId);
                
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
                overlay.classList.remove('hidden');
                
                document.getElementById('game-status').innerHTML = `Game Over!<br>Score: <span class="text-amber-500">${score}</span>`;
                
                @auth
                if (score >= 1000) {
                    $.post('{{ route('webapp.game.award-credits') }}', { score: score, _token: '{{ csrf_token() }}' }, function(res) {
                        if (res.success) {
                            if (window.toastr) {
                                toastr.success(res.message);
                            } else {
                                alert(res.message);
                            }
                        }
                    }).fail(function(xhr) {
                        console.error('Failed to award credits:', xhr);
                    });
                }
                @endauth
            }

            function startGame() {
                if (!audioCtx) audioCtx = new AudioContext();
                audioCtx.resume();
                
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
                
                isPlaying = true;
                score = 0;
                combo = 0;
                timeLeft = 45;
                notes = [];
                particles = [];
                floatingTexts = [];
                spawnTimer = 0;
                spawnInterval = 550;
                lastTime = performance.now();
                
                resizeCanvas();
                animationId = requestAnimationFrame(gameLoop);
            }

            window.addEventListener('keydown', (e) => {
                if (!isPlaying) return;
                const keyMap = {'d':0, 'f':1, 'j':2, 'k':3};
                const lane = keyMap[e.key.toLowerCase()];
                if (lane !== undefined) {
                    hitLane(lane);
                }
            });

            canvas.addEventListener('mousedown', (e) => {
                if (!isPlaying) return;
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left;
                
                const trackWidth = Math.min(500, canvas.width * 0.9);
                const offsetX = (canvas.width - trackWidth) / 2;
                
                if (x >= offsetX && x <= offsetX + trackWidth) {
                    const laneWidth = trackWidth / 4;
                    const lane = Math.floor((x - offsetX) / laneWidth);
                    if (lane >= 0 && lane <= 3) hitLane(lane);
                }
            });
            
            canvas.addEventListener('touchstart', (e) => {
                if (!isPlaying) return;
                e.preventDefault(); 
                const rect = canvas.getBoundingClientRect();
                const trackWidth = Math.min(500, canvas.width * 0.9);
                const offsetX = (canvas.width - trackWidth) / 2;
                
                for (let i=0; i < e.changedTouches.length; i++) {
                    const x = e.changedTouches[i].clientX - rect.left;
                    if (x >= offsetX && x <= offsetX + trackWidth) {
                        const laneWidth = trackWidth / 4;
                        const lane = Math.floor((x - offsetX) / laneWidth);
                        if (lane >= 0 && lane <= 3) hitLane(lane);
                    }
                }
            }, {passive: false});

            startBtnCircle.addEventListener('click', startGame);
            
            draw();
        });
    </script>
    @endpush
</x-webapp-layout>
