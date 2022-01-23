<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Video;
use Inertia\Inertia;
use Inertia\Response;

class ChannelController extends Controller
{
    protected int $order;

    /**
     * @return Response
     */
    public function index(): Response
    {
        $this->order = 0;
        $channels = Channel::with('latestVideo')
            ->get()
            ->sortByDesc('latestVideo.published_at')
            ->mapWithKeys(function ($channel) {
                return [$this->order++ => [
                    'id'           => $channel->id,
                    'name'         => $channel->name,
                    'youtube_id'   => $channel->youtube_id,
                    'count'        => number_format($channel->videos->count(), 0, ',', '.'),
                    'published'    => $channel->latestVideo->published_at->format('d.m.Y H:i:s'),
                    'latest'       => $channel->latestVideo->youtube_id,
                    'broadcasters' => number_format($this->getBroadcastersCount($channel), 0, ',', '.'),
                ]
                ];
            });

        return Inertia::render('Channel/Index', [
            'channels' => $channels,
        ]);
    }

    protected function getBroadcastersCount($channel)
    {
        $channel->load(['videos.broadcasters' => function ($query) use (&$broadcasters) {
            $broadcasters = $query->get()->unique();
        }]);

        return $broadcasters->count();
    }
}
