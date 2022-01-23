<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Browser;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected int $limit;

    public function __construct()
    {
        try {
            $this->limit = Browser::isDesktop() ? 20 : 8;
        } catch (Exception $exception) {
            $this->limit = 20;
        }
    }
}
