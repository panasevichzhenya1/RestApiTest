<?php

namespace App\User\Api\Controller;

use App\User\Application\Command\ChangePassword;
use App\User\Application\Command\ChangePhoneNumber;
use App\User\Application\Command\CreateUser;
use App\User\Application\Command\Login;
use App\User\Application\Command\RemoveUser;
use App\User\Application\Command\Rename;
use App\User\Application\UserHandler;
use App\User\Infrastructure\Repository\UserRepository;
use App\User\Model\ValueObject\Email;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @var UserHandler
     */
    private $handler;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var EntityManagerInterface
     */
    private $em;


    public function __construct(UserHandler $handler, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->handler = $handler;
        $this->serializer = $serializer;
        $this->em = $em;
    }

    /**
     * @Route("/api/login_check", name="login", methods={"POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $encoder
     */
    public function login(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $encoder): JsonResponse
    {
        $login = $this->serializer->deserialize($request->getContent(), Login::class, 'json');
        $user = $userRepository->findByEmail(new Email($login->email()));
        if (!$user || $user->getPassword() != $request->get('password')) {
            return new JsonResponse([
                'message' => 'email or password is wrong.',
            ]);
        }
        $payload = [
            "user" => $user->getEmail(),
            "exp"  => (new \DateTime())->modify("+5 minutes")->getTimestamp(),
        ];


        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');

        return new JsonResponse([
            'message' => 'success!',
            'token' => sprintf('Bearer %s', $jwt),
        ]);
    }

    /**
     * @Route("/api/user", name="register")
     */
    public function create(Request $request): JsonResponse
    {
        $createUser = $this->deserializeJson($request->getContent(), CreateUser::class);
        $this->em->transactional(function () use ($createUser) {
            $this->handler->create($createUser);
        });

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * @Route("/api/user/delete", name="remove")
     */
    public function delete(Request $request): JsonResponse
    {
        $removeUserCommand = $this->deserializeJson($request->getContent(), RemoveUser::class);
        $this->em->transactional(function () use ($removeUserCommand) {
            $this->handler->remove($removeUserCommand);
        });

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * @Route("/api/user/rename", name="rename")
     */
    public function rename(Request $request, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $user = $tokenStorage->getToken()->getUser();
        $renameCommand = $this->deserializeJson($request->getContent(), Rename::class, [
            'default_constructor_arguments' => [
                Rename::class => [
                    'user' => $user
                ]
            ]
        ]);
        $this->em->transactional(function () use ($renameCommand) {
            $this->handler->rename($renameCommand);
        });

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * @Route("/api/user/change-phone-number", name="change_number")
     */
    public function changePhoneNumber(Request $request, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $user = $tokenStorage->getToken()->getUser();
        $changeNumberCommand = $this->deserializeJson($request->getContent(), ChangePhoneNumber::class, [
            'default_constructor_arguments' => [
                ChangePhoneNumber::class => [
                    'user' => $user
                ]
            ]
        ]);
        $this->em->transactional(function () use ($changeNumberCommand) {
            $this->handler->changePhoneNumber($changeNumberCommand);
        });

        return new JsonResponse(['status' => 'ok']);
    }

    public function changePassword(Request $request, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $user = $tokenStorage->getToken()->getUser();
        $changePasswordCommand = $this->deserializeJson($request->getContent(), ChangePassword::class, [
            'default_constructor_arguments' => [
                ChangePassword::class => [
                    'user' => $user
                ]
            ]
        ]);

        $this->em->transactional(function () use ($changePasswordCommand) {
            $this->handler->changePassword($changePasswordCommand);
        });

        return new JsonResponse(['status' => 'ok']);
    }

    private function deserializeJson(string $json, string $type, array $context = [])
    {
        return $this->serializer->deserialize($json, $type, 'json', $context);
    }
}
