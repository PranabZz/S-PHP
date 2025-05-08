<?php


namespace Sphp\Core;

use Sphp\Core\Database;

class Controller
{
    public $env;
    public $db;

    public function __construct()
    {
        $this->env = require('../app/config/config.php');
        $this->db = new Database($this->env);
    }
}

