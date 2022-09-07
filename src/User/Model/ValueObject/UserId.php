<?php

namespace App\User\Model\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Embeddable()
 */
class UserId
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="id", type="uuid", unique=true)
     */
    private $id;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function id(): Uuid
    {
        return $this->id;
    }
}
