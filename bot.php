<?php
// ========================================================
// TELERAPTOR 24/7 CLOUD TELEGRAM WEBHOOK BOT
// URL: https://teleraptor.parakichner.com/bot.php
// Zero Python • 100% PHP • Hosted on DirectAdmin LiteSpeed
// ========================================================

if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

$BOT_TOKEN = defined('BOT_TOKEN') ? BOT_TOKEN : "8794122196:AAH2LqFAMhKGJVHTYsFybp1OZtP50dineUE";
$ADMIN_ID = defined('ADMIN_ID') ? (int)ADMIN_ID : 332489517; // Fuad (@Fuad761)
$CHANNEL_ID = defined('CHANNEL_ID') ? CHANNEL_ID : "@teleraptor_ig";
$API_URL = "https://api.telegram.org/bot{$BOT_TOKEN}";

// Pre-curated viral library for instant 1-tap mobile publishing
$APPS = [
    'spotube' => [
        'name' => 'Spotube',
        'category' => 'Open-Source Audio & Music',
        'rating' => '4.9 ★★★★★',
        'highlights' => [
            'Zero Ads, No Spotify account required',
            'Free high-bitrate 320kbps offline downloads',
            '100% Open-Source & battery friendly'
        ],
        'url' => 'https://github.com/KRTirtho/spotube',
        'desc' => 'Stream and download millions of songs for free without ads or account.'
    ],
    'seal' => [
        'name' => 'Seal',
        'category' => 'Video & Audio Downloader',
        'rating' => '4.8 ★★★★★',
        'highlights' => [
            'Download from 1,000+ sites (YouTube, TikTok, IG)',
            'Extract 320kbps MP3 audio with 1 click',
            'Modern Material You UI, completely ad-free'
        ],
        'url' => 'https://github.com/JunkFood02/Seal',
        'desc' => 'The cleanest open-source video and audio downloader powered by yt-dlp.'
    ],
    'localsend' => [
        'name' => 'LocalSend',
        'category' => 'Cross-Platform Sharing',
        'rating' => '4.9 ★★★★★',
        'highlights' => [
            'AirDrop for Android, Windows, Mac & Linux',
            'Zero mobile data or internet needed (Local Wi-Fi)',
            'No tracking, no file size limits, zero compression'
        ],
        'url' => 'https://localsend.org',
        'desc' => 'Transfer huge files between phone and PC instantly without cables.'
    ],
    'immich' => [
        'name' => 'Immich',
        'category' => 'Self-Hosted Photo Storage',
        'rating' => '4.9 ★★★★★',
        'highlights' => [
            'Direct Google Photos replacement',
            'Unlimited backup without 15GB limits',
            'AI facial recognition and search built-in'
        ],
        'url' => 'https://immich.app',
        'desc' => 'Never pay for Google One cloud storage again.'
    ],
    'netguard' => [
        'name' => 'NetGuard',
        'category' => 'No-Root Firewall & Adblock',
        'rating' => '4.7 ★★★★★',
        'highlights' => [
            'Block ads in games and apps system-wide',
            'Requires zero root permissions',
            'Saves up to 40% battery and mobile data'
        ],
        'url' => 'https://netguard.me',
        'desc' => 'Take full control of which apps can access your mobile internet.'
    ]
];

$JOBS = [
    'flutter' => [
        'role' => 'Senior Flutter / Mobile Developer',
        'company' => 'Global Fintech Corp',
        'salary' => '$4,500 - $6,500 / month (USD)',
        'perks' => [
            '100% Remote (Work from Ethiopia/Worldwide)',
            'Paid in USD / Crypto / Wire Transfer',
            'Flexible Schedule + Equipment Stipend'
        ],
        'url' => 'https://t.me/teleraptor_ig'
    ],
    'support' => [
        'role' => 'Remote Customer Support Specialist',
        'company' => 'International SaaS Platform',
        'salary' => '$2,000 - $2,800 / month (USD)',
        'perks' => [
            'No coding degree required (Fluent English)',
            'Full training provided + internet stipend',
            'Paid monthly in USD or Crypto'
        ],
        'url' => 'https://t.me/teleraptor_ig'
    ]
];

// Helper: Send Telegram Request
function telegram_request($method, $params = []) {
    global $API_URL;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "{$API_URL}/{$method}");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true);
}

// Receive incoming webhook payload
$input = file_get_contents('php://input');
if (!$input) {
    // If visited directly in browser, show status
    echo "<h1>⚡ Teleraptor Telegram Webhook Active</h1>";
    echo "<p>Connected to {$CHANNEL_ID} • Running on LiteSpeed PHP</p>";
    exit;
}

$update = json_decode($input, true);

// Handle Callback Queries (Inline Button Clicks)
if (isset($update['callback_query'])) {
    $cb = $update['callback_query'];
    $cb_id = $cb['id'];
    $from_id = $cb['from']['id'];
    $chat_id = $cb['message']['chat']['id'];
    $data = $cb['data'] ?? '';

    // Acknowledge callback immediately
    telegram_request('answerCallbackQuery', ['callback_query_id' => $cb_id]);

    if ($from_id != $ADMIN_ID) {
        telegram_request('sendMessage', [
            'chat_id' => $chat_id,
            'text' => '⛔ Access restricted to Fuad (@Fuad761).'
        ]);
        exit;
    }

    if (strpos($data, 'publish_app_') === 0) {
        $app_key = str_replace('publish_app_', '', $data);
        if (isset($APPS[$app_key])) {
            $a = $APPS[$app_key];
            $caption = "🔥 <b>HIDDEN ANDROID GEM: {$a['name']}</b>\n\n";
            $caption .= "{$a['desc']}\n\n";
            $caption .= "<b>Category:</b> {$a['category']}\n";
            $caption .= "<b>Rating:</b> {$a['rating']}\n\n";
            $caption .= "✨ <b>Highlights:</b>\n";
            foreach ($a['highlights'] as $h) {
                $caption .= "• {$h}\n";
            }
            $caption .= "\n👇 <i>Tap below to download official release:</i>";

            $keyboard = [
                "inline_keyboard" => [
                    [["text" => "📲 Download {$a['name']} APK", "url" => $a['url']]],
                    [
                        ["text" => "⭐ Repository / Site", "url" => $a['url']],
                        ["text" => "💬 Share Channel", "url" => "https://t.me/share/url?url=https://t.me/teleraptor_ig&text=Check%20out%20this%20gem!"]
                    ]
                ]
            ];

            $res = telegram_request('sendMessage', [
                'chat_id' => $CHANNEL_ID,
                'text' => $caption,
                'parse_mode' => 'HTML',
                'reply_markup' => $keyboard
            ]);

            if ($res && $res['ok']) {
                telegram_request('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "✅ <b>Successfully broadcasted {$a['name']} to {$CHANNEL_ID}!</b>\nMessage ID: " . $res['result']['message_id'],
                    'parse_mode' => 'HTML'
                ]);
            } else {
                telegram_request('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "❌ Error publishing: " . ($res['description'] ?? 'Unknown error')
                ]);
            }
        }
    } elseif (strpos($data, 'publish_job_') === 0) {
        $job_key = str_replace('publish_job_', '', $data);
        if (isset($JOBS[$job_key])) {
            $j = $JOBS[$job_key];
            $caption = "🌍 <b>100% REMOTE GLOBAL JOB ALERT</b>\n\n";
            $caption .= "💼 <b>Role:</b> {$j['role']}\n";
            $caption .= "🏢 <b>Company:</b> {$j['company']}\n";
            $caption .= "💵 <b>Salary:</b> {$j['salary']}\n\n";
            $caption .= "✨ <b>Perks & Benefits:</b>\n";
            foreach ($j['perks'] as $p) {
                $caption .= "• {$p}\n";
            }
            $caption .= "\n👇 <i>Apply or view instructions below:</i>";

            $keyboard = [
                "inline_keyboard" => [
                    [["text" => "📝 Apply Now / Details", "url" => $j['url']]],
                    [
                        ["text" => "💬 Share Job", "url" => "https://t.me/share/url?url=https://t.me/teleraptor_ig&text=Remote%20Job%20Opportunity!"]
                    ]
                ]
            ];

            $res = telegram_request('sendMessage', [
                'chat_id' => $CHANNEL_ID,
                'text' => $caption,
                'parse_mode' => 'HTML',
                'reply_markup' => $keyboard
            ]);

            if ($res && $res['ok']) {
                telegram_request('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "✅ <b>Successfully broadcasted Job ({$j['role']}) to {$CHANNEL_ID}!</b>",
                    'parse_mode' => 'HTML'
                ]);
            }
        }
    } elseif ($data === 'view_stats') {
        $chat_info = telegram_request('getChat', ['chat_id' => $CHANNEL_ID]);
        $count = telegram_request('getChatMemberCount', ['chat_id' => $CHANNEL_ID]);

        $title = $chat_info['result']['title'] ?? 'Teleraptor';
        $members = $count['result'] ?? 'Unknown';

        telegram_request('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "📊 <b>Channel Analytics:</b>\n\n" .
                      "<b>Channel:</b> {$title} ({$CHANNEL_ID})\n" .
                      "<b>Total Subscribers:</b> <code>{$members}</code>\n" .
                      "<b>Status:</b> 🟢 Active & Broadcasting\n\n" .
                      "💡 <i>Tip: Post 1 TikTok Carousel daily at 12:00 PM or 7:00 PM EAT to gain 50-200 subscribers/day.</i>",
            'parse_mode' => 'HTML'
        ]);
    }
    exit;
}

// Handle Incoming Text Messages
if (isset($update['message'])) {
    $msg = $update['message'];
    $chat_id = $msg['chat']['id'];
    $from_id = $msg['from']['id'] ?? 0;
    $text = trim($msg['text'] ?? '');

    // Public User Funnel
    if ($from_id != $ADMIN_ID) {
        $keyboard = [
            "inline_keyboard" => [
                [["text" => "👉 Join @teleraptor_ig Channel", "url" => "https://t.me/teleraptor_ig"]],
                [["text" => "🌐 Visit Website", "url" => "https://teleraptor.parakichner.com/bio.php"]]
            ]
        ];
        telegram_request('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "👋 <b>Welcome to Teleraptor!</b>\n\n" .
                      "We share the best 100% free open-source Android apps, hidden tools, and remote tech jobs.\n\n" .
                      "Tap below to join our official channel where all download links and setup guides are pinned:",
            'parse_mode' => 'HTML',
            'reply_markup' => $keyboard
        ]);
        exit;
    }

    // Admin Menu (Fuad)
    $menu = [
        "inline_keyboard" => [
            [
                ["text" => "🎵 Post Spotube", "callback_data" => "publish_app_spotube"],
                ["text" => "📥 Post Seal", "callback_data" => "publish_app_seal"]
            ],
            [
                ["text" => "📡 Post LocalSend", "callback_data" => "publish_app_localsend"],
                ["text" => "🖼️ Post Immich", "callback_data" => "publish_app_immich"]
            ],
            [
                ["text" => "🛡️ Post NetGuard", "callback_data" => "publish_app_netguard"],
                ["text" => "💼 Post Remote Job", "callback_data" => "publish_job_flutter"]
            ],
            [
                ["text" => "📊 Channel Stats", "callback_data" => "view_stats"],
                ["text" => "🌐 Open Web Studio", "url" => "https://teleraptor.parakichner.com"]
            ]
        ]
    ];

    telegram_request('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "👑 <b>Teleraptor 24/7 Cloud Admin</b>\n\n" .
                  "Your automated bot is running in the cloud on your shared hosting (zero PC required).\n\n" .
                  "Tap any preset below to broadcast directly to <b>{$CHANNEL_ID}</b>:",
        'parse_mode' => 'HTML',
        'reply_markup' => $menu
    ]);
}
