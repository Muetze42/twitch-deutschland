<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate([
            'twitch_id' => 279904718,
            'name'      => 'MuetzeOfficial',
            'is_admin'  => true,
        ]);
    }
}
