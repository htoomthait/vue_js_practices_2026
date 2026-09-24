<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        // Generate 30 fake contact records
        for ($i = 0; $i < 30; $i++) {
            DB::table('contacts')->insert([
                'name' => $faker->firstName . " " . $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'contact_no' => $faker->phoneNumber,
                'designation' => $faker->jobTitle,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
