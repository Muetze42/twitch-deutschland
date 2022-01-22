<?php

namespace App\Http\Controllers\Api;

use App\Models\Channel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    /**
     * @param Channel $channel
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function show(Channel $channel, Request $request): LengthAwarePaginator
    {
        $this->validateRequest($request);

        return $channel->videos()
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
