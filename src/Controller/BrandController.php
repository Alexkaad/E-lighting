<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Category;
use App\Repository\BrandRepository;

use App\Service\UploadService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/brand')]
class BrandController extends AbstractController
{


    public function __construct(
        private BrandRepository $repository,
        private UploadService   $uploadService,

    )
    {

    }

    #[Route('/add', methods: ['POST'])]
    public function addBrand(Request $request,LoggerInterface $logger): JsonResponse
    {
        // Récupération des données JSON envoyées dans la requête
        $data = json_decode($request->getContent(), true);
        $origin = $data['origin'];
        $name = $data['name'];
        $path= $data['path'];

        if (!$data || !isset($data['name'], $data['origin'])) {
            return new JsonResponse(['error' => 'Nom, contexte et origine sont obligatoires'], 400);
        }

        // Récupération de l'URL de l'image (optionnelle)
        $imageUrl = $data['path'] ?? null;

        if ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return new JsonResponse(['error' => 'URL de l\'image invalide'], 400);
        }

        // Création de la marque
        $brand = new Brand();
        $brand->setName($data['name']);
        $brand->setOrigin($data['origin']);
        $brand->setPath($imageUrl); // Stocke uniquement l'URL de l'image
        $brand->setCreatedAt(new \DateTimeImmutable());

        // Sauvegarde dans la base de données
        $this->repository->persist($brand);

        $logger->info('Brand created successfully', [
            'id' => $brand->getId(),
            'name' => $brand->getName(),
            'url' => $brand->getPath(),
        ]);

        // Retourne une réponse JSON de succès
        return new JsonResponse([
            'id' => $brand->getId(),
            'name' => $brand->getName(),
            'origin' => $brand->getOrigin(),
            'path' => $brand->getPath(),
        ], 201);


    }
}