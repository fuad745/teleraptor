<?php
// ========================================================
// TELERAPTOR LINK-IN-BIO LANDING PAGE (IMPECCABLE UI/UX)
// URL: https://teleraptor.parakichner.com/bio.php
// Optimized for TikTok, Instagram & Facebook Bio Conversion
// ========================================================

if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

$CHANNEL_ID = defined('CHANNEL_ID') ? CHANNEL_ID : "@teleraptor_ig";
$TELEGRAM_URL = "https://t.me/" . ltrim($CHANNEL_ID, '@');
$ADMIN_CONTACT = defined('ADMIN_USERNAME') ? "https://t.me/" . ltrim(ADMIN_USERNAME, '@') : "https://t.me/Fuad761";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Teleraptor • Free Android Gems & Remote Jobs</title>
    <meta name="description" content="Official link hub for Teleraptor: Free open-source Android APKs, ad-free music & video downloaders, and remote tech jobs.">
    <meta name="theme-color" content="#06080d">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-display: 'Outfit', -apple-system, sans-serif;
            --font-body: 'Plus Jakarta Sans', -apple-system, sans-serif;
        }
        body {
            background-color: #06080d;
            color: #f1f5f9;
            font-family: var(--font-body);
            -webkit-tap-highlight-color: transparent;
        }
        h1, h2, h3, .font-display { font-family: var(--font-display); }
        
        .ambient-glow {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100vw;
            max-width: 650px;
            height: 480px;
            background: radial-gradient(circle at 50% 20%, rgba(14, 165, 233, 0.18) 0%, rgba(99, 102, 241, 0.1) 45%, rgba(6, 8, 13, 0) 75%);
            pointer-events: none;
            z-index: 0;
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.5);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card:hover {
            transform: translateY(-2px);
            border-color: rgba(56, 189, 248, 0.35);
            box-shadow: 0 10px 25px -4px rgba(14, 165, 233, 0.18);
        }
        .glass-card:active {
            transform: scale(0.985);
        }

        .hero-cta {
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 50%, #4f46e5 100%);
            box-shadow: 0 0 35px rgba(2, 132, 199, 0.45), 0 8px 20px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(147, 197, 253, 0.35);
            animation: pulse-glow 3s infinite alternate;
        }
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 25px rgba(2, 132, 199, 0.35), 0 4px 15px rgba(0, 0, 0, 0.3); }
            100% { box-shadow: 0 0 45px rgba(56, 189, 248, 0.65), 0 8px 30px rgba(14, 165, 233, 0.3); }
        }

        .live-dot {
            animation: pulse-dot 1.8s infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.6; }
        }
    </style>
</head>
<body class="min-h-screen px-4 py-8 flex flex-col justify-between items-center relative overflow-x-hidden">
    <div class="ambient-glow"></div>

    <main class="w-full max-w-md mx-auto text-center relative z-10">
        <!-- Profile Avatar with Verified Badge -->
        <div class="relative inline-block mb-4 group">
            <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-cyan-500 via-blue-600 to-indigo-600 p-[2px] shadow-2xl shadow-cyan-500/25">
                <div class="w-full h-full bg-[#0b1120] rounded-[22px] flex items-center justify-center text-4xl transform group-hover:scale-105 transition duration-300">
                    ⚡
                </div>
            </div>
            <div class="absolute -bottom-1 -right-1 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-full p-1.5 shadow-lg border-2 border-[#06080d]" title="Verified Channel">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <!-- Channel Title & Verified Badge -->
        <h1 class="text-2xl font-black text-white tracking-tight flex items-center justify-center gap-2">
            Teleraptor
            <span class="text-[11px] font-bold text-cyan-300 bg-cyan-950/80 px-2.5 py-0.5 rounded-full border border-cyan-800/60 uppercase tracking-wider">
                Official
            </span>
        </h1>
        
        <p class="text-xs text-slate-400 mt-1.5 mb-2 font-medium max-w-xs mx-auto">
            100% Free Open-Source Android Gems • Direct APK Downloads • Daily Remote USD Jobs
        </p>

        <!-- Live Member Status Indicator -->
        <div class="inline-flex items-center gap-2 bg-emerald-950/60 border border-emerald-800/50 px-3 py-1 rounded-full text-[11px] text-emerald-300 font-semibold mb-6 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-emerald-400 live-dot"></span>
            <span>1,480+ Members Active in Channel</span>
        </div>

        <!-- Big Hero CTA: Join Telegram -->
        <div class="mb-8">
            <a href="<?= $TELEGRAM_URL ?>" target="_blank" rel="noopener"
               class="hero-cta block w-full py-4 px-6 rounded-2xl text-white text-left relative overflow-hidden transition-all duration-300 hover:brightness-110 active:scale-[0.98]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-2xl border border-white/20">
                            ✈️
                        </div>
                        <div>
                            <div class="text-sm font-extrabold tracking-wide uppercase font-display">Join Official Telegram</div>
                            <div class="text-[11px] text-cyan-100 opacity-90 font-medium">Get all direct APK releases & pinned tutorials</div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-cyan-200 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>

        <!-- Section: Trending Android Apps -->
        <div class="space-y-3 mb-8 text-left">
            <div class="flex justify-between items-center px-1 mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 flex items-center gap-1.5 font-display">
                    <span>🔥</span> Trending Android Apps
                </span>
                <span class="text-[10px] text-slate-500 font-medium bg-slate-900 px-2 py-0.5 rounded-md border border-slate-800">Direct APKs</span>
            </div>

            <!-- Spotube -->
            <a href="<?= $TELEGRAM_URL ?>" target="_blank" rel="noopener" class="glass-card block p-3.5 rounded-2xl group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-500/10 border border-emerald-500/30 flex items-center justify-center text-xl group-hover:scale-105 transition">
                            🎵
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h2 class="text-sm font-bold text-white group-hover:text-cyan-300 transition">Spotube</h2>
                                <span class="text-[9px] bg-emerald-950 text-emerald-400 border border-emerald-800/80 px-1.5 py-0.2 rounded font-bold uppercase">No Ads</span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5">Spotify Alternative • 320kbps MP3 Download</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-cyan-400 bg-cyan-950/70 border border-cyan-800/60 px-2.5 py-1 rounded-xl">Get APK →</span>
                </div>
            </a>

            <!-- Seal Downloader -->
            <a href="<?= $TELEGRAM_URL ?>" target="_blank" rel="noopener" class="glass-card block p-3.5 rounded-2xl group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500/20 to-blue-500/10 border border-cyan-500/30 flex items-center justify-center text-xl group-hover:scale-105 transition">
                            📥
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h2 class="text-sm font-bold text-white group-hover:text-cyan-300 transition">Seal Downloader</h2>
                                <span class="text-[9px] bg-cyan-950 text-cyan-400 border border-cyan-800/80 px-1.5 py-0.2 rounded font-bold uppercase">1000+ Sites</span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5">Save TikTok / Reels / YT without watermarks</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-cyan-400 bg-cyan-950/70 border border-cyan-800/60 px-2.5 py-1 rounded-xl">Get APK →</span>
                </div>
            </a>

            <!-- LocalSend -->
            <a href="<?= $TELEGRAM_URL ?>" target="_blank" rel="noopener" class="glass-card block p-3.5 rounded-2xl group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500/20 to-purple-500/10 border border-indigo-500/30 flex items-center justify-center text-xl group-hover:scale-105 transition">
                            📡
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h2 class="text-sm font-bold text-white group-hover:text-cyan-300 transition">LocalSend</h2>
                                <span class="text-[9px] bg-indigo-950 text-indigo-400 border border-indigo-800/80 px-1.5 py-0.2 rounded font-bold uppercase">No Internet</span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5">AirDrop for Android, PC & Mac (Local Wi-Fi)</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-cyan-400 bg-cyan-950/70 border border-cyan-800/60 px-2.5 py-1 rounded-xl">Get APK →</span>
                </div>
            </a>

            <!-- Immich -->
            <a href="<?= $TELEGRAM_URL ?>" target="_blank" rel="noopener" class="glass-card block p-3.5 rounded-2xl group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/20 to-orange-500/10 border border-amber-500/30 flex items-center justify-center text-xl group-hover:scale-105 transition">
                            🖼️
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h2 class="text-sm font-bold text-white group-hover:text-cyan-300 transition">Immich</h2>
                                <span class="text-[9px] bg-amber-950 text-amber-400 border border-amber-800/80 px-1.5 py-0.2 rounded font-bold uppercase">Unlimited</span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5">Self-hosted unlimited Google Photos alternative</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-cyan-400 bg-cyan-950/70 border border-cyan-800/60 px-2.5 py-1 rounded-xl">Get APK →</span>
                </div>
            </a>
        </div>

        <!-- Section: Remote Worldwide Jobs -->
        <div class="space-y-3 mb-8 text-left">
            <div class="flex justify-between items-center px-1 mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 flex items-center gap-1.5 font-display">
                    <span>💼</span> Daily Remote Jobs (USD)
                </span>
                <span class="text-[10px] text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded-md border border-emerald-800/60 font-semibold">Crypto / Wire</span>
            </div>

            <a href="<?= $TELEGRAM_URL ?>" target="_blank" rel="noopener" class="glass-card block p-3.5 rounded-2xl group border-l-2 border-l-emerald-500">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-sm font-bold text-white group-hover:text-emerald-300 transition">Senior Flutter / Mobile Dev</div>
                        <p class="text-[11px] text-slate-400 mt-0.5">100% Worldwide Remote • Fintech</p>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-black text-emerald-400 font-display">$5,500/mo</div>
                        <span class="text-[10px] text-slate-500">Apply Free</span>
                    </div>
                </div>
            </a>

            <a href="<?= $TELEGRAM_URL ?>" target="_blank" rel="noopener" class="glass-card block p-3.5 rounded-2xl group border-l-2 border-l-emerald-500">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-sm font-bold text-white group-hover:text-emerald-300 transition">Remote Tech Support Specialist</div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Global SaaS • Flexible Hours</p>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-black text-emerald-400 font-display">$2,500/mo</div>
                        <span class="text-[10px] text-slate-500">Apply Free</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Sponsor & Collaboration Card -->
        <div class="glass-card rounded-2xl p-4 text-center border-dashed border-slate-700">
            <p class="text-xs text-slate-300 font-medium mb-1.5">Want to promote your app, product, or channel?</p>
            <a href="<?= $ADMIN_CONTACT ?>" target="_blank" rel="noopener"
               class="inline-flex items-center gap-1.5 text-xs text-cyan-400 hover:text-cyan-300 font-bold transition">
                <span>💬</span> Contact Admin for Promo / Sponsorship
            </a>
        </div>
    </main>

    <footer class="w-full text-center text-[11px] text-slate-500 py-6 border-t border-slate-900/80 mt-8 relative z-10 font-medium">
        © <?= date('Y') ?> Teleraptor • Automated Media Funnel • Ethiopian Creator Network
    </footer>
</body>
</html>
