<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * @param Video $video
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function show(Video $video, Request $request): LengthAwarePaginator
    {
        if ($request->header('X-Requested-With') != 'XMLHttpRequest' || $request->input('hash') != md5($request->userAgent())) {
            abort(405);
        }

        return $video->broadcasters()
            ->inRandomOrder()
            ->paginate(20)
            ->withQueryString()
            ->through(fn($broadcaster) => [
                'name' => $broadcaster->display_name,
                'logo' => $broadcaster->getFirstMediaUrl('logo'),
            ]);
    }
}
