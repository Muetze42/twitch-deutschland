<?php

namespace App\Http\Controllers\Api;

use App\Models\Broadcaster;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class BroadcasterController extends Controller
{
    /**
     * @param Broadcaster $broadcaster
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function show(Broadcaster $broadcaster, Request $request): LengthAwarePaginator
    {
        $this->validateRequest($request);

        return $broadcaster->videos()
            ->paginate(15)
            ->withQueryString()
            ->through(fn($video) => [
                'id'        => $video->youtube_id,
                'title'     => $video->title,
                'image'     => $video->getFirstMediaUrl('thumb', 'public'),
                'published' => $video->published_at->format('d.m.Y H:i:s'),
                'channel'   => $video->channel->name,
            ]);
    }
}
