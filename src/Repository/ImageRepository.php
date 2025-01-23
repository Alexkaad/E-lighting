<?php

namespace App\Repository;

use App\Entity\Images;

class ImageRepository
{
    public function __construct()
    {

    }

    // Methode pour ajouter une image
    public function addImage(Images $image):void
    {
        $connexion = Database::connect();
        $imageId = uniqid();
        $query = $connexion->prepare('INSERT INTO images (url,file_name,mime_type,size,uploaded_at,is_Primary,product_id) VALUES (:url,:file_name,:mime_type,:size,:uploaded_at,:is_Primary,:product_id)');
        $query->bindValue(':url', $image->getUrl());
        $query->bindValue(':file_name', $image->getFilename());
        $query->bindValue(':mime_type', $image->getMimeType());
        $query->bindValue(':size', $image->getSize(), \PDO::PARAM_INT);
        $query->bindValue(':is_Primary',$image->getIsPrimary());
        $query->bindValue(':product_id',$image->getProductId());
        $query->bindValue(':uploaded_at', $image->getUploadedAt()->format('Y-m-d H:i:s'));
        $query->execute();
    }

    // Methode pour voir l'image d'un produit par son Id
    public function getImageById(int $id): ?Images
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT url  FROM images WHERE id =:id");
        $query->bindValue(':id', $id);
        $query->execute();

        $image = $query->fetch(\PDO::FETCH_ASSOC);
        return $image ?: null;
    }

    public function getImageByUrl(string $url): ?Images
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM images WHERE url = :url");
        $query->bindValue(':url', $url);
        $query->execute();

        $image = $query->fetch(\PDO::FETCH_ASSOC);
        return $image ?: null;
    }


    public function update(Images $images): void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('UPDATE image SET url=:url, file_name=:file_name, MimeType=:mime_type, size=:size, uploaded_at=:uploaded_at WHERE id = :id');
        $query->bindValue(':url', $images->getUrl());
        $query->bindValue(':file_name', $images->getFileName());
        $query->bindValue(':mime_type', $images->getMimeType());
        $query->bindValue(':size', $images->getSize());
        $query->bindValue(':uploaded_at', $images->getUploadedAt());
        $query->execute();
    }

    public function deleteImageById(string $imageId): void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('DELETE FROM images WHERE id = :id ');
        $query->bindValue(':product_id', $imageId);
        $query->execute();
    }

    public function deleteImageByFileName(string $fileName): void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('DELETE FROM images WHERE file_name = :file_name');
        $query->bindValue(':file_name', $fileName);
        $query->execute();
    }

    public function deleteImageByUrl(string $url): void
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('DELETE FROM images WHERE url = :url');
        $query->bindValue(':url', $url);
        $query->execute();
    }

    public function getImageProductByUrl(string $url): ?Images
    {
        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM images WHERE url = :url");
        $query->bindValue(':url', $url);
        $query->execute();
        $image = $query->fetch(\PDO::FETCH_ASSOC);
        return $image ?: null;
    }

    public function getAllImages(): array
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('SELECT * FROM images');
        $query->execute();
        $images = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $images;
    }

    public function unsetPrimaryImageForProduct(int $productId): bool
    {
        try {
            $connexion = Database::connect();

            // Désactiver toutes les images principales pour un produit donné
            $query = $connexion->prepare('UPDATE images SET is_primary = 0 WHERE product_id = :product_id');
            $query->bindValue(':product_id', $productId, \PDO::PARAM_INT);

            return $query->execute() === true; // Toujours retourner un booléen
        } catch (\PDOException $e) {
            error_log('Erreur PDO dans unsetPrimaryImageForProduct : ' . $e->getMessage());
            return false;
        }
    }

    public function setPrimaryImageForProduct(int $productId, array $images): void
    {
        try {
            $connexion = Database::connect();

            // Étape 1 : Réinitialiser toutes les images comme non principales pour ce produit
            $queryUnsetPrimary = $connexion->prepare('UPDATE images SET is_primary = 0 WHERE product_id = :product_id');
            $queryUnsetPrimary->bindValue(':product_id', $productId, \PDO::PARAM_INT);
            $queryUnsetPrimary->execute();

            // Étape 2 : Parcourir les images envoyées, pour identifier et définir l'image primaire
            foreach ($images as $image) {
                $imageId = $image['id']; // ID de l'image (présupposé envoyé)
                $isPrimary = (bool)$image['isPrimary']; // Indicateur primaire envoyé avec l'image

                if ($isPrimary) {
                    // Définir cette image comme principale
                    $querySetPrimary = $connexion->prepare('UPDATE images SET is_primary = 1 WHERE id = :image_id AND product_id = :product_id');
                    $querySetPrimary->bindValue(':image_id', $imageId, \PDO::PARAM_INT);
                    $querySetPrimary->bindValue(':product_id', $productId, \PDO::PARAM_INT);
                    $querySetPrimary->execute();

                    break; // Une seule image peut être primaire, arrêter le traitement ici
                }
            }
        } catch (\PDOException $e) {
            // Enregistrement d'erreurs en cas d'exception
            error_log('Erreur PDO dans setPrimaryImageForProduct : ' . $e->getMessage());
            throw new \Exception('Impossible de définir l\'image primaire pour ce produit.');
        }
    }
    public function getPrimaryImageForProduct(int $productId): ?int
    {
        $connexion = Database::connect();
        $query = $connexion->prepare('SELECT id FROM images WHERE product_id = :product_id AND isPrimary = 1');
        $query->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $query->execute();

        // Récupérer la première ligne
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result['id'] ?? null; // Retourne null si aucune ligne trouvée
    }
}