<?php
// Script to list all users
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::all();
echo "Current users in database:\n";
echo "==========================\n";
foreach ($users as $user) {
    $adminStatus = $user->is_admin ? 'YES' : 'NO';
    echo "ID: {$user->id} | Email: {$user->email} | Name: {$user->name} | Admin: {$adminStatus}\n";
}
