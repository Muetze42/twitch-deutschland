<?php

namespace App\Http\Middleware;

use App\Models\Broadcaster;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;
use Browser;
use Exception;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app.layout';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param Request $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param Request $request
     * @return array
     */
    public function share(Request $request): array
    {
        try {
            $browser = Browser::isDesktop() ? 'desktop' : 'mobile';
        } catch (Exception $exception) {
            $browser = 'mobile';
        }

        $route = Route::currentRouteName();
        $routePart = explode('.', $route)[0];
//        if(!$routePart && $request->input('search')) {
//            $routePart = $request->input('search');
//        }
        $pageTitle = $routePart ? ucfirst($routePart).' « '.config('app.name') : '';
        view()->share('pageTitle', $pageTitle);

        $pageRobots = 'noindex,nofollow';
        $metaDescriptions = [
            'videos.index' => lastAnd(implode(', ', array_map(function ($channel) { return '„'.$channel.'“'; }, Channel::all()->pluck('name')->toArray()))). ' Clips Compilations',
            'streams.index' => 'Finde Twitch Clips Compilations von '
                .lastAnd(implode(', ', array_map(function ($broadcaster) { return '„'.$broadcaster.'“'; }, Broadcaster::whereHas('videos')->ordered()->limit(6)->get()->pluck('display_name')->toArray()))).' & vielen anderen',
        ];
        if (!empty($metaDescriptions[$route])) {
            $pageRobots = 'index,follow';
            view()->share('metaDescription', $metaDescriptions[$route]);
        }
        view()->share('pageRobots', $pageRobots);

        return array_merge(parent::share($request), [
            'pageTitle'  => $pageTitle,
            'device'     => $browser,
            'agent'      => md5($request->userAgent()),
            'csrf_token' => csrf_token(),
        ]);
    }
}
