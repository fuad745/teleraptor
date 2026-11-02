<?php
// ========================================================
// TELERAPTOR VIRAL SOCIAL MEDIA & TELEGRAM FUNNEL STUDIO
// Subdomain: teleraptor.parakichner.com
// Zero Python • 100% PHP + HTML5 Canvas • Impeccable UI/UX
// ========================================================

if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

$BOT_TOKEN = defined('BOT_TOKEN') ? BOT_TOKEN : "8794122196:AAH2LqFAMhKGJVHTYsFybp1OZtP50dineUE";
$CHANNEL_ID = defined('CHANNEL_ID') ? CHANNEL_ID : "@teleraptor_ig";
$ADMIN_PIN = defined('ADMIN_PIN') ? ADMIN_PIN : "7610";
$DEPLOY_SECRET = defined('DEPLOY_SECRET') ? DEPLOY_SECRET : "teleraptor_deploy_7610";

session_start();

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Handle Login
$auth_error = "";
if (isset($_POST['pin_login'])) {
    if ($_POST['pin'] === $ADMIN_PIN) {
        $_SESSION['authenticated'] = true;
    } else {
        $auth_error = "Invalid PIN. Please check and try again.";
    }
}

// Check Authentication
$is_logged_in = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;

// Handle Telegram Publish Action
$publish_status = "";
if ($is_logged_in && isset($_POST['action']) && $_POST['action'] === 'publish_telegram') {
    $title = htmlspecialchars($_POST['app_name'] ?? 'Spotube');
    $category = htmlspecialchars($_POST['category'] ?? 'Android Gem');
    $rating = htmlspecialchars($_POST['rating'] ?? '4.9 ★★★★★');
    $f1 = htmlspecialchars($_POST['feature1'] ?? '');
    $f2 = htmlspecialchars($_POST['feature2'] ?? '');
    $f3 = htmlspecialchars($_POST['feature3'] ?? '');
    $app_url = $_POST['download_url'] ?? "https://t.me/" . ltrim($CHANNEL_ID, '@');
    $image_base64 = $_POST['image_data'] ?? '';

    $caption = "🔥 <b>HIDDEN ANDROID GEM: {$title}</b>\n\n";
    $caption .= "<b>Category:</b> {$category}\n";
    $caption .= "<b>Rating:</b> {$rating}\n\n";
    $caption .= "✨ <b>Highlights:</b>\n";
    if ($f1) $caption .= "• {$f1}\n";
    if ($f2) $caption .= "• {$f2}\n";
    if ($f3) $caption .= "• {$f3}\n\n";
    $caption .= "👇 <i>Tap below to access full release and guide:</i>";

    $keyboard = [
        "inline_keyboard" => [
            [["text" => "📲 Download APK / Access", "url" => $app_url]],
            [
                ["text" => "💬 Share Channel", "url" => "https://t.me/share/url?url=https://t.me/" . ltrim($CHANNEL_ID, '@') . "&text=Check%20out%20this%20channel!"]
            ]
        ]
    ];

    if (!empty($image_base64)) {
        $img_data = explode(',', $image_base64);
        $decoded = base64_decode(end($img_data));
        $tmp_file = sys_get_temp_dir() . '/teleraptor_' . time() . '.png';
        file_put_contents($tmp_file, $decoded);

        $post_fields = [
            'chat_id' => $CHANNEL_ID,
            'caption' => $caption,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode($keyboard),
            'photo' => new CURLFile($tmp_file, 'image/png', 'card.png')
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot{$BOT_TOKEN}/sendPhoto");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $res = curl_exec($ch);
        curl_close($ch);
        @unlink($tmp_file);

        $json_res = json_decode($res, true);
        if ($json_res && $json_res['ok']) {
            $msg_id = $json_res['result']['message_id'] ?? '';
            $publish_status = "success:Published successfully to {$CHANNEL_ID}! (Message ID: #{$msg_id})";
        } else {
            $desc = $json_res['description'] ?? 'Network or API error';
            $publish_status = "error:Publish failed: {$desc}";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Teleraptor Funnel Studio Pro</title>
    <meta name="description" content="Viral Social-to-Telegram Funnel Automation & Content Studio Pro">
    <meta name="theme-color" content="#07090e">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-display: 'Outfit', -apple-system, sans-serif;
            --font-body: 'Plus Jakarta Sans', -apple-system, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }
        body {
            background-color: #07090e;
            color: #f1f5f9;
            font-family: var(--font-body);
            -webkit-tap-highlight-color: transparent;
        }
        h1, h2, h3, .font-display { font-family: var(--font-display); }
        code, pre, .font-mono { font-family: var(--font-mono); }

        .glass-panel {
            background: rgba(14, 20, 34, 0.72);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.5);
        }
        .glass-panel-subtle {
            background: rgba(19, 27, 46, 0.55);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .tab-btn {
            color: #94a3b8;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .tab-btn.active {
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
        }

        .slide-tab-btn.active {
            background: #0284c7;
            color: #ffffff;
            border-color: #38bdf8;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.35);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.5);
            filter: brightness(1.08);
        }
        .btn-primary:active { transform: scale(0.98); }

        .btn-emerald {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-emerald:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
            filter: brightness(1.08);
        }
        .btn-emerald:active { transform: scale(0.98); }

        .btn-purple {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            box-shadow: 0 4px 16px rgba(168, 85, 247, 0.3);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-purple:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(168, 85, 247, 0.45);
            filter: brightness(1.08);
        }
        .btn-purple:active { transform: scale(0.98); }

        .chip-btn {
            background: rgba(17, 24, 39, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.2s ease;
        }
        .chip-btn:hover {
            background: rgba(14, 165, 233, 0.15);
            border-color: rgba(56, 189, 248, 0.4);
            transform: translateY(-1px);
        }

        /* Toast Container */
        #toastContainer {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }
        .toast-msg {
            pointer-events: auto;
            min-width: 280px;
            max-width: 380px;
            padding: 12px 18px;
            border-radius: 16px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            color: #f8fafc;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            transition: all 0.3s ease;
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Canvas Frame Aspect Ratio */
        .canvas-frame {
            aspect-ratio: 9 / 16;
            background: #020408;
            box-shadow: 0 12px 30px -8px rgba(0, 0, 0, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .canvas-frame:hover {
            border-color: rgba(56, 189, 248, 0.4);
            box-shadow: 0 16px 40px -8px rgba(14, 165, 233, 0.25);
        }
    </style>
</head>
<body class="p-3 md:p-8 max-w-7xl mx-auto min-h-screen flex flex-col justify-between">

    <!-- Toast Notification Anchor -->
    <div id="toastContainer"></div>

<?php if (!$is_logged_in): ?>
    <!-- ================= LOGIN SCREEN (IMPECCABLE) ================= -->
    <div class="max-w-md mx-auto my-auto mt-20 glass-panel rounded-3xl p-8 md:p-10 shadow-2xl border border-cyan-500/20 text-center relative overflow-hidden">
        <div class="absolute -top-16 -right-16 w-40 h-40 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="inline-flex p-4 bg-gradient-to-tr from-cyan-500/20 to-blue-600/20 rounded-2xl mb-5 border border-cyan-500/30 shadow-inner">
            <span class="text-4xl">👑</span>
        </div>
        
        <h1 class="text-3xl font-black text-white tracking-tight font-display">Teleraptor Studio</h1>
        <p class="text-slate-400 text-xs mt-1 mb-6 font-medium">Automated Viral Funnel & Monetization Hub</p>
        
        <?php if ($auth_error): ?>
            <div class="bg-red-950/80 border border-red-500/80 text-red-200 px-4 py-3 rounded-2xl text-xs mb-5 flex items-center gap-2">
                <span>⚠️</span>
                <span><?= $auth_error ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div class="text-left">
                <label class="block text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1.5 font-display">
                    Admin Security PIN
                </label>
                <input type="password" name="pin" placeholder="••••" autofocus required maxlength="8"
                       class="w-full bg-slate-900/90 border border-slate-700 rounded-2xl px-4 py-3.5 text-white text-center tracking-[0.6em] text-2xl font-bold focus:outline-none focus:border-cyan-400 transition shadow-inner">
            </div>

            <button type="submit" name="pin_login"
                    class="w-full btn-primary text-white font-bold py-3.5 px-4 rounded-2xl text-sm flex items-center justify-center gap-2">
                <span>⚡</span>
                <span>Unlock Studio Pro</span>
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-800/80 text-[11px] text-slate-500 flex justify-between items-center">
            <span>DirectAdmin LiteSpeed</span>
            <span class="font-mono text-cyan-400/80">PIN: 7610</span>
        </div>
    </div>

<?php else: ?>
    <!-- ================= LOGGED IN STUDIO DASHBOARD ================= -->
    <div>
        <!-- Top Navbar with CI/CD & Quick Links -->
        <header class="flex flex-wrap justify-between items-center gap-4 mb-6 glass-panel rounded-2xl px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-xl shadow-md shadow-cyan-500/30">
                    ⚡
                </div>
                <div>
                    <h1 class="text-lg font-black text-white tracking-wide font-display flex items-center gap-2">
                        Teleraptor Studio Pro
                        <span class="text-[10px] bg-cyan-950 text-cyan-300 font-mono px-2 py-0.5 rounded-full border border-cyan-800/60">v2.4 CI/CD</span>
                    </h1>
                    <p class="text-xs text-slate-400">Broadcasting to: 
                        <a href="https://t.me/<?= ltrim($CHANNEL_ID, '@') ?>" target="_blank" class="text-cyan-400 font-bold hover:underline"><?= $CHANNEL_ID ?></a>
                    </p>
                </div>
            </div>

            <!-- Tab Switcher -->
            <div class="flex flex-wrap bg-slate-950/80 p-1 rounded-xl border border-slate-800/80 text-xs font-semibold gap-1">
                <button onclick="switchTab('studio')" id="tab_studio" class="tab-btn active px-3.5 py-2 rounded-lg">🚀 Studio</button>
                <a href="bio.php" target="_blank" class="text-slate-400 hover:text-white px-3.5 py-2 rounded-lg transition flex items-center gap-1.5">
                    <span>🔗</span> Bio Page
                </a>
                <button onclick="switchTab('monetize')" id="tab_monetize" class="tab-btn px-3.5 py-2 rounded-lg">💰 Monetization</button>
                <button onclick="switchTab('blueprint')" id="tab_blueprint" class="tab-btn px-3.5 py-2 rounded-lg">📋 Routine</button>
            </div>

            <!-- Actions: CI/CD Pull & Logout -->
            <div class="flex items-center gap-2">
                <button onclick="openVideoModal()" id="watchVideoBtn"
                        class="text-xs bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-500 hover:to-pink-500 text-white font-bold px-3 py-2 rounded-xl transition flex items-center gap-1.5 shadow-md shadow-rose-600/30"
                        title="Watch generated 9:16 vertical short video sample">
                    <span>🎬</span>
                    <span>Watch 9:16 Short</span>
                </button>
                <button onclick="triggerGitDeploy()" id="deployBtn"
                        class="text-xs bg-slate-900 hover:bg-slate-800 border border-cyan-500/30 text-cyan-300 font-semibold px-3 py-2 rounded-xl transition flex items-center gap-1.5"
                        title="Pull latest changes from GitHub repository">
                    <span>🔄</span>
                    <span class="hidden sm:inline">Git Pull CI/CD</span>
                </button>
                <a href="?logout=1" class="text-xs bg-red-950/40 hover:bg-red-900/60 border border-red-800/50 text-red-300 px-3 py-2 rounded-xl transition">
                    Logout
                </a>
            </div>
        </header>

        <!-- Status Banner -->
        <?php if ($publish_status): ?>
            <?php list($type, $msg) = explode(':', $publish_status, 2); ?>
            <div class="<?= $type === 'success' ? 'bg-emerald-950/80 border-emerald-500 text-emerald-200' : 'bg-red-950/80 border-red-500 text-red-200' ?> border px-5 py-3 rounded-2xl mb-6 flex items-center gap-3 shadow-lg">
                <span class="text-xl"><?= $type === 'success' ? '✅' : '❌' ?></span>
                <span class="font-semibold text-sm"><?= $msg ?></span>
            </div>
        <?php endif; ?>

        <!-- ================= TAB 1: STUDIO ================= -->
        <div id="section_studio" class="space-y-6">
            
            <!-- Quick Viral Presets & Language Bar -->
            <div class="glass-panel p-4 rounded-2xl space-y-3">
                <div class="flex flex-wrap justify-between items-center gap-2 border-b border-slate-800 pb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 font-display flex items-center gap-1.5">
                            <span>⚡</span> 1-Tap Viral Presets:
                        </span>
                        <span class="text-[10px] text-slate-500 hidden md:inline">Instant Auto-Fill & Live Canvas Render</span>
                    </div>

                    <!-- Dual Language Mode Toggle -->
                    <div class="flex items-center gap-1 bg-slate-950 p-1 rounded-xl border border-slate-800 text-xs">
                        <button onclick="setLang('en')" id="lang_en" class="px-3 py-1 rounded-lg font-bold transition bg-emerald-600 text-white">🇺🇸 English</button>
                        <button onclick="setLang('am')" id="lang_am" class="px-3 py-1 rounded-lg font-bold text-slate-400 hover:text-white transition">🇪🇹 አማርኛ</button>
                    </div>
                </div>

                <!-- Presets Container -->
                <div class="flex flex-wrap gap-2" id="presetsContainer">
                    <!-- Dynamic preset buttons injected by JS -->
                </div>
            </div>

            <!-- Studio Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: Form Controls (5 cols) -->
                <div class="lg:col-span-5 glass-panel rounded-2xl p-5 md:p-6 shadow-xl space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                        <h2 class="text-sm font-bold text-white flex items-center gap-2 font-display">
                            <span>✏️</span> Content & Card Configuration
                        </h2>
                        <span class="text-[10px] text-slate-500 font-mono">Live Sync</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Slide 1 Viral Hook (TikTok / Reels)</label>
                        <input type="text" id="hook_text" value="STOP PAYING FOR SPOTIFY PREMIUM 🛑" oninput="renderAllSlides()"
                               class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3.5 py-2 text-sm text-yellow-300 font-bold focus:border-cyan-400 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Badge Tag</label>
                        <input type="text" id="badge_text" value="🔥 HIDDEN ANDROID GEM" oninput="renderAllSlides()"
                               class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3.5 py-2 text-sm text-white focus:border-cyan-400 focus:outline-none transition">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">App / Role Name</label>
                            <input type="text" id="app_name" value="Spotube" oninput="renderAllSlides()"
                                   class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3.5 py-2 text-sm text-white font-bold focus:border-cyan-400 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Rating / Salary</label>
                            <input type="text" id="rating" value="4.9 ★★★★★" oninput="renderAllSlides()"
                                   class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3.5 py-2 text-sm text-amber-400 font-semibold focus:border-cyan-400 focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Category / Company</label>
                        <input type="text" id="category" value="Open-Source Audio & Music" oninput="renderAllSlides()"
                               class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3.5 py-2 text-sm text-white focus:border-cyan-400 focus:outline-none transition">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-400">3 Key Features (Bullet Points)</label>
                        <input type="text" id="f1" value="Zero Ads, No Account Required" oninput="renderAllSlides()"
                               class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:border-cyan-400 focus:outline-none transition" placeholder="Feature 1">
                        <input type="text" id="f2" value="Free High-Bitrate 320kbps Offline Downloads" oninput="renderAllSlides()"
                               class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:border-cyan-400 focus:outline-none transition" placeholder="Feature 2">
                        <input type="text" id="f3" value="100% Open-Source & Battery Friendly" oninput="renderAllSlides()"
                               class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-white focus:border-cyan-400 focus:outline-none transition" placeholder="Feature 3">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1 flex justify-between">
                            <span>APK / Destination URL</span>
                            <span class="text-[10px] text-cyan-400">Direct or Shortened</span>
                        </label>
                        <input type="text" id="download_url" value="https://github.com/KRTirtho/spotube"
                               class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-cyan-300 font-mono focus:border-cyan-400 focus:outline-none transition">
                        
                        <!-- Shortener Bar -->
                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                            <span class="text-[11px] text-slate-400 font-medium">💰 1-Tap USDT Monetize:</span>
                            <button type="button" onclick="wrapShortener('shrinkme')" class="text-[10px] bg-emerald-950 hover:bg-emerald-900 border border-emerald-700 text-emerald-300 font-semibold px-2 py-0.5 rounded-lg transition">ShrinkMe (USDT)</button>
                            <button type="button" onclick="wrapShortener('exe')" class="text-[10px] bg-emerald-950 hover:bg-emerald-900 border border-emerald-700 text-emerald-300 font-semibold px-2 py-0.5 rounded-lg transition">Exe.io (Crypto)</button>
                            <button type="button" onclick="wrapShortener('channel')" class="text-[10px] bg-cyan-950 hover:bg-cyan-900 border border-cyan-700 text-cyan-300 font-semibold px-2 py-0.5 rounded-lg transition">Channel Bio Link</button>
                        </div>
                    </div>

                    <!-- Action Execution Buttons -->
                    <div class="pt-3 space-y-2.5">
                        <form method="POST" id="publishForm" onsubmit="preparePublish()">
                            <input type="hidden" name="action" value="publish_telegram">
                            <input type="hidden" name="app_name" id="post_app_name">
                            <input type="hidden" name="category" id="post_category">
                            <input type="hidden" name="rating" id="post_rating">
                            <input type="hidden" name="feature1" id="post_f1">
                            <input type="hidden" name="feature2" id="post_f2">
                            <input type="hidden" name="feature3" id="post_f3">
                            <input type="hidden" name="download_url" id="post_url">
                            <input type="hidden" name="image_data" id="post_image_data">

                            <button type="submit" id="publishSubmitBtn"
                                    class="w-full btn-primary text-white font-extrabold py-3.5 rounded-xl transition text-sm flex items-center justify-center gap-2">
                                <span>🚀</span> Publish Post to <?= $CHANNEL_ID ?>
                            </button>
                        </form>

                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="downloadAllSlides()"
                                    class="btn-emerald text-white font-bold py-2.5 px-3 rounded-xl transition text-xs flex items-center justify-center gap-1.5">
                                <span>📥</span> Download 3 Slides
                            </button>
                            <button onclick="copyTikTokCaption()" id="copyBtn"
                                    class="bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-semibold py-2.5 px-3 rounded-xl transition text-xs flex items-center justify-center gap-1.5">
                                <span>📋</span> Copy Caption
                            </button>
                        </div>

                        <!-- 8-Second Video Generator Button -->
                        <button onclick="generateVideo()" id="videoBtn"
                                class="w-full btn-purple text-white font-bold py-3 rounded-xl transition text-xs flex items-center justify-center gap-2">
                            <span>🎬</span> Export 8s Looped Video (TikTok / Reels)
                        </button>
                    </div>
                </div>

                <!-- Right Column: 3-Slide Interactive Carousel & Previews (7 cols) -->
                <div class="lg:col-span-7 glass-panel rounded-2xl p-5 md:p-6 shadow-xl flex flex-col items-center">
                    
                    <div class="w-full flex flex-wrap justify-between items-center mb-4 border-b border-slate-800 pb-3 gap-2">
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold text-white flex items-center gap-2 font-display">
                                <span>📱</span> 3-Slide Carousel Pack Preview
                            </h2>
                            <span class="text-[10px] bg-cyan-950 text-cyan-300 border border-cyan-800/60 px-2 py-0.5 rounded-full font-mono">1080×1920</span>
                        </div>

                        <!-- Mobile Slide Switcher Tabs -->
                        <div class="flex items-center gap-1 bg-slate-950 p-1 rounded-xl border border-slate-800 text-[11px] font-bold">
                            <button onclick="setSlideView('all')" id="btnViewAll" class="slide-tab-btn active px-2.5 py-1 rounded-lg">All (3)</button>
                            <button onclick="setSlideView('1')" id="btnView1" class="slide-tab-btn px-2.5 py-1 rounded-lg">1: Hook</button>
                            <button onclick="setSlideView('2')" id="btnView2" class="slide-tab-btn px-2.5 py-1 rounded-lg">2: Card</button>
                            <button onclick="setSlideView('3')" id="btnView3" class="slide-tab-btn px-2.5 py-1 rounded-lg">3: Funnel</button>
                        </div>
                    </div>

                    <!-- Slide Previews Container -->
                    <div id="slidesGrid" class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        
                        <!-- Slide 1: Hook -->
                        <div id="slideWrap1" class="flex flex-col items-center">
                            <div class="w-full flex justify-between items-center mb-1.5 px-1">
                                <span class="text-xs font-bold text-yellow-400">Slide 1: Hook</span>
                                <span class="text-[10px] text-slate-500">TikTok Attention</span>
                            </div>
                            <div class="canvas-frame rounded-2xl overflow-hidden w-full relative group cursor-pointer" onclick="openLightbox('canvasHook', 'Slide 1: Viral Hook')">
                                <canvas id="canvasHook" width="1080" height="1920" class="w-full h-full"></canvas>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <span class="text-xs bg-black/80 text-white px-3 py-1.5 rounded-xl border border-white/20 font-semibold">🔍 View Fullscreen</span>
                                </div>
                                <button onclick="event.stopPropagation(); downloadCanvas('canvasHook', 'slide1_hook.png')" class="absolute bottom-2 right-2 bg-black/80 hover:bg-cyan-600 text-white text-[10px] px-2.5 py-1 rounded-lg backdrop-blur border border-white/20 transition font-semibold">⬇ Save</button>
                            </div>
                        </div>

                        <!-- Slide 2: Feature Card -->
                        <div id="slideWrap2" class="flex flex-col items-center">
                            <div class="w-full flex justify-between items-center mb-1.5 px-1">
                                <span class="text-xs font-bold text-cyan-400">Slide 2: Value</span>
                                <span class="text-[10px] text-slate-500">Feature Card</span>
                            </div>
                            <div class="canvas-frame rounded-2xl overflow-hidden w-full relative group cursor-pointer ring-2 ring-cyan-500/30" onclick="openLightbox('canvasCard', 'Slide 2: Value Card')">
                                <canvas id="canvasCard" width="1080" height="1920" class="w-full h-full"></canvas>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <span class="text-xs bg-black/80 text-white px-3 py-1.5 rounded-xl border border-white/20 font-semibold">🔍 View Fullscreen</span>
                                </div>
                                <button onclick="event.stopPropagation(); downloadCanvas('canvasCard', 'slide2_value.png')" class="absolute bottom-2 right-2 bg-black/80 hover:bg-cyan-600 text-white text-[10px] px-2.5 py-1 rounded-lg backdrop-blur border border-white/20 transition font-semibold">⬇ Save</button>
                            </div>
                        </div>

                        <!-- Slide 3: Funnel CTA -->
                        <div id="slideWrap3" class="flex flex-col items-center">
                            <div class="w-full flex justify-between items-center mb-1.5 px-1">
                                <span class="text-xs font-bold text-emerald-400">Slide 3: Funnel</span>
                                <span class="text-[10px] text-slate-500">Telegram CTA</span>
                            </div>
                            <div class="canvas-frame rounded-2xl overflow-hidden w-full relative group cursor-pointer" onclick="openLightbox('canvasCTA', 'Slide 3: Telegram Funnel')">
                                <canvas id="canvasCTA" width="1080" height="1920" class="w-full h-full"></canvas>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <span class="text-xs bg-black/80 text-white px-3 py-1.5 rounded-xl border border-white/20 font-semibold">🔍 View Fullscreen</span>
                                </div>
                                <button onclick="event.stopPropagation(); downloadCanvas('canvasCTA', 'slide3_cta.png')" class="absolute bottom-2 right-2 bg-black/80 hover:bg-cyan-600 text-white text-[10px] px-2.5 py-1 rounded-lg backdrop-blur border border-white/20 transition font-semibold">⬇ Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- Live Caption Preview -->
                    <div class="w-full mt-6 bg-slate-950/80 border border-slate-800 rounded-2xl p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider font-display">Ready-to-Post Caption (TikTok / Reels):</span>
                            <button onclick="copyTikTokCaption()" class="text-xs text-cyan-400 font-semibold hover:underline">Copy All</button>
                        </div>
                        <p id="captionPreviewText" class="text-xs text-slate-300 font-mono whitespace-pre-line leading-relaxed"></p>
                    </div>

                </div>

            </div>
        </div>

        <!-- ================= TAB 2: ETHIOPIA MONETIZATION BLUEPRINT ================= -->
        <div id="section_monetize" class="hidden space-y-6">
            <div class="glass-panel rounded-2xl p-6 md:p-8 shadow-xl">
                <div class="flex items-center gap-3 border-b border-slate-800 pb-4 mb-6">
                    <span class="text-3xl">💰</span>
                    <div>
                        <h2 class="text-xl font-bold text-white font-display">Monetization Engine: Earn $500 – $2,000/Month from Ethiopia</h2>
                        <p class="text-xs text-slate-400">100% Free Tools • Zero Face • Zero Voice • Cashout in USDT TRC20 / Payoneer / Telebirr</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="glass-panel-subtle rounded-2xl p-5 hover:border-cyan-500/40 transition">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-bold text-white text-sm flex items-center gap-2">
                                <span class="text-xl">📥</span> 1. Pay-Per-Download (PPA)
                            </h3>
                            <span class="text-[10px] bg-emerald-950 text-emerald-400 border border-emerald-800 px-2 py-0.5 rounded-full font-semibold">USDT TRC20</span>
                        </div>
                        <p class="text-xs text-slate-400 mb-3">
                            Upload recommended open-source APKs or PDF guides to free file-hosting platforms that pay for every single download.
                        </p>
                        <ul class="text-xs text-slate-300 space-y-1.5 mb-4 font-medium">
                            <li>• <b>Up-4ever.net</b>: Pays $10 to $40 per 1,000 downloads. Minimum cashout: $1 in USDT TRC20 or Binance.</li>
                            <li>• <b>DropGalaxy.com</b>: Global PPA with instant crypto cashouts.</li>
                            <li>• <i>Funnel action:</i> Put your PPA download link inside the Telegram post button!</li>
                        </ul>
                    </div>

                    <div class="glass-panel-subtle rounded-2xl p-5 hover:border-cyan-500/40 transition">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-bold text-white text-sm flex items-center gap-2">
                                <span class="text-xl">🔗</span> 2. Crypto URL Shorteners
                            </h3>
                            <span class="text-[10px] bg-emerald-950 text-emerald-400 border border-emerald-800 px-2 py-0.5 rounded-full font-semibold">USDT / Payoneer</span>
                        </div>
                        <p class="text-xs text-slate-400 mb-3">
                            Run GitHub or job application links through high-paying shorteners. When visitors click to get the link, you earn revenue per click.
                        </p>
                        <ul class="text-xs text-slate-300 space-y-1.5 mb-4 font-medium">
                            <li>• <b>ShrinkMe.io</b>: Up to $22 / 1,000 clicks. Payouts via USDT, Airtm, Payoneer.</li>
                            <li>• <b>Exe.io</b>: Pays $5–$15 / 1,000 clicks directly to Binance or Bitcoin.</li>
                            <li>• <i>Funnel action:</i> Shorten the download button links posted on your Telegram channel.</li>
                        </ul>
                    </div>

                    <div class="glass-panel-subtle rounded-2xl p-5 hover:border-cyan-500/40 transition">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-bold text-white text-sm flex items-center gap-2">
                                <span class="text-xl">📢</span> 3. Sponsored Telegram Channel Posts
                            </h3>
                            <span class="text-[10px] bg-cyan-950 text-cyan-400 border border-cyan-800 px-2 py-0.5 rounded-full font-semibold">Telebirr / USDT</span>
                        </div>
                        <p class="text-xs text-slate-400 mb-3">
                            Once <b><?= $CHANNEL_ID ?></b> reaches 1,000+ active subscribers, Ethiopian tech startups, betting apps, and crypto projects will pay to broadcast ads.
                        </p>
                        <ul class="text-xs text-slate-300 space-y-1.5 mb-4 font-medium">
                            <li>• <b>Telega.io</b>: The largest global Telegram ad marketplace. Earn $15 to $100 per sponsored post.</li>
                            <li>• <b>Local Ethiopian Advertisers</b>: Direct bank transfer / Telebirr payments for 24hr pinned posts.</li>
                            <li>• <i>Funnel action:</i> Put "For promo/ads: <?= defined('ADMIN_USERNAME') ? ADMIN_USERNAME : '@Fuad761' ?>" in channel bio!</li>
                        </ul>
                    </div>

                    <div class="glass-panel-subtle rounded-2xl p-5 hover:border-cyan-500/40 transition">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-bold text-white text-sm flex items-center gap-2">
                                <span class="text-xl">💼</span> 4. High-Paying Tech & VPN Affiliates
                            </h3>
                            <span class="text-[10px] bg-purple-950 text-purple-400 border border-purple-800 px-2 py-0.5 rounded-full font-semibold">$20 - $50 / sale</span>
                        </div>
                        <p class="text-xs text-slate-400 mb-3">
                            Recommend tools that tech & Android users love: VPNs, hosting, and freelance services.
                        </p>
                        <ul class="text-xs text-slate-300 space-y-1.5 mb-4 font-medium">
                            <li>• <b>Surfshark / NordVPN Affiliate</b>: Pays 40% commission per referral.</li>
                            <li>• <b>Hostinger Affiliate</b>: Pays up to $60 per signup.</li>
                            <li>• <i>Funnel action:</i> Post security tips with your affiliate link as the recommended solution!</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= TAB 3: VIRAL BLUEPRINT ================= -->
        <div id="section_blueprint" class="hidden space-y-6">
            <div class="glass-panel rounded-2xl p-6 md:p-8 shadow-xl">
                <div class="flex items-center gap-3 border-b border-slate-800 pb-4 mb-6">
                    <span class="text-3xl">📋</span>
                    <div>
                        <h2 class="text-xl font-bold text-white font-display">Daily 15-Minute Posting Routine</h2>
                        <p class="text-xs text-slate-400">Algorithm growth strategy for TikTok, Instagram Reels & Facebook</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="glass-panel-subtle rounded-2xl p-5">
                        <span class="text-cyan-400 text-xs font-bold uppercase tracking-wider block mb-2 font-display">Step 1 (2 Minutes)</span>
                        <h3 class="font-bold text-white text-sm mb-2 font-display">Generate in Teleraptor Studio</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            1. Select an app preset (Spotube, Seal, LocalSend) or enter a new one.<br>
                            2. Tap <b>"🚀 Publish to Channel"</b>.<br>
                            3. Tap <b>"📥 Download 3 Slides"</b> (or Export 8s Video).<br>
                            4. Tap <b>"📋 Copy Caption"</b>.
                        </p>
                    </div>

                    <div class="glass-panel-subtle rounded-2xl p-5">
                        <span class="text-cyan-400 text-xs font-bold uppercase tracking-wider block mb-2 font-display">Step 2 (5 Minutes)</span>
                        <h3 class="font-bold text-white text-sm mb-2 font-display">Upload Photo Carousel / Video</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            1. Open TikTok, select <b>Photo Mode</b> (or Video Mode).<br>
                            2. Choose Slide 1 (Hook), Slide 2 (Card), Slide 3 (CTA).<br>
                            3. Add a trending sound (search "viral tech audio").<br>
                            4. Paste the caption & publish.
                        </p>
                    </div>

                    <div class="glass-panel-subtle rounded-2xl p-5">
                        <span class="text-cyan-400 text-xs font-bold uppercase tracking-wider block mb-2 font-display">Step 3 (3 Minutes)</span>
                        <h3 class="font-bold text-white text-sm mb-2 font-display">Cross-Post & Funnel</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            1. Post the exact same 3 photos as an Instagram Carousel and Facebook Reel.<br>
                            2. Pin the top comment: <i>"Download link and full tutorial is pinned in my Telegram <?= $CHANNEL_ID ?> (Link in bio)"</i>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Lightbox Zoom Modal -->
    <div id="lightboxModal" class="fixed inset-0 bg-black/90 backdrop-blur-md z-50 hidden flex flex-col items-center justify-center p-4" onclick="closeLightbox()">
        <div class="relative max-w-sm w-full" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-3">
                <span id="lightboxTitle" class="text-sm font-bold text-white font-display">Slide Preview</span>
                <button onclick="closeLightbox()" class="text-slate-400 hover:text-white text-xl font-bold px-2">✕</button>
            </div>
            <img id="lightboxImg" class="w-full rounded-2xl border border-slate-700 shadow-2xl" src="" alt="Slide Preview">
            <div class="mt-4 flex justify-between gap-3">
                <button id="lightboxDownloadBtn" class="w-full btn-emerald text-white text-xs font-bold py-2.5 rounded-xl">
                    📥 Download High-Res PNG
                </button>
            </div>
        </div>
    </div>

    <!-- CI/CD Deployment Modal -->
    <div id="deployModal" class="fixed inset-0 bg-black/90 backdrop-blur-md z-50 hidden flex flex-col items-center justify-center p-4">
        <div class="glass-panel max-w-lg w-full rounded-3xl p-6 shadow-2xl border border-cyan-500/30">
            <div class="flex justify-between items-center mb-4 border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🔄</span>
                    <span class="text-sm font-bold text-white font-display">CI/CD Git Pull Execution</span>
                </div>
                <button onclick="closeDeployModal()" class="text-slate-400 hover:text-white font-bold">✕</button>
            </div>
            <div id="deployModalContent" class="space-y-3 font-mono text-xs text-slate-300 max-h-80 overflow-y-auto bg-slate-950 p-4 rounded-xl border border-slate-900">
                <div class="flex items-center gap-2 text-cyan-400">
                    <span class="animate-spin">⏳</span>
                    <span>Connecting to git origin & executing pull...</span>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button onclick="closeDeployModal()" class="text-xs bg-slate-800 hover:bg-slate-700 text-white font-semibold px-4 py-2 rounded-xl transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- 9:16 Video Short Player Modal -->
    <div id="videoModal" class="fixed inset-0 bg-black/90 backdrop-blur-md z-50 hidden flex flex-col items-center justify-center p-4">
        <div class="glass-panel max-w-sm w-full rounded-3xl p-5 shadow-2xl border border-rose-500/30 flex flex-col items-center">
            <div class="w-full flex justify-between items-center mb-3 border-b border-slate-800 pb-2">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🎬</span>
                    <span class="text-sm font-bold text-white font-display">Video Style Comparison</span>
                </div>
                <button onclick="closeVideoModal()" class="text-slate-400 hover:text-white font-bold p-1">✕</button>
            </div>

            <!-- Style Selector Tabs -->
            <div class="flex bg-slate-950/90 p-1 rounded-xl border border-slate-800/80 text-[11px] font-semibold gap-1 w-full mb-3">
                <button onclick="switchVideoStyle('infographic')" id="styleBtn_info" class="flex-1 py-1.5 rounded-lg bg-cyan-600 text-white shadow font-bold transition">
                    ✨ Style A: Infographic
                </button>
                <button onclick="switchVideoStyle('mpt')" id="styleBtn_mpt" class="flex-1 py-1.5 rounded-lg text-slate-400 hover:text-white font-medium transition">
                    🎬 Style B: MoneyPrinterTurbo
                </button>
            </div>
            
            <div class="w-full aspect-[9/16] max-h-[60vh] bg-black rounded-2xl overflow-hidden border border-slate-700/80 shadow-inner flex items-center justify-center">
                <video id="viralVideoPlayer" src="sample_short.mp4" controls playsinline preload="auto" class="w-full h-full object-contain"></video>
            </div>

            <div class="w-full mt-4 flex items-center gap-2">
                <a id="videoDownloadLink" href="sample_short.mp4" download="teleraptor_style_a_infographic.mp4" class="flex-1 btn-emerald text-white text-xs font-bold py-2.5 rounded-xl flex items-center justify-center gap-1.5 shadow-md shadow-emerald-600/30">
                    <span>📥</span> Download MP4
                </a>
                <button onclick="copyVideoUrl()" class="chip-btn text-xs text-slate-300 font-semibold px-3 py-2.5 rounded-xl flex items-center gap-1">
                    <span>📋</span> Copy Link
                </button>
            </div>
        </div>
    </div>

    <!-- Sticky Mobile Quick-Action Bar -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 p-3 bg-slate-950/90 backdrop-blur-xl border-t border-slate-800/90 z-40 flex items-center justify-between gap-2">
        <button onclick="document.getElementById('publishSubmitBtn').click()" class="flex-1 btn-primary text-white text-xs font-bold py-2.5 rounded-xl flex items-center justify-center gap-1 shadow-md">
            <span>🚀</span> Post
        </button>
        <button onclick="downloadAllSlides()" class="flex-1 btn-emerald text-white text-xs font-bold py-2.5 rounded-xl flex items-center justify-center gap-1 shadow-md">
            <span>📥</span> 3 Slides
        </button>
        <button onclick="copyTikTokCaption()" class="flex-1 bg-slate-800 text-white text-xs font-semibold py-2.5 rounded-xl flex items-center justify-center gap-1 border border-slate-700">
            <span>📋</span> Copy
        </button>
    </div>

    <!-- Script Engine -->
    <script>
        let currentLang = 'en';
        let currentActiveSlide = 'all';

        const presets = {
            en: {
                spotube: {
                    hook: "STOP PAYING FOR SPOTIFY PREMIUM 🛑",
                    badge: "🔥 HIDDEN ANDROID GEM",
                    name: "Spotube",
                    cat: "Open-Source Audio & Music",
                    rate: "4.9 ★★★★★",
                    f1: "No Ads, No Account Required",
                    f2: "Free High-Bitrate 320kbps Offline Downloads",
                    f3: "100% Open-Source & Battery Friendly",
                    url: "https://github.com/KRTirtho/spotube"
                },
                seal: {
                    hook: "DOWNLOAD ANY VIDEO FROM ANYWHERE 📥",
                    badge: "🔥 TOP MEDIA UTILITY",
                    name: "Seal",
                    cat: "Video & Audio Downloader",
                    rate: "4.8 ★★★★★",
                    f1: "Download from 1,000+ sites (yt-dlp)",
                    f2: "Extract 320kbps MP3 audio easily",
                    f3: "Material You UI, completely ad-free",
                    url: "https://github.com/JunkFood02/Seal"
                },
                localsend: {
                    hook: "AIRDROP FOR ANDROID AND WINDOWS 📡",
                    badge: "🔥 PRODUCTIVITY GEM",
                    name: "LocalSend",
                    cat: "Cross-Platform Sharing",
                    rate: "4.9 ★★★★★",
                    f1: "AirDrop for Android, PC & Mac",
                    f2: "Zero internet required (Local Wi-Fi)",
                    f3: "No tracking, no compression, no limits",
                    url: "https://localsend.org"
                },
                immich: {
                    hook: "GOOGLE PHOTOS 15GB FULL? DO THIS 🖼️",
                    badge: "🔥 STORAGE LIFE SAVER",
                    name: "Immich",
                    cat: "Self-Hosted Photo Backup",
                    rate: "4.9 ★★★★★",
                    f1: "Unlimited high-res photo backup",
                    f2: "AI facial recognition and search",
                    f3: "100% private, your own cloud",
                    url: "https://immich.app"
                },
                netguard: {
                    hook: "BLOCK EVERY IN-APP AD ON ANDROID 🛡️",
                    badge: "🔥 SECURITY & PRIVACY",
                    name: "NetGuard",
                    cat: "No-Root Firewall & Adblock",
                    rate: "4.7 ★★★★★",
                    f1: "Blocks ads in games and apps",
                    f2: "Requires zero root permissions",
                    f3: "Saves up to 40% battery & mobile data",
                    url: "https://netguard.me"
                },
                job_flutter: {
                    hook: "$5,500/MO REMOTE MOBILE DEV ROLE 💼",
                    badge: "🌍 100% REMOTE GLOBAL JOB",
                    name: "Senior Flutter Dev",
                    cat: "Global Fintech Corporation",
                    rate: "$4,500 - $6,500 / month",
                    f1: "100% Remote (Work from Anywhere)",
                    f2: "Paid in USD / Crypto / Wire Transfer",
                    f3: "Flexible Hours + Equipment Stipend",
                    url: "https://t.me/<?= ltrim($CHANNEL_ID, '@') ?>"
                }
            },
            am: {
                spotube: {
                    hook: "ነፃ ሙዚቃ ያለምንም ማስታወቂያ ያውርዱ 🛑",
                    badge: "🔥 ድብቅ የአንድሮይድ አፕ",
                    name: "Spotube",
                    cat: "ነፃ የሙዚቃ ማጫወቻ እና ማውረጃ",
                    rate: "4.9 ★★★★★",
                    f1: "ያለምንም ማስታወቂያ እና አካውንት ይሰራል",
                    f2: "ከፍተኛ ጥራት ያላቸው ሙዚቃዎችን ያወርዳል",
                    f3: "100% ነፃ እና ባትሪ አይበላም",
                    url: "https://github.com/KRTirtho/spotube"
                },
                seal: {
                    hook: "ማንኛውንም ቪዲዮ ከየትኛውም ቦታ ያውርዱ 📥",
                    badge: "🔥 ምርጥ የቪዲዮ ማውረጃ",
                    name: "Seal",
                    cat: "የቪዲዮ እና MP3 ማውረጃ",
                    rate: "4.8 ★★★★★",
                    f1: "ከቲክቶክ፣ ዩቲዩብ እና ፌስቡክ ያወርዳል",
                    f2: "ቪዲዮውን ወደ MP3 ሙዚቃ ይቀይራል",
                    f3: "ሙሉ በሙሉ ያለምንም ማስታወቂያ",
                    url: "https://github.com/JunkFood02/Seal"
                },
                localsend: {
                    hook: "ፋይሎችን ያለ ኢንተርኔት በሰከንዶች ይላኩ 📡",
                    badge: "🔥 ምርጥ ኤርድሮፕ አፕ",
                    name: "LocalSend",
                    cat: "ፈጣን የፋይል ማስተላለፊያ",
                    rate: "4.9 ★★★★★",
                    f1: "ከስልክ ወደ ኮምፒውተር በፍጥነት ይልካል",
                    f2: "ምንም የሞባይል ዳታ አይጠይቅም (ዋይፋይ)",
                    f3: "ያለምንም የፋይል መጠን ገደብ",
                    url: "https://localsend.org"
                },
                job_remote: {
                    hook: "ከቤት ሆነው በዶላር የሚከፍል ስራ 💼",
                    badge: "🌍 ኦንላይን ሪሞት ስራ",
                    name: "Remote Support Specialist",
                    cat: "ዓለም አቀፍ የቴክኖሎጂ ኩባንያ",
                    rate: "$2,000 - $3,500 / ወር",
                    f1: "ከኢትዮጵያ ሆነው በኦንላይን የሚሰሩት",
                    f2: "ክፍያ በUSD / Crypto / ቴሌብር",
                    f3: "ምንም የኮዲንግ ዲግሪ አይጠይቅም",
                    url: "https://t.me/<?= ltrim($CHANNEL_ID, '@') ?>"
                }
            }
        };

        // Toast Notification System
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast-msg';
            
            const icon = type === 'success' ? '✅' : (type === 'error' ? '❌' : 'ℹ️');
            toast.innerHTML = `<span class="text-base">${icon}</span><span>${message}</span>`;
            
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function setLang(lang) {
            currentLang = lang;
            document.getElementById('lang_en').className = lang === 'en' ? 'px-3 py-1 rounded-lg font-bold transition bg-emerald-600 text-white' : 'px-3 py-1 rounded-lg font-bold text-slate-400 hover:text-white transition';
            document.getElementById('lang_am').className = lang === 'am' ? 'px-3 py-1 rounded-lg font-bold transition bg-emerald-600 text-white' : 'px-3 py-1 rounded-lg font-bold text-slate-400 hover:text-white transition';
            renderPresetButtons();
            loadPreset(lang === 'en' ? 'spotube' : 'spotube');
            showToast(`Switched language to ${lang === 'en' ? 'English' : 'አማርኛ'}`, 'info');
        }

        function renderPresetButtons() {
            const container = document.getElementById('presetsContainer');
            const data = presets[currentLang];
            let html = '';
            for (const key in data) {
                const item = data[key];
                const emoji = key.includes('job') ? '💼' : (key === 'spotube' ? '🎵' : (key === 'seal' ? '📥' : (key === 'localsend' ? '📡' : (key === 'immich' ? '🖼️' : '🛡️'))));
                html += `<button onclick="loadPreset('${key}')" class="chip-btn text-xs font-semibold px-3 py-1.5 rounded-xl text-slate-200 transition flex items-center gap-1.5"><span>${emoji}</span><span>${item.name}</span></button>`;
            }
            container.innerHTML = html;
        }

        function switchTab(tab) {
            document.getElementById('section_studio').classList.toggle('hidden', tab !== 'studio');
            document.getElementById('section_monetize').classList.toggle('hidden', tab !== 'monetize');
            document.getElementById('section_blueprint').classList.toggle('hidden', tab !== 'blueprint');

            document.getElementById('tab_studio').className = tab === 'studio' ? 'tab-btn active px-3.5 py-2 rounded-lg' : 'tab-btn px-3.5 py-2 rounded-lg';
            document.getElementById('tab_monetize').className = tab === 'monetize' ? 'tab-btn active px-3.5 py-2 rounded-lg' : 'tab-btn px-3.5 py-2 rounded-lg';
            document.getElementById('tab_blueprint').className = tab === 'blueprint' ? 'tab-btn active px-3.5 py-2 rounded-lg' : 'tab-btn px-3.5 py-2 rounded-lg';
        }

        function setSlideView(view) {
            currentActiveSlide = view;
            ['all', '1', '2', '3'].forEach(v => {
                const btn = document.getElementById('btnView' + (v === 'all' ? 'All' : v));
                if (btn) btn.className = v === view ? 'slide-tab-btn active px-2.5 py-1 rounded-lg' : 'slide-tab-btn px-2.5 py-1 rounded-lg text-slate-400 hover:text-white';
            });

            const grid = document.getElementById('slidesGrid');
            const w1 = document.getElementById('slideWrap1');
            const w2 = document.getElementById('slideWrap2');
            const w3 = document.getElementById('slideWrap3');

            if (view === 'all') {
                grid.className = 'grid grid-cols-1 md:grid-cols-3 gap-4 w-full';
                w1.classList.remove('hidden');
                w2.classList.remove('hidden');
                w3.classList.remove('hidden');
            } else {
                grid.className = 'grid grid-cols-1 max-w-sm mx-auto w-full';
                w1.classList.toggle('hidden', view !== '1');
                w2.classList.toggle('hidden', view !== '2');
                w3.classList.toggle('hidden', view !== '3');
            }
        }

        function loadPreset(key) {
            const p = presets[currentLang][key] || presets['en'][key];
            if (!p) return;
            document.getElementById('hook_text').value = p.hook;
            document.getElementById('badge_text').value = p.badge;
            document.getElementById('app_name').value = p.name;
            document.getElementById('category').value = p.cat;
            document.getElementById('rating').value = p.rate;
            document.getElementById('f1').value = p.f1;
            document.getElementById('f2').value = p.f2;
            document.getElementById('f3').value = p.f3;
            document.getElementById('download_url').value = p.url;
            renderAllSlides();
            showToast(`Loaded preset: ${p.name}`, 'success');
        }

        function roundRect(ctx, x, y, width, height, radius, fill, stroke) {
            ctx.beginPath();
            ctx.moveTo(x + radius, y);
            ctx.lineTo(x + width - radius, y);
            ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
            ctx.lineTo(x + width, y + height - radius);
            ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
            ctx.lineTo(x + radius, y + height);
            ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
            ctx.lineTo(x, y + radius);
            ctx.quadraticCurveTo(x, y, x + radius, y);
            ctx.closePath();
            if (fill) ctx.fill();
            if (stroke) ctx.stroke();
        }

        // ================= RENDER SLIDE 1: HOOK =================
        function renderSlide1(ctx, W, H) {
            const bg = ctx.createLinearGradient(0, 0, 0, H);
            bg.addColorStop(0, '#150608');
            bg.addColorStop(0.5, '#0a0d16');
            bg.addColorStop(1, '#05070d');
            ctx.fillStyle = bg;
            ctx.fillRect(0, 0, W, H);

            const glow = ctx.createRadialGradient(W/2, 500, 50, W/2, 500, 480);
            glow.addColorStop(0, 'rgba(239, 68, 68, 0.28)');
            glow.addColorStop(1, 'rgba(0, 0, 0, 0)');
            ctx.fillStyle = glow;
            ctx.beginPath();
            ctx.arc(W/2, 500, 480, 0, Math.PI * 2);
            ctx.fill();

            // Warning Pill
            ctx.fillStyle = 'rgba(239, 68, 68, 0.2)';
            ctx.strokeStyle = '#ef4444';
            ctx.lineWidth = 4;
            roundRect(ctx, 240, 220, W - 480, 90, 45, true, true);
            ctx.fillStyle = '#ff6b6b';
            ctx.font = 'bold 44px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(currentLang === 'am' ? '⚠️ አንድሮይድ ማስጠንቀቂያ' : '⚠️ ANDROID WARNING', W/2, 280);

            // Big Hook Text
            const hook = document.getElementById('hook_text').value;
            ctx.fillStyle = '#ffffff';
            ctx.font = '900 78px sans-serif';
            
            const words = hook.split(' ');
            let line = '';
            let y = 600;
            for (let n = 0; n < words.length; n++) {
                let testLine = line + words[n] + ' ';
                let metrics = ctx.measureText(testLine);
                if (metrics.width > W - 180 && n > 0) {
                    ctx.fillText(line, W/2, y);
                    line = words[n] + ' ';
                    y += 105;
                } else {
                    line = testLine;
                }
            }
            ctx.fillText(line, W/2, y);

            // Teaser Card
            ctx.fillStyle = 'rgba(23, 32, 54, 0.85)';
            ctx.strokeStyle = 'rgba(56, 189, 248, 0.4)';
            ctx.lineWidth = 3;
            roundRect(ctx, 100, 1050, W - 200, 360, 40, true, true);

            ctx.fillStyle = '#38bdf8';
            ctx.font = 'bold 48px sans-serif';
            ctx.fillText(currentLang === 'am' ? '99% ሰዎች የማያውቁት ምስጢር' : '99% of people don’t know this', W/2, 1160);

            ctx.fillStyle = '#94a3b8';
            ctx.font = '40px sans-serif';
            ctx.fillText(currentLang === 'am' ? 'ነፃ እና ኦፕን ሶርስ የሆነ ድብቅ አፕ' : 'There is a 100% free open-source secret', W/2, 1250);
            ctx.fillText(currentLang === 'am' ? 'ይህንን ችግር ወዲያውኑ ይፈታል' : 'that fixes this immediately.', W/2, 1320);

            // Bottom Swipe Pill
            ctx.fillStyle = '#f59e0b';
            roundRect(ctx, 200, 1560, W - 400, 110, 55, true, false);
            ctx.fillStyle = '#000000';
            ctx.font = 'bold 50px sans-serif';
            ctx.fillText(currentLang === 'am' ? 'ቀጣዩን ለማየት ይጎትቱ ➡️' : 'SWIPE TO REVEAL ➡️', W/2, 1635);
        }

        // ================= RENDER SLIDE 2: VALUE CARD =================
        function renderSlide2(ctx, W, H) {
            const bgGrad = ctx.createLinearGradient(0, 0, 0, H);
            bgGrad.addColorStop(0, '#0a0e1a');
            bgGrad.addColorStop(1, '#12182a');
            ctx.fillStyle = bgGrad;
            ctx.fillRect(0, 0, W, H);

            const glowGrad = ctx.createRadialGradient(W/2, 600, 50, W/2, 600, 450);
            glowGrad.addColorStop(0, 'rgba(46, 204, 113, 0.22)');
            glowGrad.addColorStop(1, 'rgba(0, 0, 0, 0)');
            ctx.fillStyle = glowGrad;
            ctx.beginPath();
            ctx.arc(W/2, 600, 450, 0, Math.PI * 2);
            ctx.fill();

            const badge = document.getElementById('badge_text').value;
            ctx.fillStyle = 'rgba(39, 174, 96, 0.2)';
            ctx.strokeStyle = '#2ecc71';
            ctx.lineWidth = 3;
            roundRect(ctx, 240, 150, W - 480, 80, 40, true, true);
            ctx.fillStyle = '#2ecc71';
            ctx.font = 'bold 38px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(badge, W/2, 205);

            // Main Glass Card
            ctx.fillStyle = 'rgba(23, 32, 54, 0.88)';
            ctx.strokeStyle = 'rgba(60, 80, 120, 0.6)';
            ctx.lineWidth = 2;
            roundRect(ctx, 80, 270, W - 160, 1140, 45, true, true);

            // Icon
            const iconSize = 200;
            ctx.fillStyle = '#2ecc71';
            roundRect(ctx, (W - iconSize)/2, 340, iconSize, iconSize, 42, true, false);
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 100px sans-serif';
            ctx.fillText('⚡', W/2, 475);

            const name = document.getElementById('app_name').value;
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 76px sans-serif';
            ctx.fillText(name, W/2, 640);

            const cat = document.getElementById('category').value;
            ctx.fillStyle = '#a0afcd';
            ctx.font = '40px sans-serif';
            ctx.fillText(cat, W/2, 720);

            const rating = document.getElementById('rating').value;
            ctx.fillStyle = '#f1c40f';
            ctx.font = 'bold 44px sans-serif';
            ctx.fillText(rating, W/2, 790);

            ctx.strokeStyle = 'rgba(50, 70, 105, 0.8)';
            ctx.beginPath();
            ctx.moveTo(140, 850);
            ctx.lineTo(W - 140, 850);
            ctx.stroke();

            const f1 = document.getElementById('f1').value;
            const f2 = document.getElementById('f2').value;
            const f3 = document.getElementById('f3').value;
            const bullets = [f1, f2, f3];

            bullets.forEach((b, idx) => {
                const y = 940 + (idx * 115);
                ctx.fillStyle = 'rgba(46, 204, 113, 0.2)';
                roundRect(ctx, 140, y - 35, 50, 50, 25, true, false);
                ctx.fillStyle = '#2ecc71';
                ctx.font = 'bold 36px sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('✓', 165, y + 2);

                ctx.fillStyle = '#e6edf3';
                ctx.font = '38px sans-serif';
                ctx.textAlign = 'left';
                ctx.fillText(b, 220, y + 2);
            });

            // CTA Box
            ctx.fillStyle = '#0284c7';
            ctx.strokeStyle = '#38bdf8';
            ctx.lineWidth = 3;
            roundRect(ctx, 80, 1470, W - 160, 310, 38, true, true);

            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 48px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(currentLang === 'am' ? '📲 አፑን ለማውረድ' : '📲 DOWNLOAD APK & GUIDE', W/2, 1550);

            ctx.fillStyle = '#ffff78';
            ctx.font = 'bold 54px sans-serif';
            ctx.fillText('👉 Join Telegram: <?= $CHANNEL_ID ?>', W/2, 1640);

            ctx.fillStyle = '#dcf0ff';
            ctx.font = '40px sans-serif';
            ctx.fillText(currentLang === 'am' ? '(ሊንኩ በባዮ / ኮሜንት ላይ አለ)' : '(Link in Bio / Comments)', W/2, 1720);
        }

        // ================= RENDER SLIDE 3: FUNNEL CTA =================
        function renderSlide3(ctx, W, H) {
            const bg = ctx.createLinearGradient(0, 0, 0, H);
            bg.addColorStop(0, '#041324');
            bg.addColorStop(0.5, '#072440');
            bg.addColorStop(1, '#020b14');
            ctx.fillStyle = bg;
            ctx.fillRect(0, 0, W, H);

            const glow = ctx.createRadialGradient(W/2, 600, 50, W/2, 600, 500);
            glow.addColorStop(0, 'rgba(14, 165, 233, 0.32)');
            glow.addColorStop(1, 'rgba(0, 0, 0, 0)');
            ctx.fillStyle = glow;
            ctx.beginPath();
            ctx.arc(W/2, 600, 500, 0, Math.PI * 2);
            ctx.fill();

            ctx.fillStyle = '#0088cc';
            roundRect(ctx, W/2 - 120, 260, 240, 240, 120, true, false);
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 130px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('✈️', W/2, 425);

            ctx.fillStyle = '#ffffff';
            ctx.font = '900 70px sans-serif';
            ctx.fillText(currentLang === 'am' ? 'አፑን በነፃ ያውርዱ' : 'GET THE APK FREE', W/2, 600);

            ctx.fillStyle = '#7dd3fc';
            ctx.font = '44px sans-serif';
            ctx.fillText(currentLang === 'am' ? 'ያለ ምንም ማስታወቂያ ወይም ክፍያ' : 'Direct download with zero ads or tracking', W/2, 680);

            ctx.fillStyle = 'rgba(15, 23, 42, 0.9)';
            ctx.strokeStyle = '#0284c7';
            ctx.lineWidth = 3;
            roundRect(ctx, 90, 780, W - 180, 680, 40, true, true);

            const steps = currentLang === 'am' ? [
                { num: "1", text: "ቴሌግራም አፕን በስልክዎ ይክፈቱ" },
                { num: "2", text: "ሰርች ያድርጉ: <?= $CHANNEL_ID ?>" },
                { num: "3", text: "ወይም የቲክቶክ ባዮ ሊንክን ይጫኑ" },
                { num: "4", text: "የተሰካውን (Pinned) አፕ ያውርዱ" }
            ] : [
                { num: "1", text: "Open Telegram app on your phone" },
                { num: "2", text: "Search: <?= $CHANNEL_ID ?>" },
                { num: "3", text: "Or click link in TikTok / IG Bio" },
                { num: "4", text: "Tap PINNED MESSAGE for APK download" }
            ];

            steps.forEach((s, i) => {
                const y = 910 + (i * 135);
                ctx.fillStyle = '#0284c7';
                roundRect(ctx, 140, y - 45, 60, 60, 30, true, false);
                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 36px sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText(s.num, 170, y + 2);

                ctx.fillStyle = '#e2e8f0';
                ctx.font = 'bold 38px sans-serif';
                ctx.textAlign = 'left';
                ctx.fillText(s.text, 230, y);
            });

            ctx.fillStyle = '#22c55e';
            roundRect(ctx, 100, 1540, W - 200, 130, 65, true, false);
            ctx.fillStyle = '#052e16';
            ctx.font = '900 52px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('JOIN <?= $CHANNEL_ID ?> NOW', W/2, 1622);
        }

        function renderAllSlides() {
            const W = 1080, H = 1920;
            const c1 = document.getElementById('canvasHook').getContext('2d');
            const c2 = document.getElementById('canvasCard').getContext('2d');
            const c3 = document.getElementById('canvasCTA').getContext('2d');

            renderSlide1(c1, W, H);
            renderSlide2(c2, W, H);
            renderSlide3(c3, W, H);

            updateCaptionPreview();
        }

        function updateCaptionPreview() {
            const name = document.getElementById('app_name').value;
            const f1 = document.getElementById('f1').value;
            const hook = document.getElementById('hook_text').value;
            
            let caption = '';
            if (currentLang === 'am') {
                caption = `${hook}\n\n` +
                    `ይህ ድንቅ አፕ ${f1} በነፃ እና ያለ ማስታወቂያ ይሰጣል።\n\n` +
                    `📥 ሙሉ አፑን ለማውረድ ቴሌግራም ቻናላችን ላይ ተሰክቷል!\n` +
                    `👉 ቴሌግራም ላይ '<?= $CHANNEL_ID ?>' ብለው ይፈልጉ ወይም ባዮ ላይ ያለውን ሊንክ ይጫኑ!\n\n` +
                    `#ethiopia #habeshatiktok #addisababa #android #apk #${name.toLowerCase().replace(/[^a-z0-9]/g, '')} #techtok #fyp`;
            } else {
                caption = `${hook}\n\n` +
                    `This hidden gem gives you ${f1} completely free and open-source.\n\n` +
                    `📥 Full APK & setup tutorial is pinned in our Telegram!\n` +
                    `👉 Search '<?= $CHANNEL_ID ?>' on Telegram or click the link in bio!\n\n` +
                    `#android #apk #techhacks #${name.toLowerCase().replace(/[^a-z0-9]/g, '')} #usefulapps #techtok #fyp #ethiopia`;
            }

            document.getElementById('captionPreviewText').innerText = caption;
        }

        function downloadCanvas(canvasId, filename) {
            const canvas = document.getElementById(canvasId);
            const a = document.createElement('a');
            a.download = filename;
            a.href = canvas.toDataURL('image/png');
            a.click();
            showToast(`Downloaded: ${filename}`, 'success');
        }

        function downloadAllSlides() {
            const name = document.getElementById('app_name').value.toLowerCase().replace(/[^a-z0-9]/g, '_');
            showToast('Starting 3-slide download...', 'info');
            downloadCanvas('canvasHook', `teleraptor_${name}_slide1_hook.png`);
            setTimeout(() => {
                downloadCanvas('canvasCard', `teleraptor_${name}_slide2_value.png`);
            }, 400);
            setTimeout(() => {
                downloadCanvas('canvasCTA', `teleraptor_${name}_slide3_funnel.png`);
            }, 800);
        }

        function copyTikTokCaption() {
            const text = document.getElementById('captionPreviewText').innerText;
            navigator.clipboard.writeText(text).then(() => {
                const btn = document.getElementById('copyBtn');
                btn.innerHTML = '<span>✅</span> Copied!';
                showToast('Caption & hashtags copied to clipboard!', 'success');
                setTimeout(() => {
                    btn.innerHTML = '<span>📋</span> Copy Caption';
                }, 2000);
            });
        }

        function wrapShortener(type) {
            const input = document.getElementById('download_url');
            const current = input.value;
            if (type === 'shrinkme') {
                const token = localStorage.getItem('shrinkme_token') || prompt('Enter your ShrinkMe.io API Token (or leave blank to test):', 'd8d1e390c29f60f6d63bb1fa19');
                if (token !== null) {
                    localStorage.setItem('shrinkme_token', token);
                    input.value = `https://shrinkme.io/st?api=${token}&url=${encodeURIComponent(current)}`;
                    showToast('Shortened with ShrinkMe (USDT)!', 'success');
                }
            } else if (type === 'exe') {
                const token = localStorage.getItem('exe_token') || prompt('Enter your Exe.io API Token (or leave blank to test):', 'c54b2a8d3e9f');
                if (token !== null) {
                    localStorage.setItem('exe_token', token);
                    input.value = `https://exe.io/st?api=${token}&url=${encodeURIComponent(current)}`;
                    showToast('Shortened with Exe.io (Crypto)!', 'success');
                }
            } else if (type === 'channel') {
                input.value = 'https://t.me/<?= ltrim($CHANNEL_ID, '@') ?>';
                showToast('Set to Channel bio link', 'info');
            }
        }

        // ================= 8-SECOND VIDEO GENERATOR =================
        async function generateVideo() {
            const btn = document.getElementById('videoBtn');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span>⏳</span> Rendering 8s Video...';
            btn.disabled = true;
            showToast('Rendering 8s high-bitrate video...', 'info');

            const recCanvas = document.createElement('canvas');
            recCanvas.width = 1080;
            recCanvas.height = 1920;
            const rCtx = recCanvas.getContext('2d');

            const stream = recCanvas.captureStream(30);
            let mimeType = 'video/webm;codecs=vp9';
            if (!MediaRecorder.isTypeSupported(mimeType)) {
                mimeType = 'video/webm';
            }

            const recorder = new MediaRecorder(stream, { mimeType, videoBitsPerSecond: 6000000 });
            const chunks = [];
            recorder.ondataavailable = e => { if (e.data.size > 0) chunks.push(e.data); };

            recorder.onstop = () => {
                const blob = new Blob(chunks, { type: mimeType });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                const name = document.getElementById('app_name').value.toLowerCase().replace(/[^a-z0-9]/g, '_');
                a.download = `teleraptor_${name}_video.webm`;
                a.href = url;
                a.click();
                btn.innerHTML = originalHTML;
                btn.disabled = false;
                showToast('Video exported successfully!', 'success');
            };

            recorder.start();

            const fps = 30;
            const totalFrames = fps * 8; // 240 frames
            let frame = 0;

            const cHook = document.getElementById('canvasHook');
            const cCard = document.getElementById('canvasCard');
            const cCTA = document.getElementById('canvasCTA');

            const interval = setInterval(() => {
                rCtx.clearRect(0, 0, 1080, 1920);

                if (frame < 75) {
                    rCtx.drawImage(cHook, 0, 0);
                } else if (frame < 165) {
                    rCtx.drawImage(cCard, 0, 0);
                } else {
                    rCtx.drawImage(cCTA, 0, 0);
                }

                frame++;
                if (frame >= totalFrames) {
                    clearInterval(interval);
                    recorder.stop();
                }
            }, 1000 / fps);
        }

        function preparePublish() {
            const canvas = document.getElementById('canvasCard');
            document.getElementById('post_app_name').value = document.getElementById('app_name').value;
            document.getElementById('post_category').value = document.getElementById('category').value;
            document.getElementById('post_rating').value = document.getElementById('rating').value;
            document.getElementById('post_f1').value = document.getElementById('f1').value;
            document.getElementById('post_f2').value = document.getElementById('f2').value;
            document.getElementById('post_f3').value = document.getElementById('f3').value;
            document.getElementById('post_url').value = document.getElementById('download_url').value;
            document.getElementById('post_image_data').value = canvas.toDataURL('image/png');
            
            const btn = document.getElementById('publishSubmitBtn');
            btn.innerHTML = '<span>⏳</span> Publishing to <?= $CHANNEL_ID ?>...';
            btn.disabled = true;
        }

        // Lightbox Functions
        function openLightbox(canvasId, title) {
            const canvas = document.getElementById(canvasId);
            const dataUrl = canvas.toDataURL('image/png');
            document.getElementById('lightboxImg').src = dataUrl;
            document.getElementById('lightboxTitle').innerText = title;
            document.getElementById('lightboxDownloadBtn').onclick = () => downloadCanvas(canvasId, `${title.toLowerCase().replace(/[^a-z0-9]/g, '_')}.png`);
            document.getElementById('lightboxModal').classList.remove('hidden');
        }

        function closeLightbox() {
            document.getElementById('lightboxModal').classList.add('hidden');
        }

        // CI/CD Git Deploy Trigger
        async function triggerGitDeploy() {
            const modal = document.getElementById('deployModal');
            const content = document.getElementById('deployModalContent');
            modal.classList.remove('hidden');
            content.innerHTML = '<div class="flex items-center gap-2 text-cyan-400"><span class="animate-spin">⏳</span><span>Pulling latest changes from Git repository...</span></div>';

            try {
                const res = await fetch('deploy.php?secret=<?= $DEPLOY_SECRET ?>&json=1');
                const data = await res.json();
                
                let html = `<div class="text-emerald-400 font-bold mb-2">✅ Deployment finished in ${data.duration}</div>`;
                data.steps.forEach((s, idx) => {
                    html += `<div class="bg-slate-900 p-2.5 rounded-lg border border-slate-800">
                        <div class="text-cyan-300 font-semibold mb-1">${idx + 1}. ${s.step}</div>
                        <pre class="text-[11px] text-slate-400 overflow-x-auto whitespace-pre-wrap">${s.output}</pre>
                    </div>`;
                });
                content.innerHTML = html;
                showToast('Git Pull completed successfully!', 'success');
            } catch (err) {
                content.innerHTML = `<div class="text-red-400">❌ Git Deploy request failed: ${err.message}</div><p class="text-xs text-slate-500 mt-2">You can also run directly in server terminal:<br><code>cd /domains/teleraptor.parakichner.com/public_html && git pull origin main</code></p>`;
                showToast('Deploy request failed', 'error');
            }
        }

        function closeDeployModal() {
            document.getElementById('deployModal').classList.add('hidden');
        }

        let currentVideoStyle = 'infographic';

        function openVideoModal() {
            const modal = document.getElementById('videoModal');
            const player = document.getElementById('viralVideoPlayer');
            modal.classList.remove('hidden');
            player.play().catch(() => {});
        }

        function closeVideoModal() {
            const modal = document.getElementById('videoModal');
            const player = document.getElementById('viralVideoPlayer');
            player.pause();
            modal.classList.add('hidden');
        }

        function switchVideoStyle(style) {
            currentVideoStyle = style;
            const player = document.getElementById('viralVideoPlayer');
            const dlBtn = document.getElementById('videoDownloadLink');
            const btnInfo = document.getElementById('styleBtn_info');
            const btnMpt = document.getElementById('styleBtn_mpt');
            
            if (style === 'infographic') {
                player.src = 'sample_short.mp4';
                dlBtn.href = 'sample_short.mp4';
                dlBtn.download = 'teleraptor_style_a_infographic.mp4';
                btnInfo.className = 'flex-1 py-1.5 rounded-lg bg-cyan-600 text-white shadow font-bold transition';
                btnMpt.className = 'flex-1 py-1.5 rounded-lg text-slate-400 hover:text-white font-medium transition';
                showToast('Switched to Style A: Infographic Deck', 'info');
            } else {
                player.src = 'mpt_style_short.mp4';
                dlBtn.href = 'mpt_style_short.mp4';
                dlBtn.download = 'teleraptor_style_b_moneyprinterturbo.mp4';
                btnMpt.className = 'flex-1 py-1.5 rounded-lg bg-rose-600 text-white shadow font-bold transition';
                btnInfo.className = 'flex-1 py-1.5 rounded-lg text-slate-400 hover:text-white font-medium transition';
                showToast('Switched to Style B: MoneyPrinterTurbo Collage', 'info');
            }
            player.play().catch(() => {});
        }

        function copyVideoUrl() {
            const file = currentVideoStyle === 'infographic' ? 'sample_short.mp4' : 'mpt_style_short.mp4';
            const url = window.location.origin + '/' + file;
            navigator.clipboard.writeText(url).then(() => {
                showToast('Video link copied: ' + file, 'success');
            });
        }

        window.onload = () => {
            renderPresetButtons();
            renderAllSlides();
        };
    </script>
<?php endif; ?>

    <footer class="mt-8 text-center text-xs text-slate-500 border-t border-slate-900 pt-4 font-medium">
        Teleraptor Automated Media Funnel Studio • Engineered for Fuad (@Fuad761) • <?= date('Y') ?>
    </footer>
</body>
</html>
