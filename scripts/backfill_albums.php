<?php
// scripts/backfill_albums.php
// Backfill `album` column on galleries where album is null or empty

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Gallery;

$rows = Gallery::whereNull('album')->orWhere('album', '')->get();
$updated = 0;
$sample = [];
foreach ($rows as $g) {
    $parts = preg_split('/\s*-\s*/', $g->title);
    $albumName = trim($parts[0] ?? '') ?: 'General';
    $g->album = $albumName;
    $g->save();
    $updated++;
    if (count($sample) < 10) {
        $sample[] = ['id' => $g->id, 'title' => $g->title, 'album' => $g->album, 'image_path' => $g->image_path];
    }
}

echo json_encode(['updated' => $updated, 'sample' => $sample], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
