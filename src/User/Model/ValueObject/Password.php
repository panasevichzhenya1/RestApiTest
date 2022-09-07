<?php

namespace App\User\Model\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Password
{
    /**
     * @ORM\Column(name="password", type="string")
     */
    private $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }
}
