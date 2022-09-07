<?php

namespace App\User\Application\Command;

class CreateUser
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
     * @var string
     */
    public $email;
    /**
     * @var integer
     */
    public $phoneNumber;
    /**
     * @var string
     */
    public $password;
}
