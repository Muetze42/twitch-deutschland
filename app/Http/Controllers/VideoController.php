<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Inertia\Inertia;
use Inertia\Response;
use Request;

class VideoController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        $videos = Video::query()->ordered()
            ->whereHas('broadcasters')
            ->when(Request::input('search'), function ($query, $search) {
                $query->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhereHas('broadcasters', function ($query) use ($search) {
                        return $query->where('name', 'like', '%'.$search.'%')->orWhere('display_name', 'like', '%'.$search.'%');
                    });
            })
            ->paginate($this->limit)
            ->withQueryString()
            ->through(fn($video) => [
                'id'         => $video->id,
                'title'      => $video->title,
                'youtube_id' => $video->youtube_id,
                'channel'    => $video->channel->name,
                'published'  => $video->published_at->format('d.m.Y H:i'),
                'image'      => $video->getFirstMediaUrl('thumb', 'public')
            ]);

        return Inertia::render('Video/Index', [
            'videos'  => $videos,
        ]);
    }
}
