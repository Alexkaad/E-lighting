<?php

namespace App\Entity;

class Image_entity
{
    private ?int $id = null;
    private ?string $path = null;
    private ?string $file_name = null;
    private ?string $mime_type = null;

    private ?int $size = null;
    private ?\DateTimeImmutable $uploaded_at = null;

    private ?string $context = null;

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
    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    /**
     * @param string|null $file_name
     */
    public function setFileName(?string $file_name): void
    {
        $this->file_name = $file_name;
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
        $this->size = $size;
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

    /**
     * @return string|null
     */
    public function getContext(): ?string
    {
        return $this->context;
    }

    /**
     * @param string|null $context
     */
    public function setContext(?string $context): void
    {
        $this->context = $context;
    }


}