import os
from PIL import Image, ImageDraw, ImageFont, ImageFilter

FONT_BOLD = "/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf"
FONT_REGULAR = "/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf"

def create_gradient(width, height, top_color, bottom_color):
    base = Image.new("RGBA", (width, height), top_color)
    top = Image.new("RGBA", (width, height), bottom_color)
    mask = Image.new("L", (width, height))
    mask_data = []
    for y in range(height):
        mask_data.extend([int(255 * (y / height))] * width)
    mask.putdata(mask_data)
    base.paste(top, (0, 0), mask)
    return base

def draw_rounded_rect(draw, bbox, radius, fill, outline=None, width=1):
    draw.rounded_rectangle(bbox, radius=radius, fill=fill, outline=outline, width=width)

def generate_tiktok_carousel(
    hook_text="ANDROID APPS\nTHAT FEEL ILLEGAL\nTO KNOW IN 2026",
    app_name="Spotube",
    category="Open-Source Audio & Music",
    rating="4.9 ★★★★★",
    highlights=[
        "No Ads, No Account Required",
        "Free High-Bitrate Offline Downloads",
        "Uses Spotify & YouTube API under the hood",
    ],
    telegram_handle="@teleraptor_ig",
    output_dir="/home/kichner/content-funnel/output"
):
    os.makedirs(output_dir, exist_ok=True)
    W, H = 1080, 1920

    title_font = ImageFont.truetype(FONT_BOLD, 74)
    subtitle_font = ImageFont.truetype(FONT_REGULAR, 42)
    feature_font = ImageFont.truetype(FONT_REGULAR, 42)
    rating_font = ImageFont.truetype(FONT_BOLD, 42)
    badge_font = ImageFont.truetype(FONT_BOLD, 38)
    cta_font = ImageFont.truetype(FONT_BOLD, 52)
    mega_font = ImageFont.truetype(FONT_BOLD, 82)

    # ==========================================
    # SLIDE 1: THE VIRAL HOOK (High Click-Through)
    # ==========================================
    s1 = create_gradient(W, H, (10, 14, 26, 255), (20, 30, 55, 255))
    glow = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    ImageDraw.Draw(glow).ellipse((W//2 - 350, 400, W//2 + 350, 1100), fill=(231, 76, 60, 45))
    glow = glow.filter(ImageFilter.GaussianBlur(140))
    s1 = Image.alpha_composite(s1, glow)
    d1 = ImageDraw.Draw(s1)

    # Top Pill
    draw_rounded_rect(d1, (240, 320, 840, 400), radius=40, fill=(231, 76, 60, 40), outline=(231, 76, 60, 220), width=3)
    d1.text((290, 338), "🚨 WARNING: DO NOT SKIP", fill=(255, 100, 100, 255), font=badge_font)

    # Big Hook Text
    lines = hook_text.split("\n")
    y_text = 560
    for line in lines:
        bbox = d1.textbbox((0, 0), line, font=mega_font)
        d1.text(((W - (bbox[2] - bbox[0])) // 2, y_text), line, fill=(255, 255, 255, 255), font=mega_font)
        y_text += 105

    # Swipe CTA
    draw_rounded_rect(d1, (200, 1450, 880, 1560), radius=55, fill=(0, 136, 204, 255), outline=(0, 200, 255, 255), width=3)
    d1.text((280, 1480), "👉 SWIPE TO SEE #1", fill=(255, 255, 255, 255), font=cta_font)

    slide1_path = os.path.join(output_dir, "tiktok_slide1_hook.png")
    s1.save(slide1_path, "PNG")

    # ==========================================
    # SLIDE 2: THE VALUE / APP SHOWCASE
    # ==========================================
    s2 = create_gradient(W, H, (10, 14, 26, 255), (18, 24, 42, 255))
    glow2 = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    ImageDraw.Draw(glow2).ellipse((W//2 - 400, 300, W//2 + 400, 1100), fill=(46, 204, 113, 35))
    glow2 = glow2.filter(ImageFilter.GaussianBlur(120))
    s2 = Image.alpha_composite(s2, glow2)
    d2 = ImageDraw.Draw(s2)

    draw_rounded_rect(d2, (280, 160, 800, 235), radius=38, fill=(39, 174, 96, 50), outline=(46, 204, 113, 220), width=3)
    d2.text((320, 175), "🔥 APP #1: THE BEST GEM", fill=(46, 204, 113, 255), font=badge_font)

    card_x0, card_y0 = 80, 290
    card_x1, card_y1 = W - 80, 1420
    draw_rounded_rect(d2, (card_x0, card_y0, card_x1, card_y1), radius=45, fill=(23, 32, 54, 210), outline=(60, 80, 120, 150), width=2)

    icon_size = 200
    icon_x = (W - icon_size) // 2
    icon_y = card_y0 + 70
    draw_rounded_rect(d2, (icon_x, icon_y, icon_x + icon_size, icon_y + icon_size), radius=42, fill=(46, 204, 113, 255))
    d2.text((icon_x + 55, icon_y + 35), "⚡", fill=(255, 255, 255, 255), font=ImageFont.truetype(FONT_BOLD, 100))

    title_y = icon_y + icon_size + 40
    t_bbox = d2.textbbox((0, 0), app_name, font=title_font)
    d2.text(((W - (t_bbox[2] - t_bbox[0])) // 2, title_y), app_name, fill=(255, 255, 255, 255), font=title_font)

    sub_y = title_y + 90
    s_bbox = d2.textbbox((0, 0), category, font=subtitle_font)
    d2.text(((W - (s_bbox[2] - s_bbox[0])) // 2, sub_y), category, fill=(160, 175, 205, 255), font=subtitle_font)

    rate_y = sub_y + 60
    r_bbox = d2.textbbox((0, 0), rating, font=rating_font)
    d2.text(((W - (r_bbox[2] - r_bbox[0])) // 2, rate_y), rating, fill=(241, 196, 15, 255), font=rating_font)

    div_y = rate_y + 80
    d2.line((card_x0 + 60, div_y, card_x1 - 60, div_y), fill=(50, 70, 105, 200), width=2)

    feat_start_y = div_y + 50
    for idx, feat in enumerate(highlights):
        fy = feat_start_y + (idx * 110)
        draw_rounded_rect(d2, (card_x0 + 60, fy + 5, card_x0 + 110, fy + 55), radius=25, fill=(46, 204, 113, 50))
        d2.text((card_x0 + 73, fy + 8), "✓", fill=(46, 204, 113, 255), font=ImageFont.truetype(FONT_BOLD, 38))
        d2.text((card_x0 + 130, fy + 8), feat, fill=(230, 235, 245, 255), font=feature_font)

    draw_rounded_rect(d2, (80, 1500, W - 80, 1680), radius=45, fill=(0, 136, 204, 255))
    d2.text((190, 1530), "👉 SWIPE FOR DOWNLOAD LINK", fill=(255, 255, 255, 255), font=cta_font)

    slide2_path = os.path.join(output_dir, "tiktok_slide2_value.png")
    s2.save(slide2_path, "PNG")

    # ==========================================
    # SLIDE 3: THE CONVERSION FUNNEL (Send to Telegram)
    # ==========================================
    s3 = create_gradient(W, H, (10, 18, 32, 255), (15, 30, 58, 255))
    glow3 = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    ImageDraw.Draw(glow3).ellipse((W//2 - 400, 400, W//2 + 400, 1200), fill=(0, 136, 204, 50))
    glow3 = glow3.filter(ImageFilter.GaussianBlur(130))
    s3 = Image.alpha_composite(s3, glow3)
    d3 = ImageDraw.Draw(s3)

    # Big Telegram Icon
    tg_icon_size = 200
    tg_x = (W - tg_icon_size) // 2
    tg_y = 220
    draw_rounded_rect(d3, (tg_x, tg_y, tg_x + tg_icon_size, tg_y + tg_icon_size), radius=50, fill=(0, 136, 204, 255))
    d3.text((tg_x + 40, tg_y + 30), "✈️", fill=(255, 255, 255, 255), font=ImageFont.truetype(FONT_BOLD, 105))

    # Instructions Box
    d3.text((180, 480), "HOW TO DOWNLOAD:", fill=(255, 255, 255, 255), font=ImageFont.truetype(FONT_BOLD, 68))

    steps = [
        "1. Open your Telegram app",
        f"2. Search: {telegram_handle}",
        "3. Click Join & Get the APK!",
    ]
    box_y0 = 600
    draw_rounded_rect(d3, (80, box_y0, W - 80, box_y0 + 520), radius=45, fill=(23, 35, 60, 220), outline=(0, 136, 204, 180), width=3)

    for idx, step in enumerate(steps):
        sy = box_y0 + 70 + (idx * 140)
        draw_rounded_rect(d3, (140, sy, 210, sy + 70), radius=35, fill=(0, 136, 204, 255))
        d3.text((163, sy + 10), str(idx + 1), fill=(255, 255, 255, 255), font=ImageFont.truetype(FONT_BOLD, 42))
        d3.text((240, sy + 12), step, fill=(240, 245, 255, 255), font=ImageFont.truetype(FONT_BOLD, 44))

    # Big Direct Link Banner
    draw_rounded_rect(d3, (80, 1280, W - 80, 1580), radius=45, fill=(39, 174, 96, 255), outline=(46, 204, 113, 255), width=3)
    d3.text((260, 1330), "🔗 DIRECT LINK IN BIO", fill=(255, 255, 255, 255), font=ImageFont.truetype(FONT_BOLD, 54))
    d3.text((310, 1420), f"Join: {telegram_handle}", fill=(255, 255, 120, 255), font=ImageFont.truetype(FONT_BOLD, 52))

    slide3_path = os.path.join(output_dir, "tiktok_slide3_funnel.png")
    s3.save(slide3_path, "PNG")

    print("[✓] Generated 3 TikTok Slides:")
    print("    1.", slide1_path)
    print("    2.", slide2_path)
    print("    3.", slide3_path)
    return [slide1_path, slide2_path, slide3_path]

if __name__ == "__main__":
    generate_tiktok_carousel()
