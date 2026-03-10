<?php
namespace App\Tests\Unit\Factory;

use App\Domain\Entity\Task;
use App\Domain\Entity\User;
use App\Domain\Enum\TaskStatus;
use App\Domain\Factory\TaskFactory;
use PHPUnit\Framework\TestCase;

class TaskFactoryTest extends TestCase
{
    private TaskFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new TaskFactory();
    }

    /**
     * Test sprawdza, czy fabryka poprawnie tworzy obiekt Task dla poprawnych danych.
     */
    public function testCreateTaskReturnsValidInstance(): void
    {
        $user = $this->createMock(User::class);

        $title = "Testowa zadanie";
        $description = "Opis zadania";
        $status = TaskStatus::TODO;

        $task = $this->factory->create($title, $description, $user, $status);

        $this->assertInstanceOf(Task::class, $task);

        $this->assertEquals($title, $task->getTitle());
        $this->assertEquals($user, $task->getAssignedUser());
        $this->assertEquals(TaskStatus::TODO, $task->getStatus());
    }

    /**
     * Test sytuacji brzegowej: Sprawdza domyślny status, jeśli nie zostanie podany.
     */
    public function testCreateTaskWithDefaultStatus(): void
    {
        $user = $this->createMock(User::class);

        $task = $this->factory->create("Tytuł", "Opis", $user);

        $this->assertEquals(TaskStatus::TODO, $task->getStatus());
    }

    /**
     * Test obsługi błędnych danych (np. pusty tytuł).
     */
    public function testCreateTaskThrowsExceptionOnEmptyTitle(): void
    {
        $user = $this->createMock(User::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Title cannot be empty");

        $this->factory->create("", "Opis", $user);
    }
}
