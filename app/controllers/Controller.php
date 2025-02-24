<?php

namespace App\Controllers;

use App\Core\Database;
use App\Services\JwtAuthService;

class Controller
{
    public $env;
    public $db;

    public function __construct()
    {
        $this->env = require('./app/config/config.php');
        $this->db = new Database($this->env);


    }
}

