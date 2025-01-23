<?php

namespace App\Controller;

use App\Entity\Creator;
use App\Repository\BrandRepository;
use App\Repository\CreatorRepository;
use App\Repository\ImageRepository;
use App\Service\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/creator')]
class CreatorController extends AbstractController
{

    private BrandRepository $brandRepository;
    private CreatorRepository $creatorRepository;

    public function __construct(
        CreatorRepository $repository,
        BrandRepository   $brandRepository,
        UploadService     $uploadService,
        ImageRepository   $imageRepository,
    )
    {

        $this->brandRepository = $brandRepository;
        $this->creatorRepository = $repository;


    }

    // la route qui va nous permettre de faire persister la category et l'id correspondant dans la table intermediare
// brand_category permettant de lier une category à une marque

    #[Route('/add', methods: ['POST'])]
    public function SaveCreator(#[MapRequestPayload]Request $request): JsonResponse
    {

        // Décoder le contenu JSON de la requête
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return new JsonResponse(['error' => 'Invalid JSON data.'], 400);
        }

        // Récupérer les données de la requête JSON
        $name = $data['name'] ?? null;
        $description = $data['description'] ?? null;
        $url = $data['path'] ?? null;
        $brands = $data['brand'] ?? [];

        // Validation des données
        if (empty($name)) {
            return new JsonResponse(['error' => '"name" and "image" fields are required, and "image" must be an integer.'], 400);
        }

        // Création de l'entité Creator
        $creator = new Creator();
        $creator->setName($name);
        $creator->setCreatedAt(new \DateTimeImmutable());
        $creator->setDescription(!empty($description) ? $description : '');
        $creator->setPath($url);



        // Associer les marques (brands)
        $brandEntities = [];
        foreach ($brands as $brandId) {
            $brandEntity = $this->brandRepository->findBrandById($brandId);
            if (!$brandEntity) {
                return new JsonResponse(['message' => sprintf('Brand with ID %d not found', $brandId)], 404);
            }
            $creator->addBrand($brandEntity);
            $brandEntities[] = $brandEntity;
        }

        // Persister l'entité Creator
        try {
            // Stocker dans la base de données
            $this->creatorRepository->persist($creator, $brandEntities);

        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Failed to save creator: ' . $e->getMessage()], 500);
        }

        return new JsonResponse(['message' => 'Creator created successfully'], 201);
    }


}



