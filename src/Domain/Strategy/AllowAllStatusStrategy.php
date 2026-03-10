<?php

namespace App\Domain\Strategy;

use App\Domain\Enum\TaskStatus;

class AllowAllStatusStrategy implements StatusValidationStrategyInterface
{
    public function canTransition(TaskStatus $currentStatus, TaskStatus $newStatus): bool
    {
        return true;
    }
}
