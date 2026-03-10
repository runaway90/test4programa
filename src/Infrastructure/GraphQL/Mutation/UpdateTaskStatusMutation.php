<?php

namespace App\Infrastructure\GraphQL\Mutation;

use App\Domain\Enum\TaskStatus;
use App\Domain\Event\TaskStatusUpdatedEvent;
use App\Domain\Strategy\StrictStatusStrategy;
use App\Infrastructure\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdateTaskStatusMutation
{
    public function __construct(
        private EntityManagerInterface  $entityManager,
        private MessageBusInterface     $bus,
        private TaskRepository          $taskRepository,
        private StrictStatusStrategy    $strategy,
    )
    {
    }

    public function __invoke(string $id, string $newStatusValue)
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            throw new \Exception("Task not found");
        }

        $oldStatus = $task->getStatus()->value;

        $newStatus = TaskStatus::from($newStatusValue);
        $task->changeStatus($newStatus, $this->strategy);

        $this->entityManager->flush();

        $this->bus->dispatch(new TaskStatusUpdatedEvent(
            $task->getId(),
            $oldStatus,
            $newStatus->value
        ));

        return $task;
    }
}
