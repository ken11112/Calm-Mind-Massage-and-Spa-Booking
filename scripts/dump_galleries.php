<?php
// scripts/dump_galleries.php
// Bootstraps the Laravel app and prints first 20 galleries as JSON

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Gallery;

$rows = Gallery::with('album')->orderBy('id')->take(20)->get()->map(function ($g) {
    return [
        'id' => $g->id,
        'title' => $g->title,
        'album_id' => $g->album_id,
        'album' => $g->album->name ?? null,
        'image_path' => $g->image_path,
    ];
})->toArray();

echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
