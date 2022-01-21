<?php

namespace Database\Seeders;

use App\Models\Broadcaster;
use Illuminate\Database\Seeder;

class BroadcasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = storage_path('app/broadcasters.json');

        if (file_exists($file)) {
            $broadcasters = json_decode(file_get_contents($file), true);

            foreach ($broadcasters as $twitchId => $data) {
                Broadcaster::firstOrCreate(
                    ['twitch_id' => $twitchId],
                    [
                        'name'         => $data['name'],
                        'display_name' => $data['display_name'],
                        //'logo'         => $data['logo'],

                    ]
                );
            }
        }
    }
}
