#!/usr/bin/env python3
"""
MoneyPrinterTurbo Style Video Generator
Recreates MoneyPrinterTurbo's cinematic B-roll collage style:
- Full-bleed 9:16 vertical video scenes with continuous Ken Burns camera zoom
- MoneyPrinterTurbo official royalty-free lofi BGM (output000.mp3)
- Center-screen bold stroke kinetic typography subtitles
- 1080x1920 @ 30fps H.264 MP4 output
"""

import os
import sys
import math
import subprocess
from PIL import Image, ImageDraw, ImageFont, ImageFilter

WIDTH = 1080
HEIGHT = 1920
FPS = 30
AUDIO_FILE = "/tmp/mpt_mixed_audio.wav"
OUTPUT_MP4 = "/home/kichner/content-funnel/mpt_style_short.mp4"
PUBLIC_MP4 = "/domains/teleraptor.parakichner.com/public_html/mpt_style_short.mp4"

FONT_PATH_BOLD = "/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf"

try:
    font_sub_xl = ImageFont.truetype(FONT_PATH_BOLD, 72)
    font_sub_lg = ImageFont.truetype(FONT_PATH_BOLD, 58)
    font_tag = ImageFont.truetype(FONT_PATH_BOLD, 36)
except:
    font_sub_xl = ImageFont.load_default()
    font_sub_lg = font_sub_xl
    font_tag = font_sub_xl

# Kinetic Subtitle Segments (MoneyPrinterTurbo / Hormozi Fast Bursts)
# (start_t, end_t, text_line1, text_line2, active_idx)
MPT_SUBTITLES = [
    (0.0, 1.2, "STOP", "SCROLLING!", 1),
    (1.2, 3.2, "IF YOU OWN AN", "ANDROID PHONE...", 1),
    (3.2, 5.2, "YOUR PHONE WASTES", "40% BATTERY!", 1),
    (5.2, 7.8, "ON SECRET", "BACKGROUND TELEMETRY", 1),
    (7.8, 10.2, "HERE IS THE SECRET:", "TELERAPTOR GEM", 1),
    (10.2, 13.5, "IT BLOCKS 140+", "HIDDEN TRACKERS!", 0),
    (13.5, 15.5, "DOUBLES YOUR", "STANDBY BATTERY!", 1),
    (15.5, 17.5, "AND BOOSTS SPEED", "WITH ZERO ROOT!", 1),
    (17.5, 20.2, "100% FREE", "ZERO ADS!", 1),
    (20.2, 23.0, "TAP LINK IN BIO", "OR JOIN TELEGRAM", 0),
    (23.0, 25.45, "GET SAFE APK NOW!", "👇 LINK IN BIO 👇", 1),
]

def draw_text_with_outline(draw, pos, text, font, fill_color, outline_color=(0, 0, 0), stroke_w=7, anchor="mm"):
    x, y = pos
    # Draw heavy black outline for high contrast
    for dx in range(-stroke_w, stroke_w + 1, 2):
        for dy in range(-stroke_w, stroke_w + 1, 2):
            if dx*dx + dy*dy <= stroke_w*stroke_w:
                draw.text((x + dx, y + dy), text, fill=outline_color, font=font, anchor=anchor)
    # Draw main fill text
    draw.text((x, y), text, fill=fill_color, font=font, anchor=anchor)

def create_scene_background(scene_id, t_rel, scene_duration):
    # Generates a rich, textured cinematic 9:16 background with continuous camera motion
    zoom = 1.0 + 0.12 * (t_rel / scene_duration)
    img = Image.new("RGB", (WIDTH, HEIGHT), (10, 12, 18))
    draw = ImageDraw.Draw(img)

    cx, cy = WIDTH // 2, HEIGHT // 2

    if scene_id == 1:
        # Scene 1: Dark Cyberpunk Tech Void / Amber Warning Alert
        # Radial cyber grid lines
        for r in range(150, 1200, 160):
            r_z = int(r * zoom)
            draw.ellipse((cx - r_z, cy - r_z, cx + r_z, cy + r_z), outline=(30, 25, 45), width=2)
        # Drifting light beams
        for i in range(12):
            ang = (i * 30 + t_rel * 15) * math.pi / 180
            x2 = cx + int(1000 * math.cos(ang))
            y2 = cy + int(1000 * math.sin(ang))
            draw.line((cx, cy, x2, y2), fill=(45, 20, 35), width=2)
        # Center Warning Shield & Pulsing Amber Glow
        glow_size = int(320 * zoom + 20 * math.sin(t_rel * 6))
        draw.ellipse((cx - glow_size, cy - glow_size - 100, cx + glow_size, cy + glow_size - 100), outline=(255, 60, 60), width=4)
        draw.text((cx, cy - 100), "⚠️", fill=(255, 80, 80), font=font_sub_xl, anchor="mm")
        draw_text_with_outline(draw, (cx, cy - 280), "CRITICAL ALERT", font_tag, fill_color=(255, 75, 75))

    elif scene_id == 2:
        # Scene 2: Matrix Data Leak / Telemetry Radar Scanner
        # Digital grid
        grid_shift = int((t_rel * 80) % 80)
        for y in range(0, HEIGHT, 80):
            draw.line((0, y + grid_shift, WIDTH, y + grid_shift), fill=(15, 30, 50), width=1)
        for x in range(0, WIDTH, 120):
            draw.line((x, 0, x, HEIGHT), fill=(15, 30, 50), width=1)
        # Radar sweep circle
        radar_r = int(450 * zoom)
        draw.ellipse((cx - radar_r, cy - radar_r, cx + radar_r, cy + radar_r), outline=(0, 190, 255), width=3)
        # Radar scan line
        ang = (t_rel * 180) * math.pi / 180
        rx = cx + int(radar_r * math.cos(ang))
        ry = cy + int(radar_r * math.sin(ang))
        draw.line((cx, cy, rx, ry), fill=(0, 240, 255), width=3)
        # Drain stats
        draw_text_with_outline(draw, (cx, cy - 260), "📡 140+ HIDDEN TRACKERS ACTIVE", font_tag, fill_color=(255, 90, 90))

    elif scene_id == 3:
        # Scene 3: Emerald Cyber Shield / App Gem Activation
        # Neon green hex grid / rings
        for r in range(180, 1000, 140):
            r_z = int(r * zoom)
            pulse_w = int(2 + 2 * math.sin(t_rel * 4 + r))
            draw.ellipse((cx - r_z, cy - r_z, cx + r_z, cy + r_z), outline=(0, 200, 120), width=pulse_w)
        # Shield Emblem
        draw_text_with_outline(draw, (cx, cy - 280), "🛡️ TELERAPTOR OPEN SOURCE", font_tag, fill_color=(0, 240, 160))
        draw_text_with_outline(draw, (cx, cy - 140), "⚡ FREEZES ALL TELEMETRY", font_tag, fill_color=(255, 255, 255))

    elif scene_id == 4:
        # Scene 4: Turbo RAM & Battery Speed Booster
        # Converging speed lines (Anime / TikTok action style)
        for i in range(24):
            ang = (i * 15 + t_rel * 25) * math.pi / 180
            r1 = int(250 * zoom)
            r2 = 1200
            x1 = cx + int(r1 * math.cos(ang))
            y1 = cy + int(r1 * math.sin(ang))
            x2 = cx + int(r2 * math.cos(ang))
            y2 = cy + int(r2 * math.sin(ang))
            draw.line((x1, y1, x2, y2), fill=(0, 160, 240), width=3)
        draw_text_with_outline(draw, (cx, cy - 280), "🚀 +1.8 GB RAM FREED", font_tag, fill_color=(255, 230, 0))
        draw_text_with_outline(draw, (cx, cy - 140), "🔋 STANDBY BATTERY 2X", font_tag, fill_color=(0, 240, 220))

    else:
        # Scene 5: High-Converting Holographic Portal (Bio Link CTA)
        # Glowing portal rings
        for r in range(120, 800, 90):
            r_z = int(r * zoom)
            draw.ellipse((cx - r_z, cy - r_z - 80, cx + r_z, cy + r_z - 80), outline=(130, 60, 255), width=3)
        draw_text_with_outline(draw, (cx, cy - 300), "📲 GET IT 100% FREE", font_tag, fill_color=(160, 120, 255))
        draw_text_with_outline(draw, (cx, cy - 160), "👉 teleraptor.parakichner.com", font_tag, fill_color=(0, 240, 255))

    # Dark Vignette on Top and Bottom edges for contrast
    vignette = Image.new("L", (WIDTH, HEIGHT), 0)
    v_draw = ImageDraw.Draw(vignette)
    for y in range(250):
        alpha = int(220 * (1 - y / 250))
        v_draw.line((0, y, WIDTH, y), fill=alpha)
        v_draw.line((0, HEIGHT - 1 - y, WIDTH, HEIGHT - 1 - y), fill=alpha)
    
    # Darken center band slightly for subtitles
    for y in range(cy - 180, cy + 180):
        dist = abs(y - cy) / 180
        alpha = int(140 * (1 - dist))
        v_draw.line((0, y, WIDTH, y), fill=alpha)
        
    vignette_color = Image.new("RGB", (WIDTH, HEIGHT), (0, 0, 0))
    img.paste(vignette_color, (0, 0), vignette)

    return img

def render_frame(t, frame_idx, total_frames):
    # Scene Breakdown
    # Scene 1: 0.0 - 5.0s
    # Scene 2: 5.0 - 9.5s
    # Scene 3: 9.5 - 15.0s
    # Scene 4: 15.0 - 19.5s
    # Scene 5: 19.5 - 25.45s
    if t < 5.0:
        scene_id = 1
        t_rel = t
        dur = 5.0
    elif t < 9.5:
        scene_id = 2
        t_rel = t - 5.0
        dur = 4.5
    elif t < 15.0:
        scene_id = 3
        t_rel = t - 9.5
        dur = 5.5
    elif t < 19.5:
        scene_id = 4
        t_rel = t - 15.0
        dur = 4.5
    else:
        scene_id = 5
        t_rel = t - 19.5
        dur = 5.95

    img = create_scene_background(scene_id, t_rel, dur)
    draw = ImageDraw.Draw(img)

    # Top Floating Watermark / Channel Handle (MoneyPrinterTurbo Style)
    draw_text_with_outline(draw, (WIDTH // 2, 140), "TELERAPTOR • @teleraptor_ig", font_tag, fill_color=(200, 215, 240), stroke_w=4)

    # ==========================================
    # MONEYPRINTERTURBO CENTER KINETIC SUBTITLES
    # ==========================================
    active_sub = None
    for start, end, l1, l2, active_idx in MPT_SUBTITLES:
        if start <= t < end:
            active_sub = (l1, l2, active_idx)
            break

    if active_sub:
        l1, l2, active_idx = active_sub
        cx, cy = WIDTH // 2, HEIGHT // 2 + 60
        
        # Color: Active word in neon yellow, inactive in pure white
        col1 = (255, 235, 0) if active_idx == 0 else (255, 255, 255)
        col2 = (255, 235, 0) if active_idx == 1 else (255, 255, 255)

        # Draw lines centered with heavy stroke
        draw_text_with_outline(draw, (cx, cy - 45), l1, font_sub_xl, fill_color=col1, stroke_w=8)
        draw_text_with_outline(draw, (cx, cy + 45), l2, font_sub_xl, fill_color=col2, stroke_w=8)

    # Bottom Callout (always visible for viewer action)
    draw_text_with_outline(draw, (WIDTH // 2, HEIGHT - 180), "🔗 Link in Bio • @teleraptor_ig", font_tag, fill_color=(255, 255, 255), stroke_w=5)

    return img

def main():
    print(f"🎬 Starting MoneyPrinterTurbo-style render: {WIDTH}x{HEIGHT} @ {FPS}fps, Duration: 25.45s")
    total_duration = 25.45
    total_frames = int(total_duration * FPS)

    ffmpeg_cmd = [
        "ffmpeg", "-y",
        "-f", "rawvideo",
        "-vcodec", "rawvideo",
        "-s", f"{WIDTH}x{HEIGHT}",
        "-pix_fmt", "rgb24",
        "-r", str(FPS),
        "-i", "-",
        "-i", AUDIO_FILE,
        "-c:v", "libx264",
        "-preset", "veryfast",
        "-crf", "21",
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
            print(f"MPT Rendering: {pct}% ({frame_idx}/{total_frames} frames, t={t:.1f}s)")

    process.stdin.close()
    process.wait()

    if os.path.exists(OUTPUT_MP4):
        size_mb = os.path.getsize(OUTPUT_MP4) / (1024 * 1024)
        print(f"🎉 Success! MoneyPrinterTurbo-style video saved to {OUTPUT_MP4} ({size_mb:.2f} MB)")
        # Copy to artifacts directory
        artifact_mp4 = "/home/kichner/.gemini/antigravity-cli/brain/48eba62c-3ff1-494d-ab1c-c5bc913aa662/mpt_style_short.mp4"
        try:
            subprocess.run(["cp", OUTPUT_MP4, artifact_mp4], check=True)
            print(f"📁 Copied to artifacts directory: {artifact_mp4}")
        except Exception as e:
            print(f"Note: {e}")
    else:
        print("❌ Error: Video file was not created.")

if __name__ == "__main__":
    main()
