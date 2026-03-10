<?php
namespace App\Infrastructure\GraphQL\Mutation;

use App\Domain\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class LoginMutation
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager
    ) {}

    public function __invoke(string $email, string $password): array
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
            // Бросаем исключение, которое GraphQL превратит в сообщение об ошибке
            throw new AuthenticationException("Неверный email или пароль");
        }

        return [
            'token' => $this->jwtManager->create($user),
            'user'  => $user,
        ];
    }
}
