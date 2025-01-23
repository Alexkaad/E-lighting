<?php

namespace App\Repository;


use App\Entity\Creator;

class CreatorRepository
{
    public function __construct()
    {
    }

    public function persist(Creator $creator, array $brands): void
    {
        try {
            // Connexion à la base de données
            $connexion = Database::connect();

            if (!$connexion->inTransaction()) {
                $connexion->beginTransaction();
            }

            // Insertion des données du `Creator`
            $query = $connexion->prepare('INSERT INTO creators (name,description, createdAt ,path) VALUES (:name,:description, :createdAt,:path)');
            $query->bindValue(':name', $creator->getName());
            $query->bindValue(':description', $creator->getDescription());
            $query->bindValue(':createdAt', $creator->getCreatedAt()->format('Y-m-d H:i:s'));
            $query->bindValue(':path',$creator->getPath());
            $query->execute();

            // Récupérer l'ID du creator nouvellement inséré
            $creatorId = $connexion->lastInsertId();

            // Insertion des relations Creator - Brands
            foreach ($brands as $brand) {
                $queryBrandCreator = $connexion->prepare('INSERT INTO brand_creator (brand_id, creator_id) VALUES (:brand_id, :creator_id)');
                $queryBrandCreator->bindValue(':brand_id', $brand->getId());
                $queryBrandCreator->bindValue(':creator_id', $creatorId);
                $queryBrandCreator->execute();
            }

            $connexion->commit();
        } catch (\Exception $e) {
            if ($connexion->inTransaction()) {
                $connexion->rollBack();
            }

            throw $e;
        }
    }


    public function getAllImages(): array
    {
        $connection = Database::connect();
        $query = $connection->prepare('SELECT * FROM creators');
        $query->execute();
        $list = [];
        while ($line = $query->fetch()) {
            $creator = new Creator();
            $creator->setId($line['id']);
            $creator->setName($line['name']);
            $creator->setDescription($line['description']);
            $creator->setCreatedAt(new \DateTimeImmutable($line['createAt']));
            $list[] = $creator;
        }

        return $list;
    }

    public function findOne(int $id): ?Creator
    {
        foreach ($this->getAllImages() as $creator) {
            if ($creator->getId() === $id) {
                return $creator;
            }
        }

        return null;
    }

    public function findByName(string $name): ?Creator
    {
        $connection = Database::connect();
        $query = $connection->prepare('SELECT * FROM creators WHERE name = :name');
        $query->bindValue(':name', $name);
        $query->execute();
        if ($line = $query->fetch()) {
            $creator = new Creator();
            $creator->setId($line['id']);
            $creator->setName($line['name']);
            $creator->setDescription($line['description']);
            $creator->setCreatedAt(new \DateTimeImmutable($line['createAt']));
            if (!isset($line['updateAt'])) {
                $creator->setUpdatedAt(new \DateTimeImmutable($line['updateAt']));
            }
            return $creator;
        }
        return null;
    }
}