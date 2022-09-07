<?php

namespace App\User\Model\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

/**
 * @ORM\Embeddable()
 */
class Email
{
    /**
     * @ORM\Column(name="email", type="string", length=100)
     */
    private $email;

    public function __construct(string $email)
    {
        $this->validate($email);
        $this->email = $email;
    }

    public function address(): string
    {
        return $this->email;
    }

    private function validate(string $email) : bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('not valid email');
        }

        return true;
    }
}
