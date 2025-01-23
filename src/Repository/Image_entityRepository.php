<?php

namespace App\Repository;

use App\Entity\Image_entity;
use PDO;

class Image_entityRepository
{
 public function __construct(){}

    public function persist(Image_entity  $imageEntity ): void
    {
     $connexion = Database::connect();
     $query = $connexion->prepare('INSERT INTO image_entity (id, path,file_name,mime_type,size,uploaded_at) VALUES (:id,:path,:file_name,:mime_type, :size,:uploaded_at)');
     $query->bindValue(':id', $imageEntity->getId());
     $query->bindValue(':path', $imageEntity->getPath());
     $query->bindValue(':file_name', $imageEntity->getFileName());
     $query->bindValue(':mime_type',$imageEntity->getMimeType());
     $query->bindValue(':size',$imageEntity->getSize());
     $query->bindValue('uploaded_at',$imageEntity->getUploadedAt()->format('Y-m-d H:i:s'));

     $query->execute();

     $imageEntity->setId($connexion->lastInsertId());

    }
}