<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use App\Service\CartItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class CartItemController extends AbstractController
{

    private CartItemRepository $cartItemRepository;
    private CartItemService $cartItemService;

    public function __construct(CartItemRepository $cartItemRepository, CartItemService $cartItemService)
    {
        $this->cartItemRepository = $cartItemRepository;
        $this->cartItemService = $cartItemService;
    }

    #[Route('/cart-item/{cartItemId}/items', methods: 'POST')]
    public function createCartItem(Request $request,int $cartId): Response
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Récupérer les informations nécessaires depuis la requête
            $productId = $data['productId'] ?? null;
            $quantity = $data['quantity'] ?? null;
            $price = $data['price'] ?? null;
            $attributes = $data['attributes'] ?? [];

            // Validation basique
            if (!$productId || !$quantity || !$price) {
                return new JsonResponse(['error' => 'Données incomplètes'], 400);
            }

            // Créer un nouvel item via le service
            $cartItem = $this->cartItemService->createAndPersistCartItem(
                $cartId,
                $productId,
                $quantity,
                $price,
                $attributes
            );

            return new JsonResponse(['message' => 'Produit ajouté au panier', 'cartItem' => $cartItem], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }

    }

    #[Route('/cart-item/{cartItemId}/items', methods: 'GET')]
    public function getCartItems(int $cartId): JsonResponse
    {
        try {
            $cartItems = $this->cartItemService->getCartItemsByCartId($cartId);

            return new JsonResponse(['cartItems' => $cartItems], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @param int $id, Request $request
     * @return JsonResponse
     * Mettre à jour la quantité d'un produit dans le panier
     */
    #[Route('/cart-item/{cartItemId}/items', methods: 'PUT')]
    public function updateCartItem(Request $request, int $id): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $newQuantity = $data['quantity'] ?? null;
            if (null === $newQuantity) {
                return new JsonResponse(['error' => 'La quantité est requise pour mettre à jour'], 400);
            }

            // Récupérer l'item (vous devez implémenter cela correctement dans le service/repository)
            $cartItemData = $this->cartItemService->getCartItemsByCartId($id);
            $cartItem = $cartItemData[0] ?? null;

            if (!$cartItem instanceof CartItem) {
                return new JsonResponse(['error' => 'Produit introuvable dans le panier'], 404);
            }

            // Mise à jour de la quantité via le service
            $updatedCartItem = $this->cartItemService->updateCartItemQuantity($cartItem, (int) $newQuantity);

            return new JsonResponse([
                'message' => 'Quantité mise à jour avec succès',
                'cartItem' => $updatedCartItem,
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * @param int $id
     * @return JsonResponse
     * Supprimer un produit du panier
     */
    #[Route('/cart-item/{id}/items', methods: 'DELETE')]
    public function deleteCartItem(int $id): JsonResponse
    {
        try {
            // Récupérer l'item
            $cartItemData = $this->cartItemService->getCartItemsByCartId($id);
            $cartItem = $cartItemData[0] ?? null;

            if (!$cartItem instanceof CartItem) {
                return new JsonResponse(['error' => 'Produit introuvable dans le panier'], 404);
            }

            // Suppression via le service
            $this->cartItemService->removeCartItem($cartItem);

            return new JsonResponse(['message' => 'Produit supprimé du panier avec succès'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }

    }


}
