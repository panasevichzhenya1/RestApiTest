<?php

namespace App\User\Application;

use App\User\Application\Command\ChangePassword;
use App\User\Application\Command\ChangePhoneNumber;
use App\User\Application\Command\CreateUser;
use App\User\Application\Command\RemoveUser;
use App\User\Application\Command\Rename;
use App\User\Infrastructure\Repository\UserRepository;
use App\User\Model\User;
use App\User\Model\ValueObject\Email;
use App\User\Model\ValueObject\Name;
use App\User\Model\ValueObject\Password;
use App\User\Model\ValueObject\PhoneNumber;

class UserHandler
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(CreateUser $createUser): void {
        $user = new User(
            new Email($createUser->email),
            new Name($createUser->firstName, $createUser->lastName),
            new PhoneNumber($createUser->phoneNumber),
            new Password($createUser->password)
        );
        $this->repository->save($user);
    }

    public function remove(RemoveUser $removeUserCommand): void {
        $user = $this->repository->findById($removeUserCommand->id);
        $this->repository->remove($user);
    }

    public function rename(Rename $renameCommand): void {
        $name = new Name($renameCommand->firstName, $renameCommand->lastName);
        $user = $renameCommand->user;
        $user->rename($name);
        $this->repository->save($user);
    }

    public function changePhoneNumber(ChangePhoneNumber $changeNumberCommand): void {
        $phoneNumber = new PhoneNumber($changeNumberCommand->phoneNumber);
        $user = $changeNumberCommand->user;
        $user->changePhoneNumber($phoneNumber);
        $this->repository->save($user);
    }

    public function changePassword(ChangePassword $changePasswordCommand): void {
        $password = new Password($changePasswordCommand->password);
        $user = $changePasswordCommand->user;
        $user->changePassword($password);
        $this->repository->save($user);
    }
}
