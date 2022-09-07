<?php

namespace App\User\Application\Command;

use App\User\Model\User;

class ChangePassword
{
    /**
     * @var string
     */
    public $password;
    /**
     * @var User
     */
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
