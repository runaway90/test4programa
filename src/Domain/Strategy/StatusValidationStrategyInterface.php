<?php

namespace App\Domain\Strategy;

use App\Domain\Enum\TaskStatus;

interface StatusValidationStrategyInterface
{
    public function canTransition(TaskStatus $currentStatus, TaskStatus $nextStatus): bool;
}
