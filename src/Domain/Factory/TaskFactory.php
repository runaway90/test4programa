<?php

namespace App\Domain\Factory;

use App\Domain\Entity\Task;
use App\Domain\Entity\User;

class TaskFactory
{
    public function create(string $title, string $description, User $user): Task
    {
        if (!$title) {
            throw new \InvalidArgumentException("Title cannot be empty");
        }
        if (!$description) {
            throw new \InvalidArgumentException("Description cannot be empty");
        }

        return new Task($title, $description, $user);
    }
}
