<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;


class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    private ?int $id = null;

    #[NotBlank]
    private ?string $firstname = null;

    /**
     * @return string|null
     */
    #[NotBlank]
    private ?string $name = null;

    #[Email]
    #[NotBlank]
    private ?string $email = null;

    private ?string $password = null;

    private ?string $telephone = null;

    private ?string $role = null;

    private ?\DateTimeImmutable $createAt = null;

    private ?string $genre = null;

    private ?\DateTimeImmutable $updateAt = null;
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $Firstname
     */
    public function setFirstname(?string $Firstname): void
    {
        $this->firstname = $Firstname;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param string|null $telephone
     */
    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string|null $role
     */
    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    /**
     * @param \DateTimeImmutable|null $createAt
     */
    public function setCreateAt(?\DateTimeImmutable $createAt): void
    {
        $this->createAt = $createAt;
    }

    /**
     * @return string|null
     */
    public function getGenre(): ?string
    {
        return $this->genre;
    }

    /**
     * @param string|null $genre
     */
    public function setGenre(?string $genre): void
    {
        $this->genre = $genre;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    /**
     * @param \DateTimeImmutable|null $updateAt
     */
    public function setUpdateAt(?\DateTimeImmutable $updateAt): void
    {
        $this->updateAt = $updateAt;
    }



    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        // TODO: Implement getRoles() method.

        // Récupérer les rôles stockés dans l'entité (par exemple, via une colonne 'role')
        $roles = [$this->role];
        // Ajoute toujours ROLE_USER si ce n'est pas déjà dans la liste
        $roles[] = 'ROLE_USER';
        // Évite les doublons dans le tableau
        return array_unique($roles);
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @inheritDoc
     */
    public function getUserIdentifier(): string
    {
        // TODO: Implement getUserIdentifier() method.
        return (string) $this->email;
    }
}