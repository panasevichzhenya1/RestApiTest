<?php

namespace App\User\Application\Command;

class Login
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $password;

    public function email(): string
    {
        return $this->email;
    }
}
