<?php

namespace App\Infrastructure\GraphQL\Resolver;

use App\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserResolver implements AliasedInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function __invoke(): array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    public function findOneById(string $id): ?User
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        return $user;
    }

    public static function getAliases(): array
    {
        return [
            '__invoke' => 'users',
            'findOneById' => 'user'
        ];
    }
}
