<?php

namespace App\Entity;

class Carts
{
    private ?int $id = null;
    private ?int $userId = null;
    private ?int $productId = null;
    private ?string $status = null;
    private ?float $totalPrice = null;
    private ?string $currency = null;
    private ?string $shippingMethod = null;
    private ?string $notes= null;
    private ?\DateTimeImmutable $abandoned= null;
    private?\DateTimeImmutable $created= null;
    private? \DateTimeImmutable $updated= null;

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
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
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
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return float|null
     */
    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    /**
     * @param float|null $totalPrice
     */
    public function setTotalPrice(?float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     */
    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string|null
     */
    public function getShippingMethod(): ?string
    {
        return $this->shippingMethod;
    }

    /**
     * @param string|null $shippingMethod
     */
    public function setShippingMethod(?string $shippingMethod): void
    {
        $this->shippingMethod = $shippingMethod;
    }

    /**
     * @return string|null
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * @param string|null $notes
     */
    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getAbandoned(): ?\DateTimeImmutable
    {
        return $this->abandoned;
    }

    /**
     * @param \DateTimeImmutable|null $abandoned
     */
    public function setAbandoned(?\DateTimeImmutable $abandoned): void
    {
        $this->abandoned = $abandoned;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreated(): ?\DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @param \DateTimeImmutable|null $created
     */
    public function setCreated(?\DateTimeImmutable $created): void
    {
        $this->created = $created;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdated(): ?\DateTimeImmutable
    {
        return $this->updated;
    }

    /**
     * @param \DateTimeImmutable|null $updated
     */
    public function setUpdated(?\DateTimeImmutable $updated): void
    {
        $this->updated = $updated;
    }





}