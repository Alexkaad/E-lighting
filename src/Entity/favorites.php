<?php

namespace App\Entity;

class favorites
{

    private ?int $id = null;
    private ?int $userId = null;
    private ?int $productId = null;
    private ?\DateTimeImmutable $addedAt = null;

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

}