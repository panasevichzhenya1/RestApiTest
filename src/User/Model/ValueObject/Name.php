<?php

namespace App\User\Model\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Name
{
    /**
     * @ORM\Column(name="first_name", type="string", length=100, nullable=false)
     */
    private $firstName;
    /**
     * @ORM\Column(name="last_name", type="string", length=100, nullable=false)
     */
    private $lastName;

    public function __construct(?string $firstName, ?string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }
}
