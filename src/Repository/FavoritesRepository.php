<?php

namespace App\Repository;

use App\Entity\favorites;
use App\Entity\Product;

class FavoritesRepository
{

    public function persist(favorites $favorites): void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('INSERT INTO favorites (id, userId, productId,addedAt) VALUES (:id, :user_id, :product_id,:AddedAt)');
        $query->bindValue(':id', $favorites->getId());
        $query->bindValue(':user_id', $favorites->getUserId());
        $query->bindValue(':product_id', $favorites->getProductId());
        $query->bindValue(':AddedAt', $favorites->getAddedAt()->format('Y-m-d H:i:s'));
        $query->execute();
    }

    public function isProductInFavorites(int $userId, int $productId): bool
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('SELECT * FROM favorites WHERE userId = :userId AND productId = :productId');
        $query->bindValue(':userId', $userId);
        $query->bindValue(':productId', $productId);
        $query->execute();
        return $query->fetch() ? true : false;
    }
    public function getFavoritesByProductId($productId): ?favorites
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('SELECT * FROM favorites WHERE productId = :productId');
        $query->bindValue(':productId', $productId);
        $query->execute();
        if($line = $query->fetchAll()){

            $favorites = new favorites();
            $favorites->setId($line['id']);
            $favorites->setUserId($line['user_id']);
            $favorites->setProductId($line['product_id']);
            $favorites->setAddedAt(new \DateTimeImmutable($line['addedAt']));
            return $favorites;
        }
        return null;

    }

    public function getFavoritesByUserId($userId): ?favorites
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('SELECT * FROM favorites WHERE user_id = :userId');
        $query->bindValue(':user_id', $userId);
        $query->execute();
        if ($line = $query->fetchAll()) {
            $favorites = new favorites();
            $favorites->setId($line['id']);
            $favorites->setUserId($line['user_id']);
            $favorites->setProductId($line['product_id']);
            $favorites->setAddedAt(new \DateTimeImmutable($line['addedAt']));
            return $favorites;
        }
        return null;
    }

    public function getLikeProductsByUserId(int $userId): array
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT p.*
FROM favorites f
JOIN products p ON f.product_id = p.id
WHERE f.user_id = :user_id");

        $query->bindValue(':user_id', $userId);

        $query->execute();

        $result = [];

        foreach ($query->fetchAll() as $line) {
            $product = new product();
            $product->setId($line['id']);
            $product->setName($line['name']);
            $product->setPrice($line['price']);
            $product->setDescription($line['description']);
            $product->setCouleur($line['couleur']);
            $product->setReference($line['reference']);
            $result[] = $product;
        }
        return $result;
    }

    public function removeFavorites(int $userId, int $productId): void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('DELETE FROM favorites WHERE user_id = :userId AND product_id = :productId');
        $query->bindValue(':user_id', $userId);
        $query->bindValue(':product_id', $productId);
        $query->execute();
    }


}