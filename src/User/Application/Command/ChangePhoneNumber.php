<?php

namespace App\User\Application\Command;

use App\User\Model\User;

class ChangePhoneNumber
{
    /**
     * @var string
     */
    public $phoneNumber;
    /**
     * @var User
     */
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
