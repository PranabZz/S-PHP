<?php

namespace App\Core;
use App\Core\Response; // PHP cannot support multiple class inhertance so we use namepsace 

class GuestMiddleware
{
    public function handel()
    {
        if ($this->auth()) {
            return "/";
        }
    }

    public function auth()
    {
        if (!$_SESSION['user']) {
            return false;
        }
        return true;
    }
}

