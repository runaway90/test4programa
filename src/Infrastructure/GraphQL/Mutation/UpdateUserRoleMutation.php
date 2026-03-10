<?php

namespace App\Infrastructure\GraphQL\Mutation;

use App\Domain\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateUserRoleMutation
{
    public function __construct(
        private UserRepository         $userRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(string $userId, string $newRole): User
    {
        $user = $this->userRepository->find($userId);

        if (!$user) {
            throw new \Exception("User not found");
        }

        $user->setRole($newRole);

        $this->entityManager->flush();

        return $user;
    }
}
