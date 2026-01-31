# 📁 IMAGES FOLDER

## Purpose
Store your **static images** that are part of the website design.

## What Goes Here
- Your personal profile photos
- Project screenshots
- Blog featured images
- Service icons
- Logos and branding
- Default placeholder images

## Subfolders
- **profile/** - Your profile photos
- **projects/** - Project screenshots and thumbnails
- **blog/** - Blog post featured images
- **services/** - Service icons and images

## How to Add Images
1. Copy your images to this folder
2. Organize into subfolders
3. Reference in code: `base_url('images/profile/my-photo.jpg')`

## Example
```
images/
├── profile/
│   └── my-photo.jpg          ← Your profile picture
├── projects/
│   ├── ecommerce.jpg         ← Project screenshots
│   └── blog-system.png
├── blog/
│   └── tutorial1.jpg         ← Blog images
└── logo.png                  ← Site logo
```

## URL Access
```
File: public/images/profile/my-photo.jpg
URL:  http://localhost/portfolio/images/profile/my-photo.jpg
```

---
**Note:** These are YOUR images that you manually add. For user-uploaded files, use the `uploads/` folder instead.
