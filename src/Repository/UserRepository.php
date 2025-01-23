<?php

namespace App\Repository;

use App\Entity\User;


class UserRepository
{
    public function __construct()
    {

    }

    public function persist(User $user): void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('INSERT INTO users (firstname,name,email,password,telephone,role,genre,createAt) 
VALUES (:firstname, :name, :email, :password, :telephone, :role, :genre, :createAt)');
        $query->bindValue(':firstname', $user->getFirstname());
        $query->bindValue(':name', $user->getName());
        $query->bindValue(':email', $user->getEmail());
        $query->bindValue(':password', $user->getPassword());
        $query->bindValue(':telephone', $user->getTelephone());
        $query->bindValue(':role', $user->getRole());
        $query->bindValue(':genre', $user->getGenre());
        $query->bindValue(':createAt', $user->getCreateAt()->format('Y-m-d H:i:s'));
        $query->execute();

        $user->setId($connexion->lastInsertId());
    }

    public function existingUser(string $identifier): ?User
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('SELECT * FROM users WHERE   email = :email ');
        $query->bindValue(':email', $identifier);
        $query->execute();

        if ($line = $query->fetch()) {
            $user = new User();
            $user->setId($line['id']);
            $user->setFirstname($line['firstname']);
            $user->setName($line['name']);
            $user->setEmail($line['email']);
            $user->setPassword($line['password']);
            $user->setTelephone($line['telephone']);
            $user->setGenre($line['genre']);
            return $user;
        }
        return null;
    }

//    public function findOneByEmail(string $email): ?User
//    {
//        return $this->findByOne(['email' => $email]);
//    }

    public function findByOne(string $email): ?User
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('SELECT * FROM users WHERE email = :email');
        $query->bindValue(':email', $email);
        $query->execute();

        if ($line = $query->fetch()) {
            $user = new User();
            $user->setId($line['id']);
            $user->setFirstname($line['firstname']);
            $user->setName($line['name']);
            $user->setEmail($line['email']);
            $user->setPassword($line['password']);
            $user->setTelephone($line['telephone']);
            $user->setRole($line['role']);
            $user->setGenre($line['genre']);

            if (!empty($line['createAt'])) {
                $user->setCreateAt(new \DateTimeImmutable($line['createAt']));
            }

            return $user;
        }

        return null;
    }

    public function promoteToAdmin(User $user): void
    {
        $connexion = Database::connect();

        // Mise à jour des rôles pour définir ROLE_ADMIN
        $query = $connexion->prepare('UPDATE users SET role = :role WHERE id = :id');
        $query->bindValue(':role', 'ROLE_ADMIN');
        $query->bindValue(':id', $user->getId());
        $query->execute();

        // Mettre à jour l'objet utilisateur
        $user->setRole('ROLE_ADMIN');
    }

    public function update(User $user)
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('UPDATE users SET firstname=:firstname, name= :name, email=:email, password=:password, telephone=:telephone, role=:role, genre=:genre ');
        $query->bindValue(':firstname', $user->getFirstname());
        $query->bindValue(':name', $user->getName());
        $query->bindValue(':email', $user->getEmail());
        $query->bindValue(':password', $user->getPassword());
        $query->bindValue(':telephone', $user->getTelephone());
        $query->bindValue(':role', $user->getRole());
        $query->bindValue(':genre', $user->getGenre());
        $query->execute();
    }

}