<?php
// Script to verify admin credentials
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'admin@calmmind.com';
$user = User::where('email', $email)->first();

if ($user) {
    echo "\n✓ Admin user found!\n";
    echo "==================\n";
    echo "User ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Is Admin: " . ($user->is_admin ? 'YES' : 'NO') . "\n";
    echo "Created at: {$user->created_at}\n";
    echo "Updated at: {$user->updated_at}\n";
    echo "\nLogin Credentials:\n";
    echo "==================\n";
    echo "Email: admin@calmmind.com\n";
    echo "Password: admin123\n";
} else {
    echo "Admin user NOT found!\n";
}
