<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Category;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/category')]
class CategoryController extends AbstractController
{

    public function __construct(
        private CategoryRepository  $repo,
         private readonly BrandRepository $brandRepository,
    )
    {

    }

// la route qui va nous permettre de faire persister la category et l'id correspondant dans la table intermediare
// brand_category permettant de lier une category à une marque
    #[Route ('/add', name: 'api_category_for_brand', methods: ['POST'])]
    public function addCategoryForBand(#[MapRequestPayload] Request $request): JsonResponse
    {


        //$category = $this->serializer->deserialize($request->getContent(), Category::class, 'json');
        $data = json_decode($request->getContent(),true);

        if ($data === null || !isset($data['name'], $data['brand'])) {
            return $this->json('Invalid data: "name" and "brand" fields are required', 400);
        }

        // Vérifier si une catégorie avec le même nom existe déjà
        if ($this->repo->findByName($data['name'])) {
            return $this->json('Category already exists', 400);
        }

        // 2. Création de la catégorie
        $category = new Category();
        $category->setName($data['name']);
        $category->setCreatedAt(new \DateTimeImmutable()); // Ajoute la date actuelle

        // 3. Conversion des IDs de `brand` en objets `Brand`
        $brandIds = $data['brand'];
        if (!is_array($brandIds)) {
            return $this->json('Invalid data: "brand" must be an array of IDs', 400);
        }
            $brands = [];
        foreach ($brandIds as $brandId) {
            $brand = $this->brandRepository->findBrandById($brandId); // Votre méthode de récupération

            if (!$brand) {
                return $this->json(sprintf('Brand with ID %d not found', $brandId), 404);
            }
            $category->addBrand($brand); // Ajouter chaque objet `Brand` à la catégorie

            $brands[] = $brand;
        }

        // 4. Sauvegarder la catégorie en base de données


        $this->repo->persistForBrand($category,$brands);



        return $this->json('Category created', 201);
    }

    public function addCategory(Category $category): JsonResponse
    {


    }

    #[Route('/all', methods: ['GET'])]
    public function show(): JsonResponse
    {
        return $this->json(
            $this->repo->findAll()
        );

    }

    #[Route('/{name}', methods: ['GET'])]
    public function showforName(string $name): JsonResponse

    {
        $category = $this->repo->findByName($name);
        if (!$category) {
            throw new NotFoundHttpException('Category not found');
        }
        return $this->json($category);
    }

    #[Route('/one/{id}', methods: 'GET')]
    public function one(int $id): JsonResponse
    {

        $category = $this->repo->findById($id);
        //Si on a pas trouvé le chien pour cet id, on renvoie une erreur 404
        if (!$category) {
            throw new NotFoundHttpException("CATEGORY not found");


        }
        return $this->json($category);
    }

    #[Route('/{id}/brands/view', name: 'get_brands_by_category', methods: 'GET')]

    public function getBrandsByCategory(int $id): JsonResponse
    {
        try {
            // Appeler la méthode du repository pour obtenir les noms des marques
            $brandNames = $this->repo->getBrandNamesByCategoryId($id);

            // Retourner les résultats en JSON
            return $this->json([
                'success' => true,
                'data'    => $brandNames,
            ]);
        } catch (\Exception $e) {
            // Gérer toute exception et retourner une erreur
            return $this->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/remove/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->repo->findById($id);
        $this->repo->remove($id);
        $response = new JsonResponse('', 204);
        $response->headers->set('Content-Type', 'Category deleted');
        return $response;

    }

    #[Route('/put/{id}', methods: ['PUT'])]
    public function put(int $id, #[MapRequestPayload] Category $category): JsonResponse

    {
        $this->one($id);
        $category->setId($id);
        $this->repo->update($category);
        return $this->json($category);
    }
}

