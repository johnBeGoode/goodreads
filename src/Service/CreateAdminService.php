<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdminService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function create(string $email, string $password): void
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $hashedPassword = $this->userPasswordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
        }

        $user->setRoles(['ROLE_ADMIN']);

        $this->userRepository->save($user, true);
    }
}