<?php
// ========================================================
// TELERAPTOR CI/CD AUTOMATED DEPLOYMENT WEBHOOK & RUNNER
// Subdomain: teleraptor.parakichner.com
// Triggers 'git pull' automatically via Webhook or Browser
// ========================================================

// Load Configuration
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
} else {
    define('DEPLOY_SECRET', 'teleraptor_deploy_7610');
}

// Authentication check: secret parameter or session admin
session_start();
$provided_secret = $_GET['secret'] ?? $_POST['secret'] ?? '';
$is_admin = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;

if (!$is_admin && $provided_secret !== DEPLOY_SECRET) {
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized: Invalid or missing deployment secret token.'
    ]);
    exit;
}

// Determine repo directory
$repo_dir = __DIR__;
chdir($repo_dir);

// Commands to run
$commands = [
    'git status -s' => 'Checking working tree status',
    'git fetch origin main 2>&1' => 'Fetching latest commits',
    'git reset --hard origin/main 2>&1' => 'Syncing with origin/main',
    'git log -1 --pretty=format:"%h - %an: %s (%cr)"' => 'Latest commit details'
];

$output_log = [];
$start_time = microtime(true);

foreach ($commands as $cmd => $desc) {
    $cmd_output = shell_exec($cmd);
    $output_log[] = [
        'step' => $desc,
        'command' => $cmd,
        'output' => trim($cmd_output ?? 'Done (no output)')
    ];
}

$duration = round(microtime(true) - $start_time, 2);

// Append to deploy.log
$log_entry = "[" . date('Y-m-d H:i:s') . "] Deploy executed in {$duration}s. Trigger: " . ($is_admin ? 'Admin UI' : 'Webhook') . "\n";
@file_put_contents(__DIR__ . '/deploy.log', $log_entry, FILE_APPEND);

// Return JSON if requested or Webhook
if (isset($_GET['json']) || !empty($_SERVER['HTTP_X_GITHUB_EVENT'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'duration' => "{$duration}s",
        'timestamp' => date('Y-m-d H:i:s'),
        'steps' => $output_log
    ], JSON_PRETTY_PRINT);
    exit;
}

// Beautiful UI Output for Browser invocation
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CI/CD Deployment • Teleraptor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #07090e; color: #e2e8f0; font-family: 'Plus Jakarta Sans', sans-serif; }
        .code-box { font-family: 'JetBrains Mono', monospace; }
        .glass-panel { background: rgba(14, 20, 34, 0.75); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.08); }
    </style>
</head>
<body class="p-4 md:p-10 max-w-4xl mx-auto min-h-screen flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-2xl shadow-lg shadow-cyan-500/20">
                    🚀
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white tracking-wide">CI/CD Git Deployment Runner</h1>
                    <p class="text-xs text-gray-400">Target: <span class="text-cyan-400 font-mono"><?= htmlspecialchars($repo_dir) ?></span></p>
                </div>
            </div>
            <a href="index.php" class="text-xs bg-gray-800 hover:bg-gray-700 text-gray-200 px-4 py-2 rounded-xl transition border border-gray-700 font-semibold">
                ← Back to Studio
            </a>
        </div>

        <div class="glass-panel rounded-3xl p-6 md:p-8 shadow-2xl mb-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-sm font-bold text-emerald-400 uppercase tracking-wider">Deployment Succeeded</span>
                </div>
                <span class="text-xs text-gray-400 bg-gray-900 px-3 py-1.5 rounded-lg border border-gray-800 font-mono">
                    Completed in <?= $duration ?>s
                </span>
            </div>

            <div class="space-y-4">
                <?php foreach ($output_log as $idx => $step): ?>
                    <div class="bg-gray-950/70 border border-gray-800/80 rounded-2xl p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold text-cyan-300"><?= ($idx + 1) ?>. <?= htmlspecialchars($step['step']) ?></span>
                            <span class="text-[11px] code-box text-gray-500"><?= htmlspecialchars($step['command']) ?></span>
                        </div>
                        <pre class="code-box text-xs text-gray-300 bg-black/60 p-3 rounded-xl overflow-x-auto whitespace-pre-wrap border border-gray-900"><?= htmlspecialchars($step['output']) ?></pre>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-800 flex flex-wrap gap-3 justify-between items-center">
                <span class="text-xs text-gray-500">
                    Webhook URL: <code class="text-cyan-400">https://teleraptor.parakichner.com/deploy.php?secret=<?= DEPLOY_SECRET ?></code>
                </span>
                <a href="deploy.php?secret=<?= DEPLOY_SECRET ?>" class="text-xs bg-cyan-600 hover:bg-cyan-500 text-white font-bold px-4 py-2 rounded-xl transition">
                    🔄 Re-Run Git Pull
                </a>
            </div>
        </div>
    </div>

    <footer class="text-center text-xs text-gray-600 py-4">
        Teleraptor Automated Deployment Engine • <?= date('Y') ?>
    </footer>
</body>
</html>
