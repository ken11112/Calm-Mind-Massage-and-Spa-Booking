<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Album;
use App\Models\Gallery;

$album = Album::firstOrCreate(['name' => 'Test Album'], ['slug' => \Illuminate\Support\Str::slug('Test Album')]);
$g = Gallery::create([
    'title' => 'Test Photo',
    'album_id' => $album->id,
    'description' => 'Created by script',
    'is_active' => 1,
    'sort_order' => (Gallery::max('sort_order') ?? 0) + 1,
    'image_path' => 'gallery/test-sample.jpg'
]);

echo "Created gallery id={$g->id} album_id={$g->album_id}\n";
