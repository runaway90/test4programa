<?php

namespace App\DataFixtures;

use App\Domain\Entity\Task;
use App\Domain\Entity\User;
use App\Domain\Enum\TaskStatus;
use App\Domain\Strategy\AllowAllStatusStrategy;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private PasswordHasherFactoryInterface $passwordHasherFactory
    ) {}

    public function load(ObjectManager $manager): void
    {
        $taskTitles = [
            'Setup Docker environment',
            'Configure Symfony project',
            'Create Task Entity and Enum',
            'Install Overblog GraphQL Bundle',
            'Write UpdateTaskStatus mutation',
            'Implement Status Transition Strategy',
            'Fix database connection issues',
            'Add Data Fixtures for testing',
            'Run migrations on Postgres',
            'Verify GraphQL Playground'
        ];
        $userRepository = $manager->getRepository(User::class);
        $user = $userRepository->findOneBy([]);

        if (!$user) {
            $hashedPassword = $this->passwordHasherFactory->getPasswordHasher(User::class)->hash('password');
            $user = new User('admin', 'admin@example.com', $hashedPassword, 'admin');

            $manager->persist($user);
            $manager->flush();
        }

        foreach ($taskTitles as $index => $title) {
            $task = new Task(
                $title,
                "Description for task " . ($index + 1),
                $user
            );

            $status = match($index % 3) {
                1 => TaskStatus::IN_PROGRESS,
                2 => TaskStatus::DONE,
                default => TaskStatus::TODO,
            };

            $task->changeStatus($status, new AllowAllStatusStrategy());
            $manager->persist($task);
        }

        $manager->flush();
    }
}
