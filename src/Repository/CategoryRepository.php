<?php

namespace App\Repository;

use App\Entity\Brand;
use App\Entity\Category;
use PDO;

class CategoryRepository
{
    public function __construct()
    {
    }

    public function persist(Category $category): void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('INSERT INTO categories (name , createAt) VALUES (:name,:createAt) ');
        $query->bindValue(':name', $category->getName());
        $query->bindValue(':createAt', $category->getCreatedAt()->format('Y-m-d H:i:s'));
        $query->execute();
    }

    public function persistForBrand(Category $category,array $brands): void
    {
        try {


            $connexion = Database::connect();

            if(!$connexion->inTransaction())
            {
                $connexion->beginTransaction();
            }
            $query = $connexion->prepare('INSERT INTO categories (name , createAt) VALUES (:name,:createAt) ');
            $query->bindValue(':name', $category->getName());
            $query->bindValue(':createAt', $category->getCreatedAt()->format('Y-m-d H:i:s'));
            $query->execute();

            // Récupérer l'ID de la catégorie

            $categoryId= $connexion->lastInsertId();

            // Insérer les relations (liaison catégorie - marques)

            foreach ($brands as $brand) {
                $queryBrandCategory = $connexion->prepare('INSERT INTO brand_categories (brand_id,category_id) VALUES (:brand_id,:category_id)');
                $queryBrandCategory->bindValue(':brand_id', $brand->getId());
                $queryBrandCategory->bindValue(':category_id', $categoryId);
                $queryBrandCategory->execute();


            }
            $connexion->commit();
        }catch (\PDOException $e)
        {
            error_log($e->getMessage());

            throw $e;
        }
    }

    // Methode qui va permettre de recuperer les noms des marques par Category
    public function getBrandNamesByCategoryId(int $categoryId): array
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('SELECT b.name AS brand_name FROM brands b JOIN brand_categories bc ON b.id = bc.brand_id 
                                            JOIN categories c ON c.id= bc.category_id WHERE c.id = :categoryId

          ');
        $query->bindParam(':categoryId',$categoryId);
        $query->execute();



        $results= $query->fetchAll(PDO::FETCH_ASSOC);

        return array_column($results,'brand_name');
    }
    public function findAll(): array
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('SELECT * FROM categories'  );
        $query->execute();


        $list = [];
        foreach ($query->fetchAll() as $line)
        {
            $category = new Category();

           $category->setName($line['name']);     
           $category->setCreatedAt(new \DateTimeImmutable($line['createAt']));     
            $category->setId($line['id']);    
             $category->setUpdatedAt(new \DateTimeImmutable($line['updateAt']));

             $list[] = $category;
        }
        return $list;
    }

    public function findByName(string $name): ?Category
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('SELECT * FROM categories WHERE name = :name');
        $query->bindValue(':name', $name);
        $query->execute();

        if($line = $query->fetch())
        {
            $category = new Category();

                $category->setName($line['name']);
                $category->setCreatedAt(new \DateTimeImmutable($line['createAt']));
                $category->setId($line['id']);
                if(!isset($line['updateAt']))
                {
                    $category->setUpdatedAt(new \DateTimeImmutable($line['updateAt']));
                }
                    return $category;
        }
        return null;
    }

    public function findById(int $id):?Category {
        $connexion = Database::connect();
        $query = $connexion->prepare('SELECT * FROM categories WHERE id=:id');
        $query->bindValue(':id', $id);
        $query->execute();
        if($line = $query->fetch()) {
            $category = new Category();
             $category->setName (  $line['name']);
             $category->setCreatedAt( new \DateTimeImmutable($line['createAt']));  
              $category->setId($line['id']);  
//               $category->setUpdatedAt(new \DateTimeImmutable($line['updateAt']));

               return $category;
            
        }
        return null;
    }

    public function remove (int $id): void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('DELETE FROM categories WHERE id = :id');
        $query->bindValue(':id', $id);
        $query->execute();
    }

    public function update(Category $category): void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('UPDATE categories SET name = :name  WHERE id = :id');
        $query->bindValue(':id', $category->getId());
        $query->bindValue(':name', $category->getName());

        $query->execute();
    }
}