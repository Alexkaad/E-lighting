<?php

namespace App\Repository;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Creator;
use App\Entity\Product;
use PDO;
use Symfony\Component\HttpFoundation\JsonResponse;


class ProductRepository
{
    public function __construct()
    {
    }

    public function persist(Product $product): int
    {

        $connexion = Database::connect();
        $query = $connexion->prepare("INSERT INTO products (name,price,description,couleur,reference,creator_id,sku,brand_id,designation,category_id,created_at,quantityStock) 
VALUES (:name,:price,:description,:couleur,:reference,:creator_id,:sku,:brand_id,:desigantion,:category_id,:created_at,:quantityStock)");
        $query->bindValue(':name', $product->getName());
        $query->bindValue(':price', $product->getPrice());
        $query->bindValue(':description', $product->getDescription());
        $query->bindValue(':couleur', $product->getCouleur());
        $query->bindValue(':reference', $product->getReference());
        $query->bindValue(':creator_id', $product->getCreatorId());
        $query->bindValue(':sku', $product->getSku());
        $query->bindValue(':brand_id', $product->getBrandId());
        $query->bindValue(':designation',$product->getDesigantion());
        $query->bindValue(':category_id', $product->getCategoryId());
        $query->bindValue(':quantityStock', $product->getQuantityStock());
        $query->bindValue(':created_at', $product->getCreatedAt()->format('Y-m-d H:i:s'));
        $query->execute();

        $lastInsertId = $connexion->lastInsertId();
        $product->setId($lastInsertId);

        return (int)$lastInsertId;
    }

    public function findProductById(int $id): ?Product
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM products WHERE id = :id");
        $query->bindValue(':id', $id);
        $query->execute();

        if ($line = $query->fetch()) {
            $product = new Product();
            $product->setId($line['id']);
            $product->setName($line['name']);
            $product->setPrice($line['price']);
            $product->setDescription($line['description']);
            $product->setCouleur($line['couleur']);
            $product->setReference($line['reference']);
            $product->setCreatorId($line['creator_id']);
            $product->setSku($line['sku']);
            $product->setBrandId($line['brand_id']);
            $product->setCategoryId($line['category_id']);


            return $product;
        }

        return null;
    }

    public function findAll(int $offset, int $limit)
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM products ORDER BY id DESC LIMIT :offset,:limit");
        $query->bindValue(':offset', $offset);
        $query->bindValue(':limit', $limit);
        $query->execute();
        $list = [];
        while ($line = $query->fetch()) {
            $product = new Product();
            $product->setId($line['id']);
            $product->setName($line['name']);
            $product->setPrice($line['price']);
            $product->setDescription($line['description']);
            $product->setCouleur($line['couleur']);
            $product->setReference($line['reference']);
            $product->setCreatorId($line['creator_id']);
            $product->setSku($line['sku']);
            $product->setBrandId($line['brand_id']);
            $product->setCategoryId($line['category_id']);
            $list[] = $product;
        }

        return $list;
    }

    public function findByName(string $name): ?Product
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM products WHERE name = :name");
        $query->bindValue(':name', $name);
        $query->execute();

        if ($line = $query->fetch()) {
            $product = new Product();
            $product->setId($line['id']);
            $product->setName($line['name']);
            $product->setPrice($line['price']);
            $product->setDescription($line['description']);
            $product->setCouleur($line['couleur']);
            $product->setReference($line['reference']);
            $product->setCreatorId($line['creator_id']);
            $product->setSku($line['sku']);
            $product->setBrandId($line['brand_id']);
            $product->setCategoryId($line['category_id']);
            $product->setQuantityStock($line['quantity_stock']);

            return $product;
        }
        return null;
    }

    public function productExists(string $name, string $couleur): bool
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM products WHERE name = :name AND Couleur = :couleur");
        $query->bindValue(':name', $name);
        $query->bindValue(':couleur', $couleur);
        $query->execute();
        return $query->rowCount() > 0;
    }

    public function findByReference(string $reference): ?Product
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM products WHERE reference = :reference");
        $query->bindValue(':reference', $reference);
        $query->execute();

        if ($line = $query->fetch()) {
            $product = new Product();
            $product->setId($line['id']);
            $product->setName($line['name']);
            $product->setPrice($line['price']);
            $product->setDescription($line['description']);
            $product->setCouleur($line['couleur']);
            $product->setReference($line['reference']);
            $product->setCreatorId($line['creator_id']);
            $product->setSku($line['sku']);
            $product->setBrandId($line['brand_id']);
            $product->setCategoryId($line['category_id']);
            $product->setCreatedAt(new \DateTimeImmutable($line['createAt']));
            $product->setCreatorId($line['creator_id']);
            $product->setQuantityStock($line['quantity_stock']);
            if ($line['updated_at'] != null) {
                $product->setUpdatedAt(new \DateTimeImmutable($line['updated_at']));
            }
            return $product;
        }
        return null;
    }

    public function findBySku(string $sku): ?Product
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM products WHERE sku = :sku");
        $query->bindValue(':sku', $sku);
        $query->execute();
        if ($line = $query->fetch()) {
            $product = new Product();
            $product->setId($line['id']);
            $product->setName($line['name']);
            $product->setPrice($line['price']);
            $product->setDescription($line['description']);
            $product->setCouleur($line['couleur']);
            $product->setReference($line['reference']);
            $product->setCreatorId($line['creator_id']);
            $product->setSku($line['sku']);
            $product->setBrandId($line['brand_id']);
            $product->setCategoryId($line['category_id']);
            $product->setCreatorId($line['creator_id']);
            $product->setQuantityStock($line['quantity_stock']);
            if ($line['updated_at'] != null) {
                $product->setUpdatedAt(new \DateTimeImmutable($line['updated_at']));
            }
            return $product;
        }
        return null;
    }

    public function remove(int $id): void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("DELETE FROM products WHERE id = :id");
        $query->bindValue(':id', $id);
        $query->execute();
    }

    public function update(Product $product): void
    {

        $connexion = Database::connect();
        $query = $connexion->prepare("UPDATE products SET name = :name, price = :price, description = :description, couleur = :couleur, reference = :reference, creator_id = :creator_id, sku = :sku, brand_id = :brand_id, category_id = :category_id, quantityStock =:quantityStock,updated_at = :updated_at WHERE id = :id");
        $query->bindValue(':id', $product->getId());
        $query->bindValue(':name', $product->getName());
        $query->bindValue(':price', $product->getPrice());
        $query->bindValue(':description', $product->getDescription());
        $query->bindValue(':couleur', $product->getCouleur());
        $query->bindValue(':reference', $product->getReference());
        $query->bindValue(':creator_id', $product->getCreatorId());
        $query->bindValue(':sku', $product->getSku());
        $query->bindValue(':brand_id', $product->getBrandId());
        $query->bindValue(':category_id', $product->getCategoryId());
        $product->binvalue('quantityStock', $product->getQuantityStock());
        $query->bindValue(':updated_at', $product->getUpdatedAt()->format('Y-m-d H:i:s'));
        $query->execute();
    }

    public function getProductByCategory(int $categoryId, int $offset, int $limit)
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM products WHERE category_id = :categoryId ORDER BY id DESC LIMIT :offset,:limit");
        $query->bindValue(':categoryId', $categoryId);
        $query->bindValue(':offset', $offset);
        $query->bindValue(':limit', $limit);
        $query->execute();
        $list = [];

        foreach ($query->fetchAll() as $line) {
            $category = new Category();
            $category->setId($line['id']);
            $category->setName($line['name']);
            $category->setCreatedAt(new \DateTimeImmutable($line['createAt']));
            $category->setUpdatedAt(new \DateTimeImmutable($line['updateAt']));

            $product = new Product();
            $product->setId($line['id']);
            $product->setName($line['name']);
            $product->setPrice($line['price']);
            $product->setDescription($line['description']);
            $product->setCouleur($line['couleur']);
            $product->setReference($line['reference']);
            $product->setCreatorId($line['creator_id']);
            $product->setSku($line['sku']);
            $product->setBrandId($line['brand_id']);
            $product->setCategoryId($line['category_id']);
            $product->setQuantityStock($line['quantity_stock']);

            $list[] = $product;

        }
        return $list;
    }

    public function getProductByBrand(int $brandId, int $offset, int $limit)
    {

        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM products WHERE brand_id = :brandId ORDER BY id DESC LIMIT :offset,:limit");
        $query->bindValue(':brandId', $brandId);
        $query->bindValue(':offset', $offset);
        $query->bindValue(':limit', $limit);
        $query->execute();
        $list = [];
        foreach ($query->fetchAll() as $line) {
            $brand = new Brand();
            $brand->setId($line['id']);
            $brand->setName($line['name']);
            $brand->setOrigin($line['origin']);
            $brand->setCreatedAt(new \DateTimeImmutable($line['createAt']));
            $brand->setUpdatedAt(new \DateTimeImmutable($line['updateAt']));

            $product = new Product();
            $product->setId($line['id']);
            $product->setName($line['name']);
            $product->setPrice($line['price']);
            $product->setDescription($line['description']);
            $product->setCouleur($line['couleur']);
            $product->setReference($line['reference']);
            $product->setCreatorId($line['creator_id']);
            $product->setSku($line['sku']);
            $product->setBrandId($line['brand_id']);
            $product->setCategoryId($line['category_id']);
            $product->setQuantityStock($line['quantity_stock']);

            $list[] = $product;
        }
        return $list;
    }

    public function getProductByCreator(int $creatorId, int $offset, int $limit)
    {

        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM products WHERE creator_id = :creatorId ORDER BY id DESC LIMIT :offset,:limit");
        $query->bindValue(':creatorId', $creatorId);
        $query->bindValue(':offset', $offset);
        $query->bindValue(':limit', $limit);
        $query->execute();
        $list = [];

        foreach ($query->fetchAll() as $line) {
            $creator = new Creator();
            $creator->setId($line['id']);
            $creator->setName($line['name']);
            $creator->setImageId($line['image_id']);
            $creator->setDescription($line['description']);
            $creator->setCreatedAt(new \DateTimeImmutable($line['createAt']));
            $creator->setUpdatedAt(new \DateTimeImmutable($line['updateAt']));


            $product = new Product();
            $product->setId($line['id']);
            $product->setName($line['name']);
            $product->setPrice($line['price']);
            $product->setDescription($line['description']);
            $product->setCouleur($line['couleur']);
            $product->setReference($line['reference']);
            $product->setCreatorId($line['creator_id']);
            $product->setSku($line['sku']);
            $product->setBrandId($line['brand_id']);
            $product->setCategoryId($line['category_id']);
            $product->setQuantityStock($line['quantity_stock']);
            $list[] = $product;

        }
        return $list;
    }

    public function getAllProductsWithImages(): array
    {
        $connexion = Database::connect();
        $query = $connexion->prepare(" SELECT 
    p.id AS product_id,
    p.name AS product_name,
    p.description AS product_description,
    p.price AS product_price,
    p.couleur AS product_couleur,
    i.id AS image_id,
    i.url AS image_url
FROM 
    products p
LEFT JOIN 
    images i
ON 
    p.id = i.product_id
ORDER BY 
    p.id ASC;");

        $query->execute();

        $rows = $query->fetchAll();
        // Transformer les résultats pour associer les images à leurs produits
        $products = [];
        foreach ($rows as $row) {
            $productId = $row['product_id'];

            // Si le produit n'existe pas encore, on l'ajoute avec ses attributs de base
            if (!isset($products[$productId])) {
                $products[$productId] = [
                    'id' => $row['product_id'],
                    'name' => $row['product_name'],
                    'description' => $row['product_description'],
                    'price' => $row['product_price'],
                    'couleur' => $row['product_couleur'],
                    'images' => [], // Initialisation des images en tableau
                ];
            }

            // Ajouter l'image uniquement si elle existe
            if (!empty($row['image_id'])) {
                $products[$productId]['images'][] = [
                    'id' => $row['image_id'],
                    'url' => $row['image_url'],
                ];
            }
        }

        return array_values($products); // Ré-indexer le tableau
    }
}

