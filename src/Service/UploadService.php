<?php

namespace App\Service;

use Aws\S3\S3Client;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    private S3Client $s3Client;
    private string $bucketName;

    public function __construct(S3Client $s3Client, string $bucketName)
    {
        $this->s3Client = $s3Client;
        $this->bucketName = $bucketName;
    }


    private function getContextSpecificData(string $context): ?array
    {
        return match ($context) {
            'brand' => [
                'brand_name' => 'Some Brand Name', // Remplacez ces données par celles spécifiques à votre projet
                'campaign_description' => 'A great marketing campaign',
                'associated_products' => ['Product A', 'Product B'],
            ],
            'creator' => [
                'creator_name' => 'John Creator',
                'portfolio_link' => 'https://example.com/creator_portfolio',
            ],
            'product' => [
                'category' => 'Technology',
                'tags' => ['tech', 'innovation'],
                'price_range' => '100-200 USD',
            ],
            default => null, // Retourne null quand aucun contexte valide n'est trouvé
        };
    }

    public function uploadImage(UploadedFile $file, string $context, int $maxWidth = 800, int $maxHeight = 800, bool $simpleReturn = false): array|string
    {
        // Déterminer le dossier cible en fonction du contexte
        $folder = match ($context) {
            'product' => 'product',
            'creator' => 'creator',
            'brand' => 'brand',
            default => throw new \InvalidArgumentException("Contexte invalide pour le dossier : {$context}"),
        };

        // Générer un nom de fichier unique
        $fileName = $folder . '/' . uniqid() . '-' . $file->getClientOriginalName();
        $tempPath = sys_get_temp_dir() . '/' . $fileName;

        // Charger et redimensionner l'image
        $this->resizeImage($file->getRealPath(), $tempPath, $maxWidth, $maxHeight);

        // Upload vers AWS S3
        $result = $this->s3Client->putObject([
            'Bucket' => $this->bucketName,
            'Key' => $fileName,
            'SourceFile' => $tempPath,
        ]);

        // Supprimer le fichier temporaire
        unlink($tempPath);

        // Option de retour simplifié (par exemple, juste l'URL de l'image)
        if ($simpleReturn) {
            return $result['ObjectURL'];
        }

        // Préparer la réponse standard
        $response = [
            'url' => $result['ObjectURL'],
            'file-name' => $file->getClientOriginalName(),
            'mime-type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ];

        // Ajout des données supplémentaires spécifiques au contexte
        $response['context_data'] = $this->getContextSpecificData($context);

        return $response;

    }

    public function resizeImage(string $sourceImagePath, string $destinationPath, int $newWidth, int $newHeight): void
    {
        // Redimensionnement de l'image
        $sourceImage = imagecreatefromjpeg($sourceImagePath); // Exemple pour le JPEG
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

        // Copier et redimensionner
        imagecopyresampled(
            $resizedImage,
            $sourceImage,
            0, 0,
            0, 0,
            $newWidth, $newHeight,
            imagesx($sourceImage),
            imagesy($sourceImage)
        );

        // Vérifiez la présence du dossier de destination
        $directory = dirname($destinationPath);

        if (!is_dir($directory)) {
            if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new \Exception('Échec de la création du répertoire : ' . $directory);
            }
        }

        // Sauvegarder l'image redimensionnée
        switch (mime_content_type($sourceImagePath)) {
            case 'image/jpeg':
                if (!imagejpeg($resizedImage, $destinationPath, 85)) {
                    throw new \Exception('Échec de l\'enregistrement de l\'image JPEG');
                }
                break;
            case 'image/png':
                if (!imagepng($resizedImage, $destinationPath, 9)) {
                    throw new \Exception('Échec de l\'enregistrement de l\'image PNG');
                }
                break;
            case 'image/gif':
                if (!imagegif($resizedImage, $destinationPath)) {
                    throw new \Exception('Échec de l\'enregistrement de l\'image GIF');
                }
                break;
            default:
                throw new \Exception('Type d\'image non pris en charge lors de l\'enregistrement');
        }

        // Libérer la mémoire
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);
    }
}