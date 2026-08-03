from PIL import Image

src_img = '/Users/faisal/.gemini/antigravity-ide/brain/f08a2242-4460-4f2b-891b-3f5e41e8b54a/logo_no_text_1785754689499.png'
img = Image.open(src_img)

# Save 192x192
img_192 = img.resize((192, 192), Image.Resampling.LANCZOS)
img_192.save('public/icon-192x192.png')

# Save 512x512
img_512 = img.resize((512, 512), Image.Resampling.LANCZOS)
img_512.save('public/icon-512x512.png')

# Also save a generic logo.png in public for use in welcome/login blades
img.save('public/logo.png')

print("Icons without text generated successfully.")
