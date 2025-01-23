<?php

namespace App\Repository;

use App\Entity\CartItem;

class CartItemRepository
{
  public function persists(CartItem $cartItem)
  {
      $connexion = Database::connect();
      $query = $connexion->prepare('INSERT INTO cart_items (id,cart_id, product_id, quantity,price,total_price,attributes,added_at,) VALUES (:id,:cart_id, :product_id, :quantity,:price,:total_price,:attributes,:added_at)');
      $query->bindValue(':id',$cartItem->getId());
      $query->bindValue(':cart_id',$cartItem->getCartId());
      $query->bindValue(':product_id',$cartItem->getProductId());
      $query->bindValue(':quantity',$cartItem->getQuantity());
      $query->bindValue(':price',$cartItem->getPrice());
      $query->bindValue(':total_price',$cartItem->getTotalPrice());
      $query->bindValue(':attributes',$cartItem->getAttributes());
      $query->bindValue(':added_at',$cartItem->getAddedAt()->format('Y-m-d H:i:s'));
      $query->execute();

      $cartItem->setId($connexion->lastInsertId());
  }
}