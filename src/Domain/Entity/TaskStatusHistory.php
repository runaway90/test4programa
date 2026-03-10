<?php
namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'task_status_history')]
class TaskStatusHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private string $taskId;

    #[ORM\Column(type: 'string')]
    private string $oldStatus;

    #[ORM\Column(type: 'string')]
    private string $newStatus;

    #[ORM\Column]
    private \DateTimeImmutable $changedAt;

    public function __construct(string $taskId, string $oldStatus, string $newStatus)
    {
        $this->taskId = $taskId;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->changedAt = new \DateTimeImmutable();
    }

    public function getOldStatus(): string { return $this->oldStatus; }
    public function getNewStatus(): string { return $this->newStatus; }
    public function getChangedAt(): \DateTimeImmutable { return $this->changedAt; }
}
