<?php
// Script to create a new admin user
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'admin@calmmind.com';
$name = 'Admin';
$password = 'admin123';

$user = User::where('email', $email)->first();
if ($user) {
    echo "User with email {$email} already exists. Updating admin status...\n";
    $user->is_admin = true;
    $user->save();
    echo "Admin status updated to true.\n";
    exit(0);
}

$user = User::create([
    'name' => $name,
    'email' => $email,
    'password' => Hash::make($password),
    'is_admin' => true,
]);

echo "Admin user created successfully!\n";
echo "Email: {$email}\n";
echo "Password: {$password}\n";
echo "User ID: {$user->id}\n";
