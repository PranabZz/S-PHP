<?php

namespace App\Middleware;
use App\Core\Response; // PHP cannot support multiple class inhertance so we use namepsace 

class Middleware
{
    public function handel()
    {
        if($_SESSION["user_token"]){
            return true;
        }else{
            return false;
        }
    }
}

