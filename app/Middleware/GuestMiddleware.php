<?php

namespace App\Middleware;

use Sphp\Core\Response; 
use Sphp\Services\Auth;// PHP doesn't support multiple inheritance, so we use namespaces to organize and share code

class GuestMiddleware
{
    public function handle()
    {
        if (Auth::check()) {
            return "/";
        }

        return true;
    }
}
