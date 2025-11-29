<?php

require 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create admin user
$adminEmail = 'admin@example.com';
$adminPassword = 'password';

$existing = User::where('email', $adminEmail)->first();

if ($existing) {
    echo "✅ Admin user already exists: {$adminEmail}\n";
} else {
    User::create([
        'name' => 'Admin',
        'email' => $adminEmail,
        'password' => Hash::make($adminPassword),
        'is_admin' => true,
        'email_verified_at' => now(),
    ]);
    echo "✅ Admin user created successfully!\n";
    echo "   Email: {$adminEmail}\n";
    echo "   Password: {$adminPassword}\n";
}

echo "\n✨ You can now login at: http://localhost:8000/admin\n";
?>
