<?php
// Teleraptor Central Configuration Template
// Copy this file to config.php and customize your credentials.

if (!defined('TELERAPTOR_CONFIG_LOADED')) {
    define('TELERAPTOR_CONFIG_LOADED', true);
    
    // Telegram Bot & Channel
    define('BOT_TOKEN', getenv('BOT_TOKEN') ?: 'YOUR_BOT_TOKEN_HERE');
    define('CHANNEL_ID', getenv('CHANNEL_ID') ?: '@teleraptor_ig');
    define('CHANNEL_CHAT_ID', getenv('CHANNEL_CHAT_ID') ?: '-1003572084558');
    
    // Admin Security PIN & Deploy Secret
    define('ADMIN_PIN', getenv('ADMIN_PIN') ?: '7610');
    define('DEPLOY_SECRET', getenv('DEPLOY_SECRET') ?: 'teleraptor_deploy_7610');
    
    // Admin Telegram Username
    define('ADMIN_USERNAME', getenv('ADMIN_USERNAME') ?: '@Fuad761');
    define('ADMIN_ID', getenv('ADMIN_ID') ?: '332489517');
}
