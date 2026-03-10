<?php

namespace App\Domain\Entity;

use App\Domain\Enum\TaskStatus;
use App\Domain\Event\TaskCreatedEvent;
use App\Domain\Event\TaskStatusUpdatedEvent;
use App\Domain\Strategy\StatusValidationStrategyInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'tasks')]
class Task
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'string', enumType: TaskStatus::class)]
    private TaskStatus $status;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $assignedUser;

    private array $recordedEvents = [];

    public function __construct(string $title, string $description, User $assignedUser, TaskStatus $status = TaskStatus::TODO)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->assignedUser = $assignedUser;

        $this->recordEvent(new TaskCreatedEvent($this->id, $this->title, $status->value));
    }

    private function recordEvent(object $event): void
    {
        $this->recordedEvents[] = $event;
    }

    public function pullEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];
        return $events;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAssignedUser(): User
    {
        return $this->assignedUser;
    }

    public function changeStatus(TaskStatus $newStatus, StatusValidationStrategyInterface $strategy): void
    {
        if (!$strategy->canTransition($this->getStatus(), $newStatus)) {
            throw new \DomainException(sprintf(
                "Change '%s' status to '%s' imposible. Because %s.",
                $this->status->value,
                $newStatus->value,
                (new \ReflectionClass($strategy))->getShortName()
            ));
        }

        $oldStatus = $this->status;
        $this->status = $newStatus;

        $this->recordEvent(new TaskStatusUpdatedEvent(
            $this->id,
            $oldStatus->value,
            $newStatus->value
        ));
    }

}
