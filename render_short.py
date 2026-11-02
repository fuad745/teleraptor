#!/usr/bin/env python3
"""
Teleraptor Automated Short-Form Video Generator
Generates high-retention 1080x1920 (9:16) MP4 short videos with synchronized captions,
cinematic glassmorphism UI, and Gemini AI voiceover.
"""

import os
import sys
import math
import subprocess
from PIL import Image, ImageDraw, ImageFont

WIDTH = 1080
HEIGHT = 1920
FPS = 30
AUDIO_FILE = "/tmp/final_mixed_audio.wav"
OUTPUT_MP4 = "/home/kichner/content-funnel/sample_short.mp4"
PUBLIC_MP4 = "/domains/teleraptor.parakichner.com/public_html/sample_short.mp4"

# Timestamps & Subtitle Segments
# Total duration: 25.45s (763 frames)
SUBTITLES = [
    # Act 1: The Hook (0.0 - 7.8s)
    (0.0, 1.8, ["Stop", "scrolling!"], 0),
    (1.8, 4.5, ["If you have an", "Android phone..."], 1),
    (4.5, 7.8, ["Your phone is wasting 40% battery", "on secret telemetry."], 1),
    
    # Act 2: The Value / App Reveal (7.8 - 17.5s)
    (7.8, 10.2, ["Here is the secret:", "Download TELERAPTOR."], 0),
    (10.2, 13.5, ["It freezes hidden trackers", "and doubles your battery."], 1),
    (13.5, 17.5, ["Speed up your phone with", "ZERO ROOT required!"], 1),
    
    # Act 3: Funnel CTA (17.5 - 25.45s)
    (17.5, 20.2, ["100% Free with", "ZERO ADS."], 0),
    (20.2, 23.0, ["Tap the link in bio", "or join our Telegram."], 0),
    (23.0, 25.45, ["Get the safe APK right now!", "👇 Link in Bio 👇"], 1),
]

# Load Fonts
FONT_PATH_BOLD = "/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf"
FONT_PATH_REG = "/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf"

def get_font(path, size):
    try:
        return ImageFont.truetype(path, size)
    except:
        return ImageFont.load_default()

font_header = get_font(FONT_PATH_BOLD, 36)
font_title_lg = get_font(FONT_PATH_BOLD, 64)
font_title_md = get_font(FONT_PATH_BOLD, 48)
font_sub = get_font(FONT_PATH_BOLD, 42)
font_caption = get_font(FONT_PATH_BOLD, 44)
font_body = get_font(FONT_PATH_REG, 34)
font_stat_num = get_font(FONT_PATH_BOLD, 72)
font_badge = get_font(FONT_PATH_BOLD, 28)

def draw_rounded_rect(draw, bbox, radius, fill, outline=None, width=1):
    draw.rounded_rectangle(bbox, radius=radius, fill=fill, outline=outline, width=width)

def render_frame(t, frame_idx, total_frames):
    img = Image.new("RGB", (WIDTH, HEIGHT), (7, 10, 18))
    draw = ImageDraw.Draw(img)
    
    # Background subtle radial/ambient glow
    # Glowing orb 1 (top right)
    orb1_y = int(400 + 40 * math.sin(t * 1.2))
    # Glowing orb 2 (bottom left)
    orb2_y = int(1400 + 50 * math.cos(t * 1.5))
    
    # Top Bar (always present)
    # Background pill
    draw_rounded_rect(draw, (60, 80, WIDTH - 60, 160), 40, (14, 20, 36), outline=(30, 42, 70), width=2)
    
    # Flashing REC badge
    rec_pulse = int(127 + 127 * math.sin(t * 6))
    draw.ellipse((95, 110, 120, 135), fill=(255, 60, 60, rec_pulse))
    draw.text((135, 105), "VIRAL DISCOVERY", fill=(255, 90, 90), font=font_badge)
    draw.text((450, 105), "TELERAPTOR • ANDROID GEM", fill=(200, 215, 245), font=font_badge)
    
    # Verified Check
    draw_rounded_rect(draw, (WIDTH - 180, 98, WIDTH - 90, 142), 22, (0, 190, 120))
    draw.text((WIDTH - 165, 107), "SAFE", fill=(255, 255, 255), font=font_badge)

    # ==========================================
    # SCENE 1: THE HOOK (0.0s - 7.8s)
    # ==========================================
    if t < 7.8:
        # Warning Hero Badge
        warn_pulse = int(220 + 35 * math.sin(t * 4))
        draw_rounded_rect(draw, (180, 260, WIDTH - 180, 350), 30, (40, 18, 20), outline=(255, 70, 70), width=3)
        draw.text((WIDTH // 2, 305), "⚠️ CRITICAL ANDROID ALERT", fill=(255, 80, 80), font=font_title_md, anchor="mm")
        
        # Big Main Card (Glassmorphism)
        draw_rounded_rect(draw, (80, 400, WIDTH - 80, 1280), 40, (16, 22, 38), outline=(50, 70, 115), width=2)
        
        # Battery Graphic Card
        draw_rounded_rect(draw, (140, 460, WIDTH - 140, 760), 30, (26, 12, 16), outline=(230, 50, 70), width=2)
        
        # Battery percentage
        drain_pct = int(40 + 5 * math.sin(t * 3))
        draw.text((WIDTH // 2, 540), f"-{drain_pct}%", fill=(255, 60, 70), font=font_stat_num, anchor="mm")
        draw.text((WIDTH // 2, 620), "BATTERY DRAINED BY SECRET TELEMETRY", fill=(255, 170, 180), font=font_sub, anchor="mm")
        draw.text((WIDTH // 2, 690), "Manufacturer background tracking active right now", fill=(180, 190, 210), font=font_body, anchor="mm")
        
        # Threat breakdown list
        items = [
            ("🚫 140+ Background Trackers Running", (255, 90, 90)),
            ("📡 Continuous Diagnostic Uploads", (255, 150, 80)),
            ("📉 Standby Battery Life Cut in Half", (255, 90, 90)),
            ("⚡ Phone Heats Up in Idle Mode", (255, 180, 90)),
        ]
        
        y_pos = 830
        for text, col in items:
            draw_rounded_rect(draw, (140, y_pos, WIDTH - 140, y_pos + 90), 20, (22, 30, 50), outline=(40, 55, 85), width=1)
            draw.text((180, y_pos + 45), text, fill=col, font=font_sub, anchor="lm")
            y_pos += 110

    # ==========================================
    # SCENE 2: THE VALUE & GEM REVEAL (7.8s - 17.5s)
    # ==========================================
    elif t < 17.5:
        # Solution Hero Badge
        draw_rounded_rect(draw, (160, 260, WIDTH - 160, 350), 30, (10, 38, 28), outline=(0, 230, 140), width=3)
        draw.text((WIDTH // 2, 305), "⚡ THE OPEN SOURCE GEM", fill=(0, 240, 150), font=font_title_md, anchor="mm")
        
        # Big Main Card (Emerald / Cyan Glass)
        draw_rounded_rect(draw, (80, 400, WIDTH - 80, 1280), 40, (14, 26, 38), outline=(0, 190, 140), width=3)
        
        # App Showcase Banner
        draw_rounded_rect(draw, (140, 460, WIDTH - 140, 680), 30, (18, 38, 54), outline=(0, 220, 170), width=2)
        draw.text((WIDTH // 2, 530), "TELERAPTOR OS GEM", fill=(255, 255, 255), font=font_title_lg, anchor="mm")
        
        # Feature Pills
        draw_rounded_rect(draw, (180, 595, 420, 645), 25, (0, 160, 100))
        draw.text((300, 620), "NO ROOT", fill=(255, 255, 255), font=font_badge, anchor="mm")
        
        draw_rounded_rect(draw, (450, 595, 680, 645), 25, (0, 140, 220))
        draw.text((565, 620), "0% ADS", fill=(255, 255, 255), font=font_badge, anchor="mm")
        
        draw_rounded_rect(draw, (710, 595, 900, 645), 25, (160, 60, 240))
        draw.text((805, 620), "FREE APK", fill=(255, 255, 255), font=font_badge, anchor="mm")
        
        # Proven Results Showcase
        results = [
            ("🛡️ Freezes 140+ Telemetry Trackers", "Clean Sandbox", (0, 240, 160)),
            ("🔋 Standby Battery Doubled (2X)", "+4.5 Hours SOT", (0, 220, 255)),
            ("🚀 Frees +1.8 GB RAM Instantly", "Zero Background Lag", (255, 220, 80)),
            ("🔒 100% Private & Open Source", "Audited Code", (200, 160, 255)),
        ]
        
        y_pos = 730
        for title, subtitle, col in results:
            draw_rounded_rect(draw, (140, y_pos, WIDTH - 140, y_pos + 115), 22, (20, 36, 52), outline=(35, 65, 95), width=1)
            draw.text((180, y_pos + 42), title, fill=col, font=font_sub, anchor="lm")
            draw.text((180, y_pos + 82), subtitle, fill=(180, 205, 230), font=font_body, anchor="lm")
            y_pos += 135

    # ==========================================
    # SCENE 3: FUNNEL CTA (17.5s - 25.45s)
    # ==========================================
    else:
        # CTA Hero Badge
        draw_rounded_rect(draw, (140, 260, WIDTH - 140, 350), 30, (20, 25, 60), outline=(80, 140, 255), width=3)
        draw.text((WIDTH // 2, 305), "📲 HOW TO GET IT FREE", fill=(120, 180, 255), font=font_title_md, anchor="mm")
        
        # Big Main Card (Blue / Purple Glow)
        draw_rounded_rect(draw, (80, 400, WIDTH - 80, 1280), 40, (16, 20, 42), outline=(70, 110, 240), width=3)
        
        # Step 1 Card
        draw_rounded_rect(draw, (140, 470, WIDTH - 140, 690), 28, (24, 32, 68), outline=(60, 100, 220), width=2)
        draw_rounded_rect(draw, (170, 500, 240, 570), 35, (60, 110, 255))
        draw.text((205, 535), "1", fill=(255, 255, 255), font=font_title_md, anchor="mm")
        draw.text((270, 535), "TAP LINK IN BIO", fill=(255, 255, 255), font=font_title_md, anchor="lm")
        draw.text((170, 630), "👉 teleraptor.parakichner.com", fill=(100, 210, 255), font=font_sub, anchor="lm")
        
        # Step 2 Card
        draw_rounded_rect(draw, (140, 730, WIDTH - 140, 950), 28, (24, 32, 68), outline=(0, 200, 180), width=2)
        draw_rounded_rect(draw, (170, 760, 240, 830), 35, (0, 190, 150))
        draw.text((205, 795), "2", fill=(255, 255, 255), font=font_title_md, anchor="mm")
        draw.text((270, 795), "JOIN TELEGRAM CHANNEL", fill=(255, 255, 255), font=font_title_md, anchor="lm")
        draw.text((170, 890), "👉 Instant Safe APK: @teleraptor_ig", fill=(0, 240, 180), font=font_sub, anchor="lm")
        
        # Large Pulsing Action Button
        btn_pulse = int(10 * math.sin(t * 8))
        draw_rounded_rect(draw, (140 - btn_pulse, 1010, WIDTH - 140 + btn_pulse, 1160), 35, (0, 130, 255), outline=(130, 220, 255), width=3)
        draw.text((WIDTH // 2, 1085), "👇 TAP BIO LINK NOW 👇", fill=(255, 255, 255), font=font_title_md, anchor="mm")
        draw.text((WIDTH // 2, 1220), "🔒 Verified VirusTotal 0/72 Clean", fill=(160, 210, 240), font=font_body, anchor="mm")

    # ==========================================
    # DYNAMIC DUAL-COLOR SUBTITLES (Hormozi Style)
    # ==========================================
    active_sub = None
    for start, end, lines, highlight_idx in SUBTITLES:
        if start <= t < end:
            active_sub = (lines, highlight_idx)
            break
            
    if active_sub:
        lines, highlight_idx = active_sub
        box_y1 = 1420
        box_y2 = 1600
        
        # Dark pill container with glowing border
        draw_rounded_rect(draw, (80, box_y1, WIDTH - 80, box_y2), 35, (8, 12, 22), outline=(255, 230, 0), width=3)
        
        if len(lines) == 1:
            draw.text((WIDTH // 2, (box_y1 + box_y2) // 2), lines[0], fill=(255, 235, 0), font=font_caption, anchor="mm")
        elif len(lines) >= 2:
            # First line
            col1 = (255, 235, 0) if highlight_idx == 0 else (255, 255, 255)
            # Second line
            col2 = (255, 235, 0) if highlight_idx == 1 else (255, 255, 255)
            
            draw.text((WIDTH // 2, box_y1 + 48), lines[0], fill=col1, font=font_caption, anchor="mm")
            draw.text((WIDTH // 2, box_y1 + 120), lines[1], fill=col2, font=font_caption, anchor="mm")

    # ==========================================
    # FOOTER WATERMARK / BRANDING
    # ==========================================
    draw.text((WIDTH // 2, 1780), "teleraptor.parakichner.com • @teleraptor_ig", fill=(100, 130, 180), font=font_body, anchor="mm")

    return img

def main():
    print(f"🎬 Starting video render: {WIDTH}x{HEIGHT} @ {FPS}fps, Total frames: ~763")
    total_duration = 25.45
    total_frames = int(total_duration * FPS)
    
    # FFmpeg command piping raw RGB frames and muxing audio
    ffmpeg_cmd = [
        "ffmpeg", "-y",
        "-f", "rawvideo",
        "-vcodec", "rawvideo",
        "-s", f"{WIDTH}x{HEIGHT}",
        "-pix_fmt", "rgb24",
        "-r", str(FPS),
        "-i", "-",  # Pipe input for video
        "-i", AUDIO_FILE,  # Voiceover + pad audio
        "-c:v", "libx264",
        "-preset", "veryfast",
        "-crf", "22",
        "-pix_fmt", "yuv420p",
        "-c:a", "aac",
        "-b:a", "192k",
        "-shortest",
        OUTPUT_MP4
    ]
    
    process = subprocess.Popen(ffmpeg_cmd, stdin=subprocess.PIPE, stderr=subprocess.PIPE)
    
    for frame_idx in range(total_frames):
        t = frame_idx / FPS
        frame = render_frame(t, frame_idx, total_frames)
        process.stdin.write(frame.tobytes())
        
        if frame_idx % 75 == 0:
            pct = int((frame_idx / total_frames) * 100)
            print(f"Rendering: {pct}% ({frame_idx}/{total_frames} frames, t={t:.1f}s)")
            
    process.stdin.close()
    process.wait()
    
    if os.path.exists(OUTPUT_MP4):
        size_mb = os.path.getsize(OUTPUT_MP4) / (1024 * 1024)
        print(f"🎉 Success! Video saved to {OUTPUT_MP4} ({size_mb:.2f} MB)")
        # Copy to artifacts directory
        artifact_mp4 = "/home/kichner/.gemini/antigravity-cli/brain/48eba62c-3ff1-494d-ab1c-c5bc913aa662/sample_short.mp4"
        try:
            subprocess.run(["cp", OUTPUT_MP4, artifact_mp4], check=True)
            print(f"📁 Copied to artifacts directory: {artifact_mp4}")
        except Exception as e:
            print(f"Note: {e}")
    else:
        print("❌ Error: Video file was not created.")

if __name__ == "__main__":
    main()
