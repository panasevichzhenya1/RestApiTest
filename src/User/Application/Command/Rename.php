<?php

namespace App\User\Application\Command;

use App\User\Model\User;

class Rename
{
    /**
     * @var string
     */
    public $firstName;
    /**
     * @var string
     */
    public $lastName;
    /**
     * @var User
     */
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
