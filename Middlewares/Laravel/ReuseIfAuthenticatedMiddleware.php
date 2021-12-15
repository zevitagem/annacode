<?php

namespace App\Libraries\Annacode\Middlewares\Laravel;

use App\Libraries\Annacode\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Libraries\Annacode\Services\AuthorizationService;

class ReuseIfAuthenticatedMiddleware
{

    public function handle(Request $request, \Closure $next, ...$guards)
    {
        if (!Helper::isOutSourcedAccess()) {
            return $next($request);
        }

        if (!Auth::check()) {
            return $next($request);
        }

        $service = new AuthorizationService();
        $user    = Auth::user();

        $authenticatedData = $service->getTempAuth(
            $user, 1//$_POST['slug']
        );

        return Redirect::to($_GET['url_callback'].'?'.$authenticatedData['params']);
    }
}