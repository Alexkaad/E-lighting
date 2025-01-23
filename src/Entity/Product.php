<?php

namespace App\Entity;

class Product
{

    private ?int $id = null;
    private ?string $name = null;
    private ?string $description = null;
    private ?float $price = null;
    private ?string $couleur = null;
    private ?string $sku = null;
    private ?string $reference = null;

    private ?\DateTimeImmutable $createdAt = null;
    private ?\DateTimeImmutable $updatedAt = null;

    private ?int $categoryId = null;
    private ?int $brandId = null;
    private ?int $creatorId = null;

    private ?string $desigantion = null;

    private ?int $quantityStock = null;
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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
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
     * @return string|null
     */
    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    /**
     * @param string|null $couleur
     */
    public function setCouleur(?string $couleur): void
    {
        $this->couleur = $couleur;
    }

    /**
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @param string|null $sku
     */
    public function setSku(?string $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string|null $reference
     */
    public function setReference(?string $reference): void
    {
        $this->reference = $reference;
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
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * @param int|null $categoryId
     */
    public function setCategoryId(?int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return int|null
     */
    public function getBrandId(): ?int
    {
        return $this->brandId;
    }

    /**
     * @return string|null
     */
    public function getDesigantion(): ?string
    {
        return $this->desigantion;
    }

    /**
     * @param string|null $desigantion
     */
    public function setDesigantion(?string $desigantion): void
    {
        $this->desigantion = $desigantion;
    }



    /**
     * @param int|null $brandId
     */
    public function setBrandId(?int $brandId): void
    {
        $this->brandId = $brandId;
    }


    /**
     * @return int|null
     */
    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }

    /**
     * @param int|null $creatorId
     */
    public function setCreatorId(?int $creatorId): void
    {
        $this->creatorId = $creatorId;
    }

    /**
     * @return int|null
     */
    public function getQuantityStock(): ?int
    {
        return $this->quantityStock;
    }

    /**
     * @param int|null $quantityStock
     */
    public function setQuantityStock(?int $quantityStock): void
    {
        $this->quantityStock = $quantityStock;
    }



}