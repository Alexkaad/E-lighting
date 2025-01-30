<?php

namespace App\Service;

use App\Entity\favorites;
use App\Repository\FavoritesRepository;
use App\Repository\UserRepository;
use App\Security\UserProvider;


class FavoritesService
{

    private $favoritesRepository;
    private UserProvider $userProvider;
    private UserRepository $userRepository;

    public function __construct(FavoritesRepository $favoritesRepository, UserRepository $userRepository)
    {
        $this->favoritesRepository = $favoritesRepository;
           $this->userRepository = $userRepository;
    }

    // Liker un produit
    public function likeProduct(int $userId, int $productId): string
    {
        // Vérifier si le produit est déjà liké
        if ($this->favoritesRepository->isFavorite($userId, $productId)) {
            return "Vous avez déjà liké ce produit.";
        }

        // Ajouter le like
        $this->favoritesRepository->addFavorite($userId, $productId);

        return "Produit liké avec succès.";
    }

    // Supprimer un like
    public function unlikeProduct(int $userId, int $productId): string
    {
        // Vérifier si le produit est dans les favoris
        if (!$this->favoritesRepository->isFavorite($userId, $productId)) {
            return "Ce produit n'est pas dans vos favoris.";
        }

        // Supprimer le like
        $this->favoritesRepository->removeFavorites($userId, $productId);

        return "Produit supprimé des favoris.";
    }


    // Récupérer les produits likés par l'utilisateur connecté
    public function getLikedProducts(string $email): array
    {
        // Récupérer l'utilisateur connecté
        $user = $this->userRepository->findByOne($email);

        if (!$user) {
            throw new \Exception("Aucun utilisateur connecté."); // Ou vous pouvez gérer ça autrement
        }

        // Récupérer les favoris de l'utilisateur
        $favorites = $this->favoritesRepository->getFavoritesByUserId($user->getId());

        if (empty($favorites)) {
            return []; // Aucun produit liké
        }

        // Extraire les IDs des produits likés
        $productIds = [];
        foreach ($favorites as $favorite) {
            $productIds[] = $favorite->getProduct()->getId();
        }

        // Récupérer les détails des produits
        return $this->fetchProductDetails($productIds);
    }

    // Récupérer les détails des produits (service ou base de données fictive)
    private function fetchProductDetails(array $productIds): array
    {
        if (empty($productIds)) {
            return [];
        }

        // Simuler un appel à une base ou un service
        $mockedProducts = [
            1 => ['id' => 1, 'name' => 'Produit A', 'price' => 10],
            2 => ['id' => 2, 'name' => 'Produit B', 'price' => 20],
            3 => ['id' => 3, 'name' => 'Produit C', 'price' => 30],
        ];

        // Filtrer les produits pour ne conserver que ceux des IDs donnés
        return array_filter($mockedProducts, function ($key) use ($productIds) {
            return in_array($key, $productIds);
        }, ARRAY_FILTER_USE_KEY);
    }


}