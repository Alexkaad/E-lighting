<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartItemController extends AbstractController
{
    #[Route('/cart-item')]
    private CartItemRepository $cartItemRepository;

    public function __construct(CartItemRepository $cartItemRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
    }

    public function addCartItem(Request $request): Response
    {
        // Récupère les données du formulaire ou de la requête JSON
        $data = json_decode($request->getContent(), true);

        // Validation (à améliorer suivant vos besoins)
        if (!isset($data['cart_id'], $data['product_id'], $data['quantity'], $data['price'], $data['attributes'])) {
            return $this->json(['error' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        // Création de l'objet CartItem
        $cartItem = new CartItem();
        $cartItem->setCartId($data['cart_id']);
        $cartItem->setProductId($data['product_id']);
        $cartItem->setQuantity((int)$data['quantity']);
        $cartItem->setPrice((int)$data['price']);
        $cartItem->setTotalPrice((int)($data['quantity'] * $data['price']));
        $cartItem->setAttributes($data['attributes']);
        $cartItem->setAddedAt(new \DateTimeImmutable());

        // Utilisation du repository pour persister l'entité
        $this->cartItemRepository->persists($cartItem);

        return $this->json(['message' => 'Cart item added successfully'], Response::HTTP_CREATED);
    }

}
