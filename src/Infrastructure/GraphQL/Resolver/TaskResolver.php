<?php
namespace App\Infrastructure\GraphQL\Resolver;

use App\Infrastructure\Repository\TaskRepository;
use Symfony\Bundle\SecurityBundle\Security;

class TaskResolver
{
    public function __construct(
        private TaskRepository $taskRepository,
        private Security $security
    ) {}

    public function resolveAllTasks(): array
    {
        return $this->taskRepository->findAll();
    }
}
