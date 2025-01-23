<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;



class AuthController extends AbstractController
{
    public function __construct(
        private UserRepository           $repo,
        private JWTTokenManagerInterface $tokenManager
    )
    {
    }

    #[Route('/api/user', methods: 'POST')]
    public function register(
        #[MapRequestPayload] User   $user,
        UserPasswordHasherInterface $hasher,
    ): JsonResponse
    {
        if ($this->repo->existingUser($user->getEmail())) {

            return $this->json('Email already exists', 400);
        }
        if ($this->repo->existingUser($user->getTelephone())) {
            return $this->json('Telephone already exists', 400);
        }
        $user->setCreateAt(new \DateTimeImmutable());
        $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setRole('ROLE_USER');
        $this->repo->persist($user);

        return $this->json($user, 201);
    }

    public function promoteToAdmin(User $user): void
    {
        // Vérifiez si l'utilisateur est déjà Admin
        if ($user->getRole() === 'ROLE_ADMIN') {
            throw new \InvalidArgumentException('Cet utilisateur est déjà administrateur.');
        }

        // Modifier le rôle de l'utilisateur en ROLE_ADMIN
        $this->repo->promoteToAdmin($user);
    }


    #[Route('/api/user', methods: 'GET')]

    public function getConnected():JsonResponse
    {
        return $this->json($this->getUser());
    }

    #[Route('/api/user/validate', methods: 'GET')]
    public function validate(#[MapRequestPayload] user $user)
    {
        $user = $this->repo->existingUser($user->getEmail());
        if (!$user) {
            return $this->json('User not found', 400);
        }
        $this->validate($user);
        return $this->json($user, 201);
    }
}


