<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'app:create-admin-user {email?} {password?}';
    protected $description = 'Create a new admin user';

    public function handle()
    {
        $email = $this->argument('email') ?? 'admin@calmmind.com';
        $password = $this->argument('password') ?? 'admin123';

        $user = User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);

        $this->info('Admin user created successfully!');
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
    }
}
