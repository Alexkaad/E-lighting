<?php

namespace App\Repository;

use App\Entity\Brand;
use App\Entity\Category;


class BrandRepository

{
 public function __construct()
 {}

    public function persist(Brand $brand):void
    {

        $connexion = Database::connect();

        // Insérer le Brand en base de données
        $query = $connexion->prepare('INSERT INTO brands (name,origin,path,createAt) VALUES (:name,:origin,:path,:createdAt)');
        $query->bindValue(':name', $brand->getName());
        $query->bindValue(':origin', $brand->getOrigin());
        $query->bindValue(':createdAt', $brand->getCreatedAt()->format('Y-m-d H:i:s'));
        $query->bindValue(':path', $brand->getPath());
        $query->execute();
        $brand->setId($connexion->lastInsertId());
    }


    public  function findBrandById(int $id): ?Brand
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM brands WHERE id = :id");
        $query->bindValue(':id', $id);
        $query->execute();

        if($line =$query->fetch())
        {
            $brand = new Brand();
            $brand->setId($line['id']);
            $brand->setName($line['name']);
            $brand->setOrigin($line['origin']);
            $brand->setCreatedAt(new \DateTimeImmutable($line['createAt']));

            return $brand;
        }
        return null;
    }



    public function findByName(string $name): ?Brand
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM brands WHERE name = :name");
        $query->bindValue(':name', $name);
        $query->execute();
        if($line =$query->fetch())
        {
            $brand = new Brand();
            $brand->setId($line['id']);
            $brand->setName($line['name']);
            $brand->setOrigin($line['origin']);
            $brand->setCreatedAt(new \DateTimeImmutable($line['createAt']));
            return $brand;
        }
            return null;
    }
}