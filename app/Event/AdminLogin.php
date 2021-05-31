<?php

namespace App\Event;


class AdminLogin
{

    public $adminId;

    public function __construct($adminId)
    {
        $this->adminId = $adminId;
    }
}