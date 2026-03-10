<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    private string $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', length: 60)]
    private string $password;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $name;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $externalId;

    #[ORM\Column(type: 'json')]
    private array $role;

    public function __construct(string $id, string $email, string $password, ?string $name = null, ?int $externalId = null, array $roles = [])
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->externalId = $externalId;
        $this->role = $roles;
    }
    public function getId(): string { return $this->id; }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string { return $this->password; }
    public function getEmail(): string { return $this->email; }
    public function getName(): ?string { return $this->name; }
    public function getExternalId(): ?int { return $this->externalId; }
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->role;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
    public function setRole(string $role): self
    {
        $role = strtoupper($role);
        if (!str_starts_with($role, 'ROLE_')) {
            $role = 'ROLE_' . $role;
        }

        if (!in_array($role, $this->role, true)) {
            $this->role[] = $role;
        }

        return $this;
    }
    public function eraseCredentials(): void
    {
    }
}
