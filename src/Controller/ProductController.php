<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use http\Client\Curl\User;
use http\Env\Response;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/product')]
class ProductController extends AbstractController
{
   private ProductRepository $repository;
   private UserRepository $userRepository;


    public function __construct(
        ProductRepository $repository,




    )
    {
        $this->repository = $repository;

    }

    #[Route('/add', methods: ['POST'])]
    public function addProduct( Request $request): JsonResponse
    {
        // Lire les données envoyées depuis Angular
        $data = json_decode($request->getContent(), true);

        // Validation des données (s'assurer d'avoir tous les champs nécessaires)
        if (empty($data['name']) || empty($data['price']) || empty($data['description'])) {
            return new JsonResponse(['error' => 'Les champs requis sont manquants'], 400);
        }

        // Création de l'objet Product
        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $product->setDescription($data['description']);
        $product->setCreatorId($data['creator_id'] ?? null); // Exemple facultatif
        $product->setCouleur($data['couleur'] ?? null);
        $product->setReference($data['reference'] ?? null);
        $product->setSku($data['sku'] ?? null);
        $product->setBrandId($data['brand_id'] ?? null);
        $product->setDesigantion($data['desigantion'] ?? null);
        $product->setCategoryId($data['category_id'] ?? null);
        $product->setCreatedAt(new \DateTimeImmutable());
        $product->setQuantityStock($data['quantityStock'] ?? 0);

        // Persist du produit en base (utilisation de votre méthode `persist()`)
        $productId = $this->repository->persist($product);


        // Retourner une réponse JSON avec l'ID du nouveau produit
        return new JsonResponse([
            'productId' => $productId,
            'message' => 'Produit créé avec succès',
        ], 201); // Code 201 : Created
    }

    #[Route('/getAll', methods: ['GET','OPTIONS'])]

    public function getAllProducts(LoggerInterface $logger,Request $request): \Symfony\Component\HttpFoundation\Response
    {

        $products = $this->repository->getAllProductsWithImages();

        $logger->info('Méthodes reçue  ',['method' => $request->getMethod()]);;


        return new JsonResponse($products, JsonResponse::HTTP_OK);
    }
}

