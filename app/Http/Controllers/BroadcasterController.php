<?php

namespace App\Http\Controllers;

use App\Models\Broadcaster;
use Inertia\Inertia;
use Inertia\Response;
use Request;

class BroadcasterController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        $broadcasters = Broadcaster::query()
            ->whereHas('videos')
            ->ordered()
            ->when(Request::input('search'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('display_name', 'like', '%'.$search.'%');
                });
            })
            ->paginate($this->limit)
            ->withQueryString()
            ->through(fn($broadcaster) => [
                'id'    => $broadcaster->id,
                'first' => $broadcaster->videos()->first() ? $broadcaster->videos()->first()->youtube_id : null,
                'name'  => e($broadcaster->display_name),
                'logo'  => $broadcaster->logo,
                'count' => number_format($broadcaster->video_count, 0, ',', '.'),
            ]);

        return Inertia::render('Broadcaster/Index', [
            'broadcasters' => $broadcasters,
        ]);
    }
}
