<?php

namespace App\User\Model\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

/**
 * @ORM\Embeddable()
 */
class PhoneNumber
{
    /**
    * @ORM\Column(name="phone_number", type="string", length=15, nullable=true)
    */
    private $phoneNumber;

    public function __construct(string $phoneNumber)
    {
        $this->validate($phoneNumber);
        $this->phoneNumber = $phoneNumber;
    }

    private function validate(string $phoneNumber) : bool
    {
        if (!preg_match('/^[0-9+]*$/', $phoneNumber)){
            throw new InvalidArgumentException('not valid phone number');
        }

        return true;
    }
}
