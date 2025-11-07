# Gallery static images

Drop image files you want displayed on the public gallery page in this folder.

Supported formats: .jpg .jpeg .png .gif

Filenames will be displayed in alphabetical order after the DB-backed gallery items.

How to add the attached photos from the chat:
1. Save the images you received in this chat to your machine.
2. Copy them into this folder: `public/images/gallery` (e.g. `gallery1.jpg`, `gallery2.jpg`, ...).
3. Refresh the gallery page at `/gallery` and you should see them appear.

Notes:
- These images are served directly from the `public` folder, so no additional `php artisan storage:link` is required for them.
- If you prefer the images to be managed via the admin area, upload them using the admin Gallery UI instead; those will be stored in `storage/app/public/gallery` and shown from the database.
