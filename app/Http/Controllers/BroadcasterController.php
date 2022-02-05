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
                        ->orWhere('display_name', 'like', '%'.$search.'%')
                        ->orWhere('old_names', 'like', '%'.$search.'%');
                });
            })
            ->paginate($this->limit)
            ->withQueryString()
            ->through(fn($broadcaster) => [
                'id'    => $broadcaster->id,
                'first' => $broadcaster->videos()->first() ? $broadcaster->videos()->first()->youtube_id : null,
                'name'  => $broadcaster->display_name,
                'logo'  => $broadcaster->getFirstMediaUrl('logo'),
                'count' => number_format($broadcaster->videos_count, 0, ',', '.'),
            ]);

        return Inertia::render('Broadcaster/Index', [
            'broadcasters' => $broadcasters,
        ]);
    }
}
