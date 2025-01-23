<?php

namespace App\Entity;

class Stock
{
 private ?int $id = null;
 private ?int $productId = null;
 private ?int $quantity = null;
 private ?int $lastUpdate = null;

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
    public function getLastUpdate(): ?int
    {
        return $this->lastUpdate;
    }

    /**
     * @param int|null $lastUpdate
     */
    public function setLastUpdate(?int $lastUpdate): void
    {
        $this->lastUpdate = $lastUpdate;
    }


}