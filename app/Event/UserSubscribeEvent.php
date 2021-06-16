<?php

namespace App\Event;

class UserSubscribeEvent
{

    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }
}

