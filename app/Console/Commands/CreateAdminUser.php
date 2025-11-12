<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'app:create-admin-user {email} {password?}';
    protected $description = 'Create or update an admin user. Usage: php artisan app:create-admin-user email@example.com [password]';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address.');
            return 1;
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->is_admin = true;
            if ($password) {
                $user->password = Hash::make($password);
            }
            $user->name = $user->name ?: 'Admin';
            $user->save();

            $this->info("Admin user updated: {$email}");
            if ($password) {
                $this->info('Password updated.');
            } else {
                $this->info('Password left unchanged.');
            }

            return 0;
        }

        // Create a new user. If no password provided, generate a secure random one but do not print it to logs.
        if (! $password) {
            try {
                $password = bin2hex(random_bytes(8));
            } catch (\Exception $e) {
                // fallback
                $password = substr(md5(uniqid('', true)), 0, 16);
            }
            $showPassword = false;
        } else {
            $showPassword = true;
        }

        $user = User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);

        $this->info("Admin user created: {$email}");
        if ($showPassword) {
            $this->info("Password: {$password}");
        } else {
            $this->info('A random password was generated and stored. Please reset it via the application or provide a password as the second argument.');
        }

        return 0;
    }
}
