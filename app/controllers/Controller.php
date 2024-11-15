<?php

namespace App\Controllers;

require_once './app/core/View.php';
require_once './app/core/Database.php';

use App\Core\Response;
use App\Core\View;
use App\Core\Database;

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

