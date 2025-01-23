<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{

    public function __construct(private UserRepository $repo){}
    /**
     * @inheritDoc
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        // TODO: Implement refreshUser() method.
        return $this->loadUserByIdentifier($user->getUserIdentifier());

    }

    /**
     * @inheritDoc
     */
    public function supportsClass(string $class): bool
    {
        // TODO: Implement supportsClass() method.
        return $class == User::class;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        // TODO: Implement loadUserByIdentifier() method.
        $user = $this->repo->findByOne($identifier);
        if (!$user) {
            throw new UserNotFoundException('User does not exists');
        }
        return $user;
    }
}