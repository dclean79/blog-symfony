<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function registerUser(User $user, string $plainPassword): User
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPasswordHash($hashedPassword);
        $user->setRole(User::ROLE_USER);
        $user->setActivationToken(bin2hex(random_bytes(32))); // token aktywacyjny

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
