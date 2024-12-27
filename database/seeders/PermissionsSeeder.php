<?php

namespace Database\Seeders;

use App\Enums\PermissionType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PermissionType::cases() as $permissionType) {
            DB::table('permissions')->updateOrInsert(
                ['permission_name' => $permissionType->value],
                ['description' => $permissionType->value . ' permission']
            );
        }
    }
}
