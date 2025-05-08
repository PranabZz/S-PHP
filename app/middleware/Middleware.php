<?php

namespace App\Middleware;
use Sphp\Core\Response; 
use Sphp\Services\Auth;// PHP cannot support multiple class inhertance so we use namepsace 

class Middleware
{
    public function handle()
    {
        return Auth::check();
    }
}

