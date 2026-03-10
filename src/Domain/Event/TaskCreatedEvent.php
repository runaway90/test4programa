<?php

namespace App\Domain\Event;

class TaskCreatedEvent
{
    public function __construct(
        public readonly string $taskId,
        public readonly string $title,
        public readonly string $status
    ) {}
}
