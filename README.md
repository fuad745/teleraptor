# ⚡ Teleraptor: Viral Social-to-Telegram Content Funnel & Studio Pro

> **Automated, Zero-Dependency Social Media Funnel & Monetization Engine for Ethiopian Creators & Worldwide Tech Channels.**  
> Built with pure PHP 8.3 & HTML5 Canvas. Zero background processes, zero Python server dependencies. Runs 100% on Shared Hosting (LiteSpeed / DirectAdmin / cPanel).

---

## 🌟 Architecture Overview

```
teleraptor/
├── index.php             # 🚀 Teleraptor Studio Pro Dashboard (Canvas Carousel & Video Generator)
├── bio.php               # 🔗 High-Converting Mobile Link-in-Bio Landing Page
├── bot.php               # 🤖 24/7 Cloud Telegram Webhook Bot (Handles /start, /post, callbacks)
├── deploy.php            # 🔄 CI/CD Git Deployment Runner & Webhook Endpoint
├── config.php            # 🔒 Local Server Configuration & Credentials (Git-ignored)
├── config.example.php    # 📄 Template Configuration file
├── tools/                # 🛠️ Optional Python Desktop Generators & n8n Workflows
│   ├── admin_bot.py
│   ├── generate_card.py
│   ├── generate_carousel.py
│   ├── post_to_telegram.py
│   ├── send_tiktok_pack.py
│   └── n8n_content_funnel.json
└── README.md             # 📖 Documentation & CI/CD Setup Guide
```

---

## 🎨 Impeccable UI/UX Engineering

- **Modern Typographic Hierarchy**: Engineered with Google Fonts **Plus Jakarta Sans** (clean, ergonomic body) and **Outfit** (striking, punchy display headings) with **JetBrains Mono** for metrics.
- **Dark Obsidian Aesthetics**: Premium space palette (`#07090e`, `#0e1422`) with subtle radial gradients, glassmorphism (`backdrop-filter: blur(16px)`), and 1px border glows.
- **Interactive Slide Switcher**:
  - **Desktop**: 3-column live card carousel with interactive hover controls.
  - **Mobile**: Segmented tab bar (`[1: Hook]`, `[2: Value Card]`, `[3: Funnel CTA]`) eliminating mobile vertical scrolling fatigue.
- **Slide Lightbox Inspector**: Tap any slide canvas to view high-resolution fullscreen preview with 1-tap PNG download.
- **Floating Toast System**: Non-blocking floating glass notifications with icon indicators and smooth auto-dismiss animations.
- **Sticky Mobile Quick-Action Bar**: Floating bottom toolbar on mobile devices for instant 1-tap `[🚀 Post]`, `[📥 3 Slides]`, and `[📋 Copy]`.
- **Bio Page Conversion Polish**: Animated verified creator badge, live member pulse indicator (`🟢 1,480+ Members Active`), glass app cards with category badges, and direct Telegram links.

---

## 🚀 CI/CD & Server Terminal Deployment

### Option 1: Direct Server Terminal Pull

In your DirectAdmin / cPanel Terminal or SSH:

```bash
# Navigate to your subdomain document root:
cd /domains/teleraptor.parakichner.com/public_html
# (or /domains/teleraptor/public_html depending on symlink)

# Initial clone (if empty):
git clone https://github.com/fuad745/teleraptor.git .

# To pull updates anytime:
git pull origin main
```

### Option 2: 1-Click CI/CD Deploy from Webhook or Studio

1. **Inside Studio Pro Dashboard**:  
   Click the **"🔄 Git Pull CI/CD"** button in the top navigation bar. It executes `deploy.php` and displays real-time git logs in an interactive modal.
2. **Via Browser / cURL**:  
   ```bash
   curl "https://teleraptor.parakichner.com/deploy.php?secret=teleraptor_deploy_7610&json=1"
   ```
3. **Automated GitHub Push Webhook**:  
   Add a GitHub Webhook under repository **Settings → Webhooks**:
   - **Payload URL**: `https://teleraptor.parakichner.com/deploy.php?secret=teleraptor_deploy_7610`
   - **Content type**: `application/json`
   - **Events**: `Just the push event`

---

## 🔐 Credentials & Security

All sensitive tokens (Telegram Bot Token, Admin PIN, Webhook Secrets) are isolated in `config.php` and excluded from Git tracking via `.gitignore`.

1. Copy `config.example.php` to `config.php`:
   ```bash
   cp config.example.php config.php
   ```
2. Fill in your credentials:
   ```php
   define('BOT_TOKEN', 'YOUR_BOT_TOKEN');
   define('CHANNEL_ID', '@teleraptor_ig');
   define('ADMIN_PIN', '7610');
   define('DEPLOY_SECRET', 'teleraptor_deploy_7610');
   ```

---

## 💰 Monetization Pathways for Ethiopian Creators

1. **Pay-Per-Download (PPA)**: Up-4ever & DropGalaxy paying $10–$40 per 1,000 downloads via USDT TRC20 / Binance.
2. **Crypto Shorteners**: ShrinkMe.io & Exe.io (integrated directly with 1-tap shortener buttons in the Studio).
3. **Sponsored Channel Broadcasts**: Local Telebirr / direct bank transfers and Telega.io ad marketplace ($15–$100/post).
4. **Tech & VPN Affiliates**: 40% recurring commissions for recommended privacy tools.

---

## 📱 Live Endpoints

- **Studio Pro Dashboard**: `https://teleraptor.parakichner.com/` (Default PIN: `7610`)
- **Mobile Link-in-Bio**: `https://teleraptor.parakichner.com/bio.php`
- **Webhook Bot Endpoint**: `https://teleraptor.parakichner.com/bot.php`
- **CI/CD Deploy Runner**: `https://teleraptor.parakichner.com/deploy.php?secret=teleraptor_deploy_7610`
- **Telegram Channel**: [@teleraptor_ig](https://t.me/teleraptor_ig)
- **Admin Contact**: [@Fuad761](https://t.me/Fuad761)
