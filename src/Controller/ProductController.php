<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function addProduct(Request $request): JsonResponse
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

    #[Route('/getAll', methods: ['GET', 'OPTIONS'])]
    public function getAllProducts(LoggerInterface $logger, Request $request): \Symfony\Component\HttpFoundation\Response
    {

        $products = $this->repository->getAllProductsWithImages();

        $logger->info('Méthodes reçue  ', ['method' => $request->getMethod()]);;


        return new JsonResponse($products, JsonResponse::HTTP_OK);
    }

    #[Route('/get/{id}', methods: ['GET', 'OPTIONS'])]
    public function getOneProduct(int $id): JsonResponse
    {
        try {
            // Appel de la méthode dans le repository
            $product = $this->repository->getProductWithHispictures($id);

            if (!$product) {
                // Retourner une réponse JSON avec un code 404 si le produit n'est pas trouvé
                return $this->json([
                    'message' => 'Produit introuvable',
                ], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
            }

            // Retourner la réponse JSON avec les données du produit
            return $this->json([
                'success' => true,
                'product' => $product,
            ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);

        } catch (\Exception $e) {
            // Gestion des erreurs
            return $this->json([
                'success' => false,
                'message' => 'Une erreur est survenue : ' . $e->getMessage(),
            ], \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route ('/category/{categoryId}', methods: ['GET'])]
    public function getProductsByCategory(int $categoryId, Request $request): JsonResponse
    {
        // Récupérer les paramètres de pagination depuis la requête
        $offset = $request->query->get('offset', 0); // Valeur par défaut = 0
        $limit = $request->query->get('limit', 10);  // Valeur par défaut = 10

        // Appeler le service pour récupérer les produits avec leurs images
        $products = $this->repository->getProductByCategory($categoryId, (int)$offset, (int)$limit);

        // Retourner les résultats au format JSON
        return $this->json($products, 200);
    }

    #[Route('/brand/{brandId}', methods: ['GET'])]
    public function getProductByBrand(int $brandId, Request $request)
    {
        // Récupérer les paramètres de pagination depuis la requête
        $offset = $request->query->get('offset', 0); // Valeur par défaut = 0
        $limit = $request->query->get('limit', 10);  // Valeur par défaut = 10

        // Appeler le service pour récupérer les produits avec leurs images
        $products = $this->repository->getProductByBrand($brandId, (int)$offset, (int)$limit);

        // Retourner les résultats au format JSON
        return $this->json($products, 200);
    }
}

