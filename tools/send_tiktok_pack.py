import os
import requests

TOKEN = "8794122196:AAH2LqFAMhKGJVHTYsFybp1OZtP50dineUE"
CHAT_ID = "@teleraptor_ig"

def send_tiktok_carousel_pack(
    bot_token=TOKEN,
    chat_id=CHAT_ID,
    slides_dir="/home/kichner/content-funnel/output"
):
    """
    Sends the 3 TikTok/Instagram carousel slides directly to Telegram
    with the exact ready-to-copy caption and hashtags.
    """
    slide1 = os.path.join(slides_dir, "tiktok_slide1_hook.png")
    slide2 = os.path.join(slides_dir, "tiktok_slide2_value.png")
    slide3 = os.path.join(slides_dir, "tiktok_slide3_funnel.png")

    caption_for_social = (
        "🔥 <b>TIKTOK & INSTAGRAM CAROUSEL PACK READY!</b>\n\n"
        "<b>How to post on TikTok / Instagram:</b>\n"
        "1. Save these 3 images to your gallery.\n"
        "2. Open TikTok ➔ Tap '+' ➔ Select 'Photo Mode' ➔ Choose Slides 1, 2, and 3.\n"
        "3. Add a trending sound from the TikTok sound library.\n"
        "4. Copy and paste the viral caption below:\n\n"
        "<code>Stop paying for music streaming 🤫 Spotube gives you ad-free high-bitrate offline music completely free and open-source!\n\n"
        "📥 Full APK & setup guide is in our Telegram!\n"
        "👉 Search '@teleraptor_ig' on Telegram or click the link in bio!\n\n"
        "#android #androidapps #techhacks #apk #usefulapps #techtok #flutter #opensource #technology</code>"
    )

    # 1. Send the instruction & copyable caption
    requests.post(
        f"https://api.telegram.org/bot{bot_token}/sendMessage",
        json={"chat_id": chat_id, "text": caption_for_social, "parse_mode": "HTML"}
    )

    # 2. Send the 3 media files as an album/photos
    for slide_path in [slide1, slide2, slide3]:
        if os.path.exists(slide_path):
            with open(slide_path, "rb") as f:
                requests.post(
                    f"https://api.telegram.org/bot{bot_token}/sendPhoto",
                    data={"chat_id": chat_id},
                    files={"photo": f}
                )

    print("[✓] Delivered TikTok Carousel Pack to Telegram!")

if __name__ == "__main__":
    send_tiktok_carousel_pack()
