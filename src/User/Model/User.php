<?php

namespace App\User\Model;

use App\User\Model\ValueObject\Email;
use App\User\Model\ValueObject\Name;
use App\User\Model\ValueObject\Password;
use App\User\Model\ValueObject\PhoneNumber;
use App\User\Model\ValueObject\UserId;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User implements UserInterface {

    /**
     * @ORM\Embedded(class="App\User\Model\ValueObject\UserId", columnPrefix=false)
     * @var UserId
     */
    private $id;

    /**
     * @ORM\Embedded(class="App\User\Model\ValueObject\Email", columnPrefix=false)
     * @var Email
     */
    private $email;

    /**
     * @ORM\Embedded(class="App\User\Model\ValueObject\Name", columnPrefix=false)
     * @var Name
     */
    private $name;

    /**
     * @ORM\Embedded(class="App\User\Model\ValueObject\PhoneNumber", columnPrefix=false)
     * @var PhoneNumber
     */
    private $phoneNumber;

    /**
     * @ORM\Embedded(class="App\User\Model\ValueObject\Password", columnPrefix=false)
     * @var Password
     */
    private $password;

    public function __construct(Email $email, Name $name, PhoneNumber $phoneNumber, Password $password)
    {
        $this->id = new UserId();
        $this->email = $email;
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
        $this->password = $password;
    }

    public function getFirstName(): string
    {
        return $this->name->getFirstName();
    }

    public function getLastName(): string
    {
        return $this->name->getLastName();
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function getEmail() : string
    {
        return $this->email->address();
    }

    public function rename(Name $name) : void
    {
        $this->name = $name;
    }

    public function changePhoneNumber(PhoneNumber $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function changePassword(Password $password): void {
        $this->password = $password;
    }

    public function getUserIdentifier(): string
    {

    }

    public function getRoles()
    {

    }

    public function getPassword()
    {

    }

    public function getSalt()
    {

    }

    public function eraseCredentials()
    {

    }

    public function __call($name, $arguments)
    {

    }

    public function getUsername()
    {

    }
}
