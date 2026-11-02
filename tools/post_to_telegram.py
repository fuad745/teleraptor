import os
import sys
import json
import requests

DEFAULT_TOKEN = "8794122196:AAH2LqFAMhKGJVHTYsFybp1OZtP50dineUE"
DEFAULT_CHAT_ID = "@teleraptor_ig"

def send_telegram_post(
    bot_token=DEFAULT_TOKEN,
    chat_id=DEFAULT_CHAT_ID,
    caption=None,
    image_path=None,
    button_rows=None
):
    """
    Publishes an image card + rich HTML caption + interactive inline buttons to Telegram.
    """
    if caption is None:
        caption = (
            "🔥 <b>HIDDEN ANDROID GEM: Spotube</b>\n\n"
            "Spotify without advertisements, trackers, or premium subscriptions — built on Flutter & Dart!\n\n"
            "✨ <b>Key Features:</b>\n"
            "• <b>No Ads & No Account Required:</b> Anonymously stream millions of songs.\n"
            "• <b>Offline Downloads:</b> Download high-bitrate audio directly to your device.\n"
            "• <b>Battery Efficient:</b> Open-source and consumes minimal background resources.\n\n"
            "👇 <i>Use the buttons below to download the latest APK or view the open-source repository:</i>"
        )

    if image_path is None:
        image_path = "/home/kichner/content-funnel/output/app_card.png"

    if button_rows is None:
        button_rows = [
            [{"text": "📲 Download APK (GitHub Releases)", "url": "https://github.com/KRTirtho/spotube/releases/latest"}],
            [
                {"text": "⭐ View Source Code", "url": "https://github.com/KRTirtho/spotube"},
                {"text": "💬 Share Channel", "url": "https://t.me/share/url?url=https://t.me/teleraptor_ig&text=Check%20out%20this%20channel!"}
            ]
        ]

    api_base = f"https://api.telegram.org/bot{bot_token}"
    reply_markup = json.dumps({"inline_keyboard": button_rows})

    if image_path and os.path.exists(image_path):
        url = f"{api_base}/sendPhoto"
        with open(image_path, "rb") as photo_file:
            data = {
                "chat_id": chat_id,
                "caption": caption,
                "parse_mode": "HTML",
                "reply_markup": reply_markup
            }
            response = requests.post(url, data=data, files={"photo": photo_file})
    else:
        url = f"{api_base}/sendMessage"
        payload = {
            "chat_id": chat_id,
            "text": caption,
            "parse_mode": "HTML",
            "reply_markup": reply_markup
        }
        response = requests.post(url, json=payload)

    res_json = response.json()
    if res_json.get("ok"):
        print(f"[✓] Post successfully published to {chat_id}! (Message ID: {res_json['result']['message_id']})")
        return True
    else:
        print(f"[x] Telegram API Error: {res_json.get('description')}")
        return False

if __name__ == "__main__":
    token = sys.argv[1] if len(sys.argv) > 1 else DEFAULT_TOKEN
    chat = sys.argv[2] if len(sys.argv) > 2 else DEFAULT_CHAT_ID
    img = sys.argv[3] if len(sys.argv) > 3 else None
    send_telegram_post(bot_token=token, chat_id=chat, image_path=img)
