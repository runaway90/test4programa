<?php

namespace App\Domain\Strategy;

use App\Domain\Enum\TaskStatus;

class StrictStatusStrategy implements StatusValidationStrategyInterface
{
    public function canTransition(TaskStatus $currentStatus, TaskStatus $nextStatus): bool
    {
        if ($currentStatus === $nextStatus) {
            return true;
        }

        return match ($currentStatus) {
            TaskStatus::TODO => $nextStatus === TaskStatus::IN_PROGRESS,
            TaskStatus::IN_PROGRESS => $nextStatus === TaskStatus::DONE || $nextStatus === TaskStatus::TODO,
            TaskStatus::DONE => false,
        };
    }
}
