<?php

namespace App\Controller;

use App\Entity\Image_entity;
use App\Repository\Image_entityRepository;
use App\Service\UploadService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ImageEntityController extends AbstractController
{
    public function __construct(

        Image_entityRepository $image_entityRepository,
        UploadService          $uploadService
    )
    {
        $this->image_entityRepository = $image_entityRepository;
        $this->uploadService = $uploadService;
    }


    #[Route ('/api/image_entity', methods: ['POST'])]
    public function addImageEntity(Request $request,LoggerInterface $logger)
    {

        // Récupération des données depuis la requête
        $image = $request->files->get('image') ?? $request->files->get('file');
        $context = $request->get('context');

        if (!$image || !$image->isValid()) {
            return new JsonResponse(['error' => 'Aucune image envoyée'], 400);
        }

        // Validation du contexte
        if (!is_string($context) || !in_array($context, ['brand', 'creator'])) {
            return new JsonResponse(['error' => 'Contexte non valide ou manquant'], 400);
        }


        try {
            $uploadedData = $this->uploadService->uploadImage($image, $context, 800, 800);

            $imageEntity = new Image_entity();
            $imageEntity->setPath($uploadedData['url']);
            $imageEntity->setMimeType($uploadedData['mime-type']);
            $imageEntity->setSize($uploadedData['size']);
            $imageEntity->setFileName($uploadedData['file-name']);
            $imageEntity->setUploadedAt(new \DateTimeImmutable());

            $this->image_entityRepository->persist($imageEntity);
            $logger->info('Méthodes reçue  ',['method' => $request->getMethod()]);;
            return new JsonResponse([
                'url' => $uploadedData['url'] ?? null,
                'context' => $context,
                'file_name' => $uploadedData['file-name'] ?? null,
                'size' => $uploadedData['size'] ?? null,
                'mime-type' => $uploadedData['mime-type'] ?? null,
                'uploaded_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }


    }
}

