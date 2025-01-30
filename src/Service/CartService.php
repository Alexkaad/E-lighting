<?php

namespace App\Service;

use App\Entity\Carts;

class CartService
{
    private CartItemService $cartItemService;

    public function __construct(CartItemService $cartItemService)
    {
        $this->cartItemService = $cartItemService;
    }

    /**
     * Calcule le prix total pour un panier
     */
    public function calculateCartTotal(int $cartId): float
    {
        $total = 0.0;

        // Récupère les CartItems associés au panier à partir de CartItemService
        $cartItems = $this->cartItemService->getCartItemsByCartId($cartId);


        foreach ($cartItems as $item) {

            // Il est probable que ton CartItemService contient déjà une logique de calcul (réutilisée ici)
            $total += $this->cartItemService->calculateTotalPrice($item->getQuantity(), $item->getPrice());
        }

        return $total;
    }

}