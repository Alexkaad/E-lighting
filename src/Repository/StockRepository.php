<?php

namespace App\Repository;

use App\Entity\Stock;

class StockRepository
{

    public function persist(Stock $stock):void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("INSERT INTO stock (id, productId, quantity) VALUES (:id, :productId, :quantity)");
        $query->bindValue('name',$stock->getId());
        $query->bindValue('quantity',$stock->getQuantity());
        $query->bindValue('productId',$stock->getProductId());
        $query->execute();
    }

    public function findStockById(int $id): ?Stock
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM stock WHERE id = :id");
        $query->bindValue(':id', $id);
        $query->execute();
        if($line =$query->fetch())
        {
            $stock = new Stock();
            $stock->setId($line['id']);
            $stock->setQuantity($line['quantity']);
            $stock->setProductId($line['productId']);
            return $stock;
        }
        return null;
    }

    public function findAll():array
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM stock");
        $query->execute();
        $list = [];
        while($line =$query->fetch())
        {
            $stock = new Stock();
            $stock->setId($line['id']);
            $stock->setQuantity($line['quantity']);
            $stock->setProductId($line['productId']);
            $list[] = $stock;
        }
        return $list;
    }

    public function updateStock(Stock $stock):void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("UPDATE stock SET quantity = :quantity WHERE id = :id");
        $query->bindValue(':quantity',$stock->getQuantity());
        $query->bindValue(':id',$stock->getId());
        $query->execute();
    }

    public function removeStock(int $id):void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("DELETE FROM stock WHERE id = :id");
        $query->bindValue(':id',$id);
        $query->execute();
    }

    public function findStockByProductId(int $productId):?Stock
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM stock WHERE productId = :productId");
        $query->bindValue(':productId',$productId);
        $query->execute();
        if($line =$query->fetch())
        {
            $stock = new Stock();
            $stock->setId($line['id']);
            $stock->setQuantity($line['quantity']);
            $stock->setProductId($line['productId']);
            return $stock;
        }
        return null;
    }

    public function findStockByProductIdAndQuantity(int $productId, int $quantity):?Stock
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM stock WHERE productId = :productId AND quantity = :quantity");
        $query->bindValue(':productId',$productId);
        $query->bindValue(':quantity',$quantity);
        $query->execute();
        if($line =$query->fetch())
        {
            $stock = new Stock();
            $stock->setId($line['id']);
            $stock->setQuantity($line['quantity']);
            $stock->setProductId($line['productId']);
            return $stock;
        }
        return null;
    }
}