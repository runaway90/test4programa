<?php

namespace App\Application\Service;

use App\Domain\Entity\User;
use App\Domain\Factory\UserFactory;
use App\Infrastructure\ExternalApi\JsonPlaceholderClient;
use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure\Persistence\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserSyncService
{
    public function __construct(
        private JsonPlaceholderClient  $client,
        private UserFactory            $userFactory,
        private EntityManagerInterface $entityManager,
        private UserRepository         $userRepository,
    private PasswordHasherFactoryInterface $passwordHasherFactory

    )
    {
    }

    public function sync(): array
    {
        $response = $this->client->fetchUsers();

        $createdCount = 0;
        $skippedCount = 0;
        foreach ($response as $userData) {
            $user = $this->userRepository->findOneBy(['email' => $userData['email']]);

            if (!$user) {
                $userData['password']  = $this->passwordHasherFactory->getPasswordHasher(User::class)->hash('password');
                $user = $this->userFactory->createFromExternalData($userData);
                $this->entityManager->persist($user);
                $createdCount++;
            } else {
                $skippedCount++;
            }
        }

        $this->entityManager->flush();

        return [
            'created' => $createdCount,
            'skipped' => $skippedCount,
        ];
    }
}
