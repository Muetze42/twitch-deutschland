<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    protected function validateRequest(Request $request)
    {
//        if ($request->header('X-Requested-With') != 'XMLHttpRequest' || $request->input('hash') != md5($request->userAgent())) {
//            abort(403, 'Die Anfrage muss immer vom gleichen Browser stammen');
//        }
        if ($request->header('X-Requested-With') != 'XMLHttpRequest') {
            abort(405);
        }
    }
}
