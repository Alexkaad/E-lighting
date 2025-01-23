<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class RoleManager
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Promouvoir un utilisateur au rôle d'administrateur.
     */
    public function promoteToAdmin(User $user): void
    {
        // Vérifiez si l'utilisateur est déjà Admin
        if ($user->getRole() === 'ROLE_ADMIN') {
            throw new \InvalidArgumentException('Cet utilisateur est déjà administrateur.');
        }

        // Modifier le rôle de l'utilisateur en ROLE_ADMIN
        $this->userRepository->promoteToAdmin($user);
    }

}