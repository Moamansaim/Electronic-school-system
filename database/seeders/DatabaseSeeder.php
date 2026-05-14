<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $user =  User::create([
            'school_id' => 'mmmm409875283',
            'password' => Hash::make('123456789'),
        ]);

        Teacher::create([
            'first_name' => 'مؤمن',
            'father_name' => 'أيسر',
            'grandfather_name' => 'حسين',
            'family_name' => 'صيام',
            'date_of_birth' => '2003-12-20',
            'national_id' => '409875283',
            'city' => 'غزة',
            'district' => 'معسكر جباليا',
            'street' => 'شارع الهوجا',
            'user_id' => $user->id,
        ]);


        // SchoolID: yisn321654789
        // Password: X7sTk7YyNkrf
    }
}