<?php

namespace Emonsite\Emstorage\PhpSdk\Model;

class EmObject implements ObjectSummaryInterface
{
    /**
     * @var string
     */
    private $id;

    private $createdAt;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $publicUrl;

    /**
     * @var string
     */
    private $mime;

    /**
     * @var int
     */
    private $size;

    /**
     * @var string
     */
    private $sizeHuman;

    /**
     * @var mixed
     */
    private $customDatas;

    /**
     * @var bool
     */
    private $hasCustomDatas;

    /**
     * @var bool
     */
    private $hasFilters;

    /**
     * @var array TODO
     */
    private $filters = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @internal
     */
    public function setId(string $id): EmObject
    {
        $this->id = $id;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @internal
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt ? new \DateTimeImmutable($createdAt) : null;
        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): EmObject
    {
        $this->filename = $filename;
        return $this;
    }

    public function getPublicUrl(): ?string
    {
        return $this->publicUrl;
    }

    /**
     * @internal
     */
    public function setPublicUrl(string $publicUrl): EmObject
    {
        $this->publicUrl = $publicUrl;
        return $this;
    }

    public function getMime(): ?string
    {
        return $this->mime;
    }

    /**
     * @internal
     */
    public function setMime(?string $mime): EmObject
    {
        $this->mime = $mime;
        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * @internal
     */
    public function setSize(float $size): EmObject
    {
        $this->size = $size;
        return $this;
    }

    public function getSizeHuman(): ?string
    {
        return $this->sizeHuman;
    }

    /**
     * @internal
     */
    public function setSizeHuman(string $sizeHuman): EmObject
    {
        $this->sizeHuman = $sizeHuman;
        return $this;
    }

    public function getCustomDatas()
    {
        return $this->customDatas;
    }

    public function setCustomDatas($customDatas)
    {
        $this->customDatas = $customDatas;
        return $this;
    }

    public function hasCustomDatas(): bool
    {
        return $this->hasCustomDatas;
    }

    /**
     * @internal
     */
    public function setHasCustomDatas(bool $hasCustomDatas): EmObject
    {
        $this->hasCustomDatas = $hasCustomDatas;
        return $this;
    }

    public function hasFilters(): bool
    {
        return $this->hasFilters;
    }

    /**
     * @internal
     */
    public function setHasFilters(bool $hasFilters): EmObject
    {
        $this->hasFilters = $hasFilters;
        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(array $filters): EmObject
    {
        $this->filters = $filters;
        return $this;
    }
}
