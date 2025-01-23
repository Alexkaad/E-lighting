<?php

namespace App\Entity;

use phpDocumentor\Reflection\Types\Boolean;

class Images
{
    public function __construct()
    {
        if($this->uploaded_at === null)
        {
            $this->uploaded_at = new \DateTimeImmutable();
        }
    }
    private ?int $id = null;
    private ?string $url = null;

    private ?string $filename = null;
    private ?string $mime_type = null;

    private bool $isPrimary = false;

    private ?int $size = null;

    private ?int $productId = null;

    private ?\DateTimeImmutable $uploaded_at = null;

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
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     */
    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mime_type;
    }

    /**
     * @param string|null $mime_type
     */
    public function setMimeType(?string $mime_type): void
    {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if($mime_type !== null && !in_array($mime_type, $allowedMimeTypes, true))
        {
            throw new \InvalidArgumentException('Mime type must be one of: ' . implode(', ', $allowedMimeTypes));
        }
        $this->mime_type = $mime_type;
    }

    /**
     * @return int|null
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * @param int|null $size
     */
    public function setSize(?int $size): void
    {
        if($size !== null  && $size < 0)
        {
            throw new \InvalidArgumentException('Size must be positive');
        }
        $this->size = $size;
    }

    /**
     * @return bool
     */
    public function getIsPrimary(): bool
    {
        return $this->isPrimary;
    }

    /**
     * @param bool $isPrimary
     */
    public function setIsPrimary(bool $isPrimary): void
    {
        $this->isPrimary = $isPrimary;
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
    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploaded_at;
    }

    /**
     * @param \DateTimeImmutable|null $uploaded_at
     */
    public function setUploadedAt(?\DateTimeImmutable $uploaded_at): void
    {
        $this->uploaded_at = $uploaded_at;
    }
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'url' => $this->getUrl(),
            'filename' => $this->getFilename(),
            'mime_type' => $this->getMimeType(),
            'size' => $this->getSize(),
            'is_primary' => $this->getIsPrimary(),
            'product_id' => $this->getProductId(),
            'uploaded_at' => $this->getUploadedAt()? $this->getUploadedAt()->format('Y-m-d H:i:s') : null,
        ];
    }

    public function isValid(): bool
    {
        return $this->url !== null && $this->uploaded_at !== null;
    }


}