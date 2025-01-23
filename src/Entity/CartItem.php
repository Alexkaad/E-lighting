<?php

namespace App\Entity;

class CartItem
{
 private ?int $id = null;
 private ?int $quantity = null;
 private ?int $productId = null;
 private ?int $cartId = null;
 private ?float $price = null;
 private ?float $totalPrice = null;
 private ?array $attributes = [];
 private ?\DateTimeImmutable $addedAt = null;
 private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     */
    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    /**
     * @param int|null $productId
     */
    public function setProductId(?int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return int|null
     */
    public function getCartId(): ?int
    {
        return $this->cartId;
    }

    /**
     * @param int|null $cartId
     */
    public function setCartId(?int $cartId): void
    {
        $this->cartId = $cartId;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     */
    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int|null
     */
    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    /**
     * @param int|null $totalPrice
     */
    public function setTotalPrice(?int $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    /**
     * @param array|null $attributes
     */
    public function setAttributes(?array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getAddedAt(): ?\DateTimeImmutable
    {
        return $this->addedAt;
    }

    /**
     * @param \DateTimeImmutable|null $addedAt
     */
    public function setAddedAt(?\DateTimeImmutable $addedAt): void
    {
        $this->addedAt = $addedAt;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeImmutable|null $updatedAt
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


}