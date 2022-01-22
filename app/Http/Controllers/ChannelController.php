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
            ->mapWithKeys(function ($item) {
                return [$this->order++ => [
                    'id'         => $item->id,
                    'name'       => $item->name,
                    'youtube_id' => $item->youtube_id,
                    'count'      => number_format($item->videos->count(), 0, ',', '.'),
                    'published'  => $item->latestVideo->published_at->format('d.m.Y H:i:s'),
                    'latest'     => $item->latestVideo->youtube_id,
                ]
                ];
            });

        return Inertia::render('Channel/Index', [
            'channels' => $channels,
        ]);
    }
}
