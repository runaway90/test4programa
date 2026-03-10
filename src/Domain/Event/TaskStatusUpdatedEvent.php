<?php

namespace App\Domain\Event;

class TaskStatusUpdatedEvent
{
    public function __construct(
        public readonly string $taskId,
        public readonly string $oldStatus,
        public readonly string $newStatus
    ) {}
}
