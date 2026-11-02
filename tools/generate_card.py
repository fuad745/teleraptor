import os
import math
from PIL import Image, ImageDraw, ImageFont, ImageFilter

FONT_BOLD = "/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf"
FONT_REGULAR = "/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf"

def create_gradient(width, height, top_color, bottom_color):
    """Creates a vertical gradient background."""
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
    """Draws a rounded rectangle."""
    draw.rounded_rectangle(bbox, radius=radius, fill=fill, outline=outline, width=width)

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
DEFAULT_OUTPUT_DIR = os.path.join(BASE_DIR, "output")

def generate_app_card(
    app_name="Spotube",
    category="Open-Source Audio & Music",
    rating="4.9 ★★★★★",
    highlights=[
        "No Ads, No Account Required",
        "Free High-Bitrate Offline Downloads",
        "Uses Spotify & YouTube API under the hood",
    ],
    telegram_handle="@teleraptor_ig",
    output_path=None
):
    if output_path is None:
        output_path = os.path.join(DEFAULT_OUTPUT_DIR, "app_card.png")
    W, H = 1080, 1920
    # Background gradient: Deep dark slate / navy
    img = create_gradient(W, H, (10, 14, 26, 255), (18, 24, 42, 255))
    draw = ImageDraw.Draw(img)

    # Accent glow circle
    glow = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    glow_draw = ImageDraw.Draw(glow)
    glow_draw.ellipse((W//2 - 400, 300, W//2 + 400, 1100), fill=(46, 204, 113, 35))
    glow = glow.filter(ImageFilter.GaussianBlur(120))
    img = Image.alpha_composite(img, glow)
    draw = ImageDraw.Draw(img)

    # Fonts
    badge_font = ImageFont.truetype(FONT_BOLD, 36)
    title_font = ImageFont.truetype(FONT_BOLD, 74)
    subtitle_font = ImageFont.truetype(FONT_REGULAR, 40)
    rating_font = ImageFont.truetype(FONT_BOLD, 42)
    feature_font = ImageFont.truetype(FONT_REGULAR, 42)
    cta_title_font = ImageFont.truetype(FONT_BOLD, 48)
    cta_handle_font = ImageFont.truetype(FONT_BOLD, 54)

    # 1. Top Category Pill / Badge
    badge_text = "🔥 HIDDEN ANDROID GEM"
    badge_w = 520
    badge_h = 75
    badge_x = (W - badge_w) // 2
    badge_y = 160
    draw_rounded_rect(draw, (badge_x, badge_y, badge_x + badge_w, badge_y + badge_h), radius=38, fill=(39, 174, 96, 50), outline=(46, 204, 113, 220), width=3)
    draw.text((badge_x + 40, badge_y + 15), badge_text, fill=(46, 204, 113, 255), font=badge_font)

    # 2. Main Glass Card Container
    card_x0, card_y0 = 80, 290
    card_x1, card_y1 = W - 80, 1420
    draw_rounded_rect(draw, (card_x0, card_y0, card_x1, card_y1), radius=45, fill=(23, 32, 54, 210), outline=(60, 80, 120, 150), width=2)

    # App Icon Placeholder / Mockup
    icon_size = 200
    icon_x = (W - icon_size) // 2
    icon_y = card_y0 + 70
    draw_rounded_rect(draw, (icon_x, icon_y, icon_x + icon_size, icon_y + icon_size), radius=42, fill=(46, 204, 113, 255))
    # Draw simple musical note or android symbol inside
    draw.text((icon_x + 55, icon_y + 35), "⚡", fill=(255, 255, 255, 255), font=ImageFont.truetype(FONT_BOLD, 100))

    # App Title
    title_y = icon_y + icon_size + 40
    title_bbox = draw.textbbox((0, 0), app_name, font=title_font)
    title_w = title_bbox[2] - title_bbox[0]
    draw.text(((W - title_w) // 2, title_y), app_name, fill=(255, 255, 255, 255), font=title_font)

    # Category & Rating
    sub_y = title_y + 90
    sub_bbox = draw.textbbox((0, 0), category, font=subtitle_font)
    sub_w = sub_bbox[2] - sub_bbox[0]
    draw.text(((W - sub_w) // 2, sub_y), category, fill=(160, 175, 205, 255), font=subtitle_font)

    rate_y = sub_y + 60
    rate_bbox = draw.textbbox((0, 0), rating, font=rating_font)
    rate_w = rate_bbox[2] - rate_bbox[0]
    draw.text(((W - rate_w) // 2, rate_y), rating, fill=(241, 196, 15, 255), font=rating_font)

    # Divider line
    div_y = rate_y + 80
    draw.line((card_x0 + 60, div_y, card_x1 - 60, div_y), fill=(50, 70, 105, 200), width=2)

    # Highlight Features Section
    feat_start_y = div_y + 50
    for idx, feat in enumerate(highlights):
        fy = feat_start_y + (idx * 110)
        # Checkmark bullet
        draw_rounded_rect(draw, (card_x0 + 60, fy + 5, card_x0 + 110, fy + 55), radius=25, fill=(46, 204, 113, 50))
        draw.text((card_x0 + 73, fy + 8), "✓", fill=(46, 204, 113, 255), font=ImageFont.truetype(FONT_BOLD, 38))
        draw.text((card_x0 + 130, fy + 8), feat, fill=(230, 235, 245, 255), font=feature_font)

    # 3. Bottom Viral CTA Box (The Funnel to Telegram)
    cta_x0, cta_y0 = 80, 1470
    cta_x1, cta_y1 = W - 80, 1780
    draw_rounded_rect(draw, (cta_x0, cta_y0, cta_x1, cta_y1), radius=38, fill=(0, 136, 204, 255), outline=(0, 180, 255, 255), width=3)

    cta_text = "📲 DOWNLOAD APK & SETUP GUIDE"
    c_bbox = draw.textbbox((0, 0), cta_text, font=cta_title_font)
    draw.text(((W - (c_bbox[2] - c_bbox[0])) // 2, cta_y0 + 40), cta_text, fill=(255, 255, 255, 255), font=cta_title_font)

    tg_sub = f"👉 Join Telegram: {telegram_handle}"
    tg_bbox = draw.textbbox((0, 0), tg_sub, font=cta_handle_font)
    draw.text(((W - (tg_bbox[2] - tg_bbox[0])) // 2, cta_y0 + 120), tg_sub, fill=(255, 255, 120, 255), font=cta_handle_font)

    footer_hint = "(Link in Bio / Comments)"
    f_bbox = draw.textbbox((0, 0), footer_hint, font=subtitle_font)
    draw.text(((W - (f_bbox[2] - f_bbox[0])) // 2, cta_y0 + 200), footer_hint, fill=(220, 240, 255, 255), font=subtitle_font)

    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    img.save(output_path, "PNG")
    print(f"Generated App Card at: {output_path}")

def generate_job_card(
    role="Senior Flutter Developer",
    company="Fintech Global Inc.",
    salary="$4,500 - $6,000 / month",
    perks=[
        "100% Remote (Anywhere in the World)",
        "Paid in USD / Crypto / Wire",
        "Flexible Hours + Equipment Stipend",
    ],
    telegram_handle="@teleraptor_ig",
    output_path=None
):
    if output_path is None:
        output_path = os.path.join(DEFAULT_OUTPUT_DIR, "job_card.png")
    W, H = 1080, 1920
    img = create_gradient(W, H, (15, 12, 30, 255), (25, 20, 48, 255))
    draw = ImageDraw.Draw(img)

    # Accent glow
    glow = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    glow_draw = ImageDraw.Draw(glow)
    glow_draw.ellipse((W//2 - 400, 300, W//2 + 400, 1100), fill=(155, 89, 182, 40))
    glow = glow.filter(ImageFilter.GaussianBlur(130))
    img = Image.alpha_composite(img, glow)
    draw = ImageDraw.Draw(img)

    badge_font = ImageFont.truetype(FONT_BOLD, 36)
    title_font = ImageFont.truetype(FONT_BOLD, 68)
    subtitle_font = ImageFont.truetype(FONT_REGULAR, 40)
    salary_font = ImageFont.truetype(FONT_BOLD, 54)
    feature_font = ImageFont.truetype(FONT_REGULAR, 42)
    cta_title_font = ImageFont.truetype(FONT_BOLD, 46)
    cta_handle_font = ImageFont.truetype(FONT_BOLD, 52)

    # Badge
    badge_text = "🌍 100% REMOTE GLOBAL HIRING"
    badge_w = 640
    badge_h = 75
    badge_x = (W - badge_w) // 2
    badge_y = 160
    draw_rounded_rect(draw, (badge_x, badge_y, badge_x + badge_w, badge_y + badge_h), radius=38, fill=(142, 68, 173, 60), outline=(175, 122, 197, 220), width=3)
    draw.text((badge_x + 40, badge_y + 15), badge_text, fill=(215, 189, 226, 255), font=badge_font)

    # Main Card
    card_x0, card_y0 = 80, 290
    card_x1, card_y1 = W - 80, 1420
    draw_rounded_rect(draw, (card_x0, card_y0, card_x1, card_y1), radius=45, fill=(28, 22, 50, 220), outline=(75, 60, 110, 160), width=2)

    # Icon
    icon_size = 180
    icon_x = (W - icon_size) // 2
    icon_y = card_y0 + 70
    draw_rounded_rect(draw, (icon_x, icon_y, icon_x + icon_size, icon_y + icon_size), radius=40, fill=(155, 89, 182, 255))
    draw.text((icon_x + 48, icon_y + 25), "💼", fill=(255, 255, 255, 255), font=ImageFont.truetype(FONT_BOLD, 95))

    # Title
    title_y = icon_y + icon_size + 40
    t_bbox = draw.textbbox((0, 0), role, font=title_font)
    draw.text(((W - (t_bbox[2] - t_bbox[0])) // 2, title_y), role, fill=(255, 255, 255, 255), font=title_font)

    # Company
    c_y = title_y + 85
    c_bbox = draw.textbbox((0, 0), company, font=subtitle_font)
    draw.text(((W - (c_bbox[2] - c_bbox[0])) // 2, c_y), company, fill=(195, 180, 220, 255), font=subtitle_font)

    # Salary Banner
    sal_y = c_y + 70
    sal_box_w = 750
    sal_box_h = 95
    sal_box_x = (W - sal_box_w) // 2
    draw_rounded_rect(draw, (sal_box_x, sal_y, sal_box_x + sal_box_w, sal_y + sal_box_h), radius=28, fill=(39, 174, 96, 50), outline=(46, 204, 113, 200), width=2)
    s_bbox = draw.textbbox((0, 0), salary, font=salary_font)
    draw.text(((W - (s_bbox[2] - s_bbox[0])) // 2, sal_y + 16), salary, fill=(46, 204, 113, 255), font=salary_font)

    # Divider
    div_y = sal_y + 130
    draw.line((card_x0 + 60, div_y, card_x1 - 60, div_y), fill=(65, 50, 95, 200), width=2)

    # Perks
    p_start_y = div_y + 45
    for idx, perk in enumerate(perks):
        py = p_start_y + (idx * 110)
        draw_rounded_rect(draw, (card_x0 + 60, py + 5, card_x0 + 110, py + 55), radius=25, fill=(155, 89, 182, 60))
        draw.text((card_x0 + 73, py + 8), "✓", fill=(215, 189, 226, 255), font=ImageFont.truetype(FONT_BOLD, 38))
        draw.text((card_x0 + 130, py + 8), perk, fill=(240, 235, 250, 255), font=feature_font)

    # CTA Box
    cta_x0, cta_y0 = 80, 1470
    cta_x1, cta_y1 = W - 80, 1780
    draw_rounded_rect(draw, (cta_x0, cta_y0, cta_x1, cta_y1), radius=38, fill=(0, 136, 204, 255), outline=(0, 180, 255, 255), width=3)

    cta_text = "🚀 DIRECT APPLICATION LINK"
    c_bbox = draw.textbbox((0, 0), cta_text, font=cta_title_font)
    draw.text(((W - (c_bbox[2] - c_bbox[0])) // 2, cta_y0 + 40), cta_text, fill=(255, 255, 255, 255), font=cta_title_font)

    tg_sub = f"👉 Apply via Telegram: {telegram_handle}"
    tg_bbox = draw.textbbox((0, 0), tg_sub, font=cta_handle_font)
    draw.text(((W - (tg_bbox[2] - tg_bbox[0])) // 2, cta_y0 + 120), tg_sub, fill=(255, 255, 120, 255), font=cta_handle_font)

    footer_hint = "(Link in Bio / Free to Apply)"
    f_bbox = draw.textbbox((0, 0), footer_hint, font=subtitle_font)
    draw.text(((W - (f_bbox[2] - f_bbox[0])) // 2, cta_y0 + 200), footer_hint, fill=(220, 240, 255, 255), font=subtitle_font)

    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    img.save(output_path, "PNG")
    print(f"Generated Job Card at: {output_path}")

if __name__ == "__main__":
    generate_app_card()
    generate_job_card()
