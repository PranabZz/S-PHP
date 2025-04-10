<?php

namespace App\Models;
use App\Core\Models;

/* TODO */

class User extends Models
{
    public function __construct()
    {
        $this->table = "users";
        $this->fillables = ['username', 'password'];
        parent::__construct();
    }

    public function getUsername($username)
    {
        $result = $this->select(['username'] , ['username' => $username]);
        return $result[0]['username'] ?? null;
    }
}