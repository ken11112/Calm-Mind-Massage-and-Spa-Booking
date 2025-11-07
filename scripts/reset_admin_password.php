<?php
// One-off script to reset admin password using Laravel's Hash facade
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'admin@calmmind.com';
$password = 'admin123';

$user = User::where('email', $email)->first();
if (! $user) {
    echo "Admin user not found\n";
    exit(1);
}

$user->password = Hash::make($password);
$user->save();

echo "Admin password for {$email} reset to '{$password}' (hashed).\n";
