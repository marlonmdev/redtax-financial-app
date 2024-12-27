<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() == 0) {
            $adminConfig = config('admin.default_admin');

            User::create([
                'avatar' => null,
                'name' => 'REDtax Admin',
                'email' => $adminConfig['email'],
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make($adminConfig['password']),
                'active' => 1,
                'role_id' => null
            ]);
        }
    }
}
