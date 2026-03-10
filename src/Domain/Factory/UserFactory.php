<?php

namespace App\Domain\Factory;

use App\Domain\Entity\User;
use Symfony\Component\Uid\Uuid;

class UserFactory
{
    public function createFromExternalData(array $data): User
    {
        return new User(
            id: Uuid::v4()->toRfc4122(),
            email: $data['email'],
            password: $data['password'],
            name: $data['name'],
            externalId: $data['id']
        );
    }
}
