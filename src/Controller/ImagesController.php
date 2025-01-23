<?php

namespace App\Controller;

use App\Entity\Images;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use App\Service\UploadService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Image;

#[Route('/api/images')]
class ImagesController extends AbstractController
{
    private UploadService $uploadService;
    private ImageRepository $imageRepository;
    private ProductRepository $productRepository;
    private Images $image;




    public function __construct(UploadService $uploadService, ImageRepository $imageRepository,productRepository $productRepository)
        {
        $this->uploadService = $uploadService;
        $this->imageRepository = $imageRepository;
        $this->productRepository = $productRepository;

        }

        #[Route(methods: ['POST'])]
        public function uploadImage(Request $request,ImageRepository $imageRepository,UploadService $uploadService,#[MapRequestPayload]Images $images):JsonResponse
        {

            $images = $request->files->get('data');
            $productId = $request->request->get('product_id'); // ID du produit depuis la requête

            $product = $this->productRepository->findProductById($productId);
            if (!$productId) {
                return new JsonResponse(['error' => "Le produit avec l'ID $productId n'existe pas"], 404);
            }

            // Vérifier si $images est un seul fichier ou un tableau
            if ($images instanceof UploadedFile) {
                // Conversion en tableau pour gérer le single upload comme multi-upload
                $images = [$images];
            }

            // Loguer pour vous assurer que plusieurs fichiers sont bien reçus
            error_log('Fichiers reçus : ' . print_r($images, true));

            // Vérifier si c'est bien un tableau maintenant
            if (!is_array($images)) {
                return new JsonResponse(['error' => 'Les données de fichiers envoyées ne sont pas valides'], 400);
            }

            // Vérifier si aucun fichier n'est envoyé
            if (!$images || count($images) === 0) {
                return new JsonResponse(['error' => 'Aucune image envoyée'], 400);
            }

            // Boucle sur chaque image :
            foreach ($images as $key => $imageFile) {

                try {
                    // Vérifier si chaque fichier est valide
                    if (!($imageFile instanceof UploadedFile) || !$imageFile->isValid()) {
                        return new JsonResponse(['error' => "Le fichier index $key est invalide"], 400);
                    }

                    // Validation supplémentaire (ex. taille, type MIME)
                    if ($imageFile->getSize() > 5 * 1024 * 1024) { // Taille max 5 Mo
                        return new JsonResponse(['error' => "Le fichier {$imageFile->getClientOriginalName()} est trop volumineux"], 400);
                    }

                    $allowedMimeTypes = ['image/jpeg', 'image/png'];
                    if (!in_array($imageFile->getMimeType(), $allowedMimeTypes)) {
                        return new JsonResponse(['error' => "Le type MIME de {$imageFile->getClientOriginalName()} n'est pas autorisé"], 400);
                    }

                    // Extraire les données supplémentaires
                    $isPrimary = $request->request->get("isPrimary_$key", false);
                    $isPrimary = filter_var($isPrimary, FILTER_VALIDATE_BOOLEAN);

                    // Obtenir des informations uniques par image
                    $fileName = $imageFile->getClientOriginalName($imageFile);
                    $productId = $request->request->get('product_id');
                    $uploadedAt = new \DateTimeImmutable();
                    $mimeType = $imageFile->getMimeType();
                    $size = $imageFile->getSize();


                    // Télécharger le fichier via votre UploadService
                    $uploadedData = $uploadService->uploadImage($imageFile, 'product',800,800);

                    // Exemple d'URL retournée (Amazon S3 ou stockage local)
                    $fileUrl = $uploadedData['url'] ?? 'https://your-disk-path/uploads/' . $fileName;


                    // Créer une entité Image pour chaque fichier

                    $image = new Images();
                    $image->setUrl($fileUrl);
                    $image->setFileName($fileName);
                    $image->setMimeType($mimeType);
                    $image->setSize($size);
                    $image->setUploadedAt(new \DateTimeImmutable());
                    $image->setIsPrimary($isPrimary);
                    $image->setProductId($product->getId());

                    if ($isPrimary) {
                        // Réinitialiser toutes les autres images principaux du même produit
                        $this->imageRepository->unsetPrimaryImageForProduct($product->getId());
                    }

                    if ($productId) {
                        $image->setProductId($product->getId());
                    }

                    // Ajouter image en base

                    $this->imageRepository->addImage($image);

                    // Ajouter les données au tableau des uploads
                    $uploadedImages[] = $image->toArray();

                    // Étape 5 : Retourner les données de l'image au client
                $uploadedImages[]= [

                        'url' => $fileUrl,
                        'file_name' => $fileName,
                        'mime_type' => $mimeType,
                        'size' => $size,
                        'uploaded_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
                    ];

            }catch (\Exception $e) {

                    return new JsonResponse(['error' => "Erreur lors de l'upload de ->{$fileName} : {$e->getMessage()}"], 500);
                }
            }
            // Retourner une réponse JSON avec tous les fichiers uploadés
    return new JsonResponse([
        'success' => true,
        'uploaded_images' => $uploadedImages,
    ], 201);
            }


        #Route[('/{id}', methods={"GET"})]
     public function getImage(string $id):JsonResponse
     {
         $image = $this->imageRepository->getImageById($id);

         if(!$image){

             return new JsonResponse(['error' => 'Image not found'], 404);
         }
         return new JsonResponse($image);
     }

     public function getAllImages(): JsonResponse
     {
         $images = $this->imageRepository->getAllImages();
         return new JsonResponse($images);
     }

     public function deleteProductImage(string $id):JsonResponse
     {
         $image = $this->imageRepository->getImageById($id);
         if(!$image)
         {
             return new JsonResponse(['error' => 'Image not found'], 404);
         }

         $this->imageRepository->deleteImageById($id);
         return new JsonResponse(['message' => 'Image deleted'], 200);
     }
}