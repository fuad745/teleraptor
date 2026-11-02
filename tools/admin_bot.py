import os
import sys
import time
import json
import requests
from generate_card import generate_app_card, generate_job_card
from generate_carousel import generate_tiktok_carousel

TOKEN = "8794122196:AAH2LqFAMhKGJVHTYsFybp1OZtP50dineUE"
ADMIN_ID = 332489517  # Fuad's Telegram ID
CHANNEL_ID = "@teleraptor_ig"
API_BASE = f"https://api.telegram.org/bot{TOKEN}"

# Curated library of viral Android gems
CURATED_APPS = [
    {
        "name": "LocalSend",
        "category": "Cross-Platform File Sharing",
        "rating": "4.9 ★★★★★",
        "highlights": [
            "AirDrop for Android, Windows & Mac",
            "Zero internet needed (Local Wi-Fi)",
            "100% Open-Source & No file size limit"
        ],
        "url": "https://localsend.org",
        "desc": "Transfer files between Android and PC instantly with zero mobile data or setup."
    },
    {
        "name": "Seal",
        "category": "Video & Audio Downloader",
        "rating": "4.8 ★★★★★",
        "highlights": [
            "Download from 1,000+ sites (yt-dlp)",
            "Extract MP3 audio at 320kbps",
            "Ad-free, clean Material You UI"
        ],
        "url": "https://github.com/JunkFood02/Seal",
        "desc": "The cleanest, open-source video and audio downloader powered by yt-dlp."
    },
    {
        "name": "Spotube",
        "category": "Open-Source Audio & Music",
        "rating": "4.9 ★★★★★",
        "highlights": [
            "No Ads, No Account Required",
            "Free High-Bitrate Offline Downloads",
            "Uses Spotify & YouTube API under the hood"
        ],
        "url": "https://github.com/KRTirtho/spotube",
        "desc": "Spotify client with zero ads, offline downloads, and no account needed."
    },
    {
        "name": "Immich",
        "category": "Self-Hosted Photo Backup",
        "rating": "4.9 ★★★★★",
        "highlights": [
            "Direct Google Photos alternative",
            "AI facial recognition & search",
            "100% private, your own cloud storage"
        ],
        "url": "https://immich.app",
        "desc": "Never pay for Google Photos 15GB limits again. Fully private photo storage."
    }
]

app_index = 0

def send_msg(chat_id, text, reply_markup=None):
    payload = {"chat_id": chat_id, "text": text, "parse_mode": "HTML"}
    if reply_markup:
        payload["reply_markup"] = json.dumps(reply_markup)
    return requests.post(f"{API_BASE}/sendMessage", json=payload).json()

def send_photo(chat_id, photo_path, caption=None, reply_markup=None):
    if not os.path.exists(photo_path):
        return None
    data = {"chat_id": chat_id, "parse_mode": "HTML"}
    if caption:
        data["caption"] = caption
    if reply_markup:
        data["reply_markup"] = json.dumps(reply_markup)
    with open(photo_path, "rb") as f:
        return requests.post(f"{API_BASE}/sendPhoto", data=data, files={"photo": f}).json()

def send_main_menu(chat_id):
    menu = {
        "inline_keyboard": [
            [
                {"text": "📱 Next Android Gem", "callback_data": "next_app"},
                {"text": "💼 Next Remote Job", "callback_data": "next_job"}
            ],
            [
                {"text": "🎬 Generate TikTok Carousel Pack", "callback_data": "gen_tiktok"},
                {"text": "🚀 Publish to @teleraptor_ig", "callback_data": "publish_channel"}
            ]
        ]
    }
    send_msg(
        chat_id,
        "👑 <b>Teleraptor Admin Panel</b>\n\n"
        "Control your social media & Telegram funnel directly from your phone!\n\n"
        "Tap a button below to generate a new post or publish:",
        reply_markup=menu
    )

def handle_callback(cb_query):
    global app_index
    cb_id = cb_query["id"]
    from_user = cb_query["from"]["id"]
    chat_id = cb_query["message"]["chat"]["id"]
    data = cb_query.get("data")

    # Acknowledge callback query
    requests.post(f"{API_BASE}/answerCallbackQuery", json={"callback_query_id": cb_id})

    if from_user != ADMIN_ID:
        send_msg(chat_id, "⛔ Access restricted to Admin only.")
        return

    if data == "next_app":
        app = CURATED_APPS[app_index % len(CURATED_APPS)]
        app_index += 1
        output_card = "/home/kichner/content-funnel/output/app_card.png"
        generate_app_card(
            app_name=app["name"],
            category=app["category"],
            rating=app["rating"],
            highlights=app["highlights"],
            telegram_handle=CHANNEL_ID,
            output_path=output_card
        )
        caption = (
            f"🔥 <b>PREVIEW: {app['name']}</b>\n\n"
            f"{app['desc']}\n\n"
            f"<b>Category:</b> {app['category']}\n"
            f"<b>Rating:</b> {app['rating']}\n\n"
            f"<b>Highlights:</b>\n"
            + "\n".join([f"• {h}" for h in app["highlights"]]) + "\n\n"
            f"<i>Ready to broadcast to {CHANNEL_ID}?</i>"
        )
        buttons = {
            "inline_keyboard": [
                [
                    {"text": "🚀 Publish to Channel Now", "callback_data": "publish_channel"},
                    {"text": "🎬 Get TikTok Pack", "callback_data": "gen_tiktok"}
                ],
                [{"text": "🔄 Try Another App", "callback_data": "next_app"}]
            ]
        }
        send_photo(chat_id, output_card, caption=caption, reply_markup=buttons)

    elif data == "next_job":
        output_card = "/home/kichner/content-funnel/output/job_card.png"
        generate_job_card(
            role="Senior Flutter / Mobile Developer",
            company="Global Fintech Co.",
            salary="$4,500 - $6,500 / month",
            perks=[
                "100% Remote (Worldwide)",
                "Paid in USD / Crypto / Wire",
                "Equipment Stipend + Flex Schedule"
            ],
            telegram_handle=CHANNEL_ID,
            output_path=output_card
        )
        caption = (
            "🌍 <b>PREVIEW: Remote Tech Job</b>\n\n"
            "<b>Role:</b> Senior Flutter / Mobile Developer\n"
            "<b>Salary:</b> $4,500 - $6,500 / month (USD)\n"
            "<b>Perks:</b> 100% Remote, Paid in USD/Crypto\n\n"
            f"<i>Ready to broadcast to {CHANNEL_ID}?</i>"
        )
        buttons = {
            "inline_keyboard": [
                [
                    {"text": "🚀 Publish Job to Channel", "callback_data": "publish_job_channel"},
                    {"text": "🔄 Refresh Job", "callback_data": "next_job"}
                ]
            ]
        }
        send_photo(chat_id, output_card, caption=caption, reply_markup=buttons)

    elif data == "gen_tiktok":
        app = CURATED_APPS[(app_index - 1) % len(CURATED_APPS)]
        slides = generate_tiktok_carousel(
            app_name=app["name"],
            category=app["category"],
            rating=app["rating"],
            highlights=app["highlights"],
            telegram_handle=CHANNEL_ID
        )
        tiktok_caption = (
            f"Stop paying for subscriptions 🤫 {app['name']} gives you {app['highlights'][0]} completely free and open-source!\n\n"
            f"📥 Full APK & setup guide is pinned in our Telegram!\n"
            f"👉 Search '{CHANNEL_ID}' on Telegram or click link in bio!\n\n"
            f"#android #androidapps #{app['name'].lower()} #techhacks #apk #usefulapps #techtok #opensource"
        )
        send_msg(chat_id, "🎬 <b>TikTok / Instagram Carousel Ready!</b>\n\nHere is your ready-to-copy caption:\n\n<code>" + tiktok_caption + "</code>")
        for s in slides:
            send_photo(chat_id, s)

    elif data in ["publish_channel", "publish_job_channel"]:
        app = CURATED_APPS[(app_index - 1) % len(CURATED_APPS)]
        card_img = "/home/kichner/content-funnel/output/app_card.png"
        caption = (
            f"🔥 <b>HIDDEN ANDROID GEM: {app['name']}</b>\n\n"
            f"{app['desc']}\n\n"
            f"✨ <b>Key Features:</b>\n"
            + "\n".join([f"• <b>{h.split(':')[0]}:</b> {h}" for h in app["highlights"]]) + "\n\n"
            f"👇 <i>Use the buttons below to download the official release:</i>"
        )
        buttons = [
            [{"text": f"📲 Download {app['name']} APK", "url": app["url"]}],
            [
                {"text": "⭐ Official Repository", "url": app["url"]},
                {"text": "💬 Share Channel", "url": f"https://t.me/share/url?url=https://t.me/teleraptor_ig&text=Check%20out%20this%20app!"}
            ]
        ]
        res = send_photo(CHANNEL_ID, card_img, caption=caption, reply_markup={"inline_keyboard": buttons})
        if res.get("ok"):
            send_msg(chat_id, f"✅ <b>Successfully broadcasted to {CHANNEL_ID}!</b>\nMessage ID: {res['result']['message_id']}")
        else:
            send_msg(chat_id, f"❌ Failed to post: {res.get('description')}")

def run_bot_daemon():
    print("[*] Starting Teleraptor Phone Admin Bot daemon...")
    offset = 0
    # Clean up any old updates
    requests.get(f"{API_BASE}/getUpdates?offset=-1")
    while True:
        try:
            res = requests.get(f"{API_BASE}/getUpdates?offset={offset}&timeout=20", timeout=30).json()
            if not res.get("ok"):
                time.sleep(2)
                continue

            for update in res.get("result", []):
                offset = update["update_id"] + 1

                if "callback_query" in update:
                    handle_callback(update["callback_query"])

                elif "message" in update:
                    msg = update["message"]
                    from_id = msg.get("from", {}).get("id")
                    chat_id = msg["chat"]["id"]
                    text = msg.get("text", "")

                    if from_id != ADMIN_ID:
                        send_msg(chat_id, "⛔ Sorry, this bot is private for @Fuad761.")
                        continue

                    if text in ["/start", "/menu", "menu", "admin"]:
                        send_main_menu(chat_id)
                    elif text == "/app":
                        # Trigger app preview
                        handle_callback({"id": "0", "from": {"id": from_id}, "message": {"chat": {"id": chat_id}}, "data": "next_app"})
                    elif text == "/job":
                        handle_callback({"id": "0", "from": {"id": from_id}, "message": {"chat": {"id": chat_id}}, "data": "next_job"})
                    elif text == "/tiktok":
                        handle_callback({"id": "0", "from": {"id": from_id}, "message": {"chat": {"id": chat_id}}, "data": "gen_tiktok"})
                    else:
                        send_main_menu(chat_id)

        except Exception as e:
            print(f"[!] Error in polling loop: {e}")
            time.sleep(3)

if __name__ == "__main__":
    run_bot_daemon()
