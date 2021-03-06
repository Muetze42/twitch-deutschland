<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * @param Video $video
     * @param Request $request
     * @return array
     */
    public function show(Video $video, Request $request): array
    {
        $this->validateRequest($request);

        $broadcasters = $video->broadcasters();
        $seed = $this->getSeed($request, $broadcasters);

        return [
            'broadcasters' => $broadcasters
                ->inRandomOrder($seed)
                ->paginate(20)
                ->appends(['seed' => $seed])
                ->withQueryString()
                ->through(fn($broadcaster) => [
                    'name' => $broadcaster->display_name,
                    'logo' => $broadcaster->getFirstMediaUrl('logo'),
                ]),
            'seed' => $seed,
        ];
    }

    protected function getSeed(Request $request, $broadcasters)
    {
        $seed = $request->input('seed');
        if (!$seed) {
            $seed = mt_rand(1, $broadcasters->count());
        }

        return $seed;
    }
}
