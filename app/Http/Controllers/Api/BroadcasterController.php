<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        if ($request->header('X-Requested-With') != 'XMLHttpRequest' || $request->input('hash') != md5($request->userAgent())) {
            abort(405);
        }

        return $broadcaster->videos()
            ->paginate(15)
            ->withQueryString()
            ->through(fn($video) => [
                'id'      => $video->youtube_id,
                'title'   => $video->title,
                'image'   => $video->getFirstMediaUrl('thumb', 'public'),
                'channel' => $video->channel->name,
            ]);
    }
}
