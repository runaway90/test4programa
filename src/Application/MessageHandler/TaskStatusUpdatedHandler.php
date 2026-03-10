<?php
namespace App\Application\MessageHandler;

use App\Domain\Entity\TaskStatusHistory;
use App\Domain\Event\TaskStatusUpdatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TaskStatusUpdatedHandler
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function __invoke(TaskStatusUpdatedEvent $event)
    {
        $history = new TaskStatusHistory(
            $event->taskId,
            $event->oldStatus,
            $event->newStatus
        );

        $this->entityManager->persist($history);
        $this->entityManager->flush();
    }
}
