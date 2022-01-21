<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
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

        return array_merge(parent::share($request), [
            'appName'    => config('app.name'),
            'device'     => $browser,
            'agent'      => md5($request->userAgent()),
            'csrf_token' => csrf_token(),
        ]);
    }
}
