<?php

namespace App\Entity;

class Category
{
    private ?int $id = null;

    private ?string $name = null;

    private ?\DateTimeImmutable $createdAt = null;

    private ?\DateTimeImmutable $updatedAt = null;

    private ?array $brand = [];

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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable|null $createdAt
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
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

    /**
     * @return array|null
     */
    public function getBrand(): ?array
    {
        return $this->brand;
    }

    /**
     * @param array|null $brand
     */
    public function setBrand(?array $brand): void
    {
        $this->brand = $brand;
    }

    public function addBrand(Brand $brand): void
    {
            $this->brand[] = $brand;
    }

    public function removeBrand(Brand $brand): void
    {
        if (($key = array_search($brand, $this->brand, true)) !== false) {
            unset($this->brand[$key]);
        }
    }

}