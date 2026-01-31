# 📤 UPLOADS FOLDER

## Purpose
Store **user-uploaded files** that are uploaded dynamically via web forms.

## What Goes Here
- User profile photos (after registration)
- Downloadable files (PDFs, eBooks, software)
- Blog post images (uploaded via admin)
- Any user-generated content

## Subfolders
- **profiles/** - User profile photos
- **downloads/** - Downloadable files (PDFs, software, videos, etc.)

## Security Note
⚠️ **IMPORTANT:** This folder contains user-uploaded content. Always:
- Validate file types
- Check file sizes
- Scan for malware
- Use unique filenames
- Never trust user input

## How Files Get Here
1. User uploads via web form
2. PHP controller validates file
3. Controller moves file here
4. Filename saved in database
5. Retrieved and displayed via PHP

## Example Flow
```
User uploads: "mybook.pdf"
PHP saves as:  "download_123_1643723456.pdf"
Database:      downloads.file_path = "download_123_1643723456.pdf"
Display:       base_url('uploads/downloads/' . $file_path)
```

## DO NOT
❌ Manually add files here (use `images/` for that)
❌ Commit this folder to Git (too many files)
❌ Allow direct URL access to sensitive files
❌ Trust filenames from users (rename them!)

## DO
✅ Validate all uploads
✅ Use unique filenames
✅ Store metadata in database
✅ Check permissions before serving files

---
**Note:** This folder is for DYNAMIC content uploaded by users/admin. For static site images, use `images/` folder.
