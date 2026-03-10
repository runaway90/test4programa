<?php
namespace App\Infrastructure\GraphQL\Resolver;

use App\Infrastructure\Repository\UserRepository;
use function React\Promise\all;

class UserLoader
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository) {
        $this->repository = $repository;
    }

    public function __invoke(array $ids)
    {
        $users = $this->repository->findBy(['id' => $ids]);
        $map = [];
        foreach ($users as $u) $map[$u->getId()] = $u;

        return all(array_map(fn($id) => $map[$id] ?? null, $ids));
    }
}
