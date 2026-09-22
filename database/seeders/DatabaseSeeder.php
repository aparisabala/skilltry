<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Webpatser\Uuid\Uuid;
use DB;
//vpx_imports
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        DB::table('app_data')->insert([
            'created_at' =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' =>  Carbon::now()->format('Y-m-d H:i:s'),

        ]);

        //vpx_attach
        DB::table('admin_user_roles')->insert([
            'name' => 'Super Admin',
            'code' => "SA",
            'can_select' => "no",
            'created_at' =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('admin_users')->insert([
            'uuid' => (string) Uuid::generate(4),
            'admin_type' => "Super Admin",
            'name' => "Webmaster",
            'mobile_number' => "01727572841",
            'email' => "admin@admin.com",
            'password' => Hash::make('123456789'),
            'is_secret' => 'yes',
            'admin_user_role_id' => 1,
            'created_at' =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
