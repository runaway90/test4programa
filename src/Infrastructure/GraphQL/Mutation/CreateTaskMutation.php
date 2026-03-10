<?php

namespace App\Infrastructure\GraphQL\Mutation;

use App\Domain\Entity\Task;
use App\Domain\Entity\User;
use App\Domain\Enum\TaskStatus;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateTaskMutation
{
    public function __construct(
        private UserRepository $repository,
        private EntityManagerInterface $entityManager
    ) {}

    public function __invoke(array $input)
    {
        $user = $this->repository->find($input['userId']);
        if (!$user instanceof User) {
            throw new \Exception("User not found: " . $input['userId']);
        }

        $status = isset($input['status'])
            ? TaskStatus::from($input['status'])
            : TaskStatus::TODO;

        $task = new Task(
            $input['title'],
            $input['description'],
            $user,
            $status
        );

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $events = $task->pullEvents();

        return $task;
    }
}
