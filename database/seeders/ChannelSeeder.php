<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    protected array $channels = [
        'Twitch Deutschland'           => 'UCKv1XY1hVOj2VB99ZL7KlZw',
        'TwitchClipsGermany'           => 'UCnuMwZoIxPK-ilnemcMOUiQ',
        'deutsche Twitch Clips & mehr' => 'UCzAc0sMdPAOrnlptQI3MXTw',
        'DieseGuteClips'               => 'UCkv1RZuHDhFp_UDmsTl1dYg',
        'FLEXX Twitch Clips'           => 'UCymtY4ROxFSAiTCXUQ8WEIg',
        'Pyke'                         => 'UCS9wMLAdHvvv7vpIpclohsA',
        'YourHighlightClipTV'          => 'UCEalc8ggKZn4Prxt49sgY_A',
        'Twitch Highlights 2K Germany' => 'UCwGX5rcgmZyX2r4m9vFgjkQ',
    ];

    protected array $notIdle = [
        'UCKv1XY1hVOj2VB99ZL7KlZw',
        'UCzAc0sMdPAOrnlptQI3MXTw',
        'UCkv1RZuHDhFp_UDmsTl1dYg',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->channels as $channelName => $channelId) {
            Channel::firstOrCreate([
                'name'       => $channelName,
                'youtube_id' => $channelId,
                'idle'       => !in_array($channelId, $this->notIdle),
            ]);
        }
    }
}
