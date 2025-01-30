<?php

namespace App\Service;

use App\Entity\CartItem;
use App\Repository\CartItemRepository;

class CartItemService
{
    private CartItemRepository $cartItemRepository;

    public function __construct(CartItemRepository $cartItemRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
    }

    /**
     * Créer et persiste un nouvel article CartItem dans la base de données.
     */
    public function createAndPersistCartItem(
        int $cartId,
        int $productId,
        int $quantity,
        float $price,
        array $attributes
    ): CartItem {
        // Validation métier
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('La quantité doit être supérieure à 0.');
        }
        if ($price < 0) {
            throw new \InvalidArgumentException('Le prix ne peut pas être négatif.');
        }

        // Création de l'entité
        $cartItem = new CartItem();
        $cartItem->setCartId($cartId);
        $cartItem->setProductId($productId);
        $cartItem->setQuantity($quantity);
        $cartItem->setPrice($price);
        $cartItem->setTotalPrice($this->calculateTotalPrice($quantity, $price));
        $cartItem->setAttributes($attributes);
        $cartItem->setAddedAt(new \DateTimeImmutable());

        // Persistance via le repository
        $this->cartItemRepository->persists($cartItem);

        return $cartItem;
    }

    /**
     * Calcul du prix total d'un article.
     */
    public function calculateTotalPrice(int $quantity, float $price): float
    {
        return $quantity * $price;
    }

    /**
     * Permet de récupérer tous les articles d’un panier.
     */
    public function getCartItemsByCartId(int $cartId): array
    {
        // Cette méthode est supposée exister dans le dépôt
        return $this->cartItemRepository->findCartItemById($cartId);
    }

    /**
     * Met à jour la quantité d’un CartItem existant.
     */
    public function updateCartItemQuantity(CartItem $cartItem, int $newQuantity): CartItem
    {
        if ($newQuantity <= 0) {
            throw new \InvalidArgumentException('La quantité doit être supérieure à 0.');
        }

        $cartItem->setQuantity($newQuantity);
        $cartItem->setTotalPrice($this->calculateTotalPrice($newQuantity, $cartItem->getPrice()));

        $this->cartItemRepository->persists($cartItem);

        return $cartItem;
    }

    /**
     * Supprime un article du panier.
     */
    public function removeCartItem(CartItem $cartItem): void
    {
        // Supposons qu'il existe une méthode delete dans votre repository
        $this->cartItemRepository->delete($cartItem);
    }
}