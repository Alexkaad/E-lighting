<?php

namespace App\Entity;

use GuzzleHttp\Psr7\UploadedFile;

class Creator
{
    private ?int $id=null;

    private ?string $name=null;

    private ?string $description=null;

    private ?\DateTimeImmutable $createdAt = null;

    private ?\DateTimeImmutable $updatedAt = null;

    private ?string $path = null;

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
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     */
    public function setPath(?string $path): void
    {
        $this->path = $path;
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