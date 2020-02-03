<?php

namespace Emonsite\Emstorage\PhpSdk\Model;

/**
 * Les informations d'une application EmStorage
 * https://admin.emstorage.fr/api/models#application
 * TODO des models pour les arrays
 */
class Application
{
    public const VISIBILITY_PUBLIC = 'public';
    public const VISIBILITY_PRIVATE = 'private';

    public const POLICY_GET = 'get';
    public const POLICY_EXT = 'ext';
    public const POLICY_SEG = 'seg';

    /**
     * @var string
     */
    private $id;

    /**
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $visibility;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $profilePolicy;

    /**
     * @var string
     */
    private $profileTag;

    /**
     * @var string
     */
    private $publicUrl;

    /**
     * @var array
     */
    private $stats = [];

    /**
     * @var array
     */
    private $limits = [];

    /**
     * @var array
     */
    private $offer = [];

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @internal
     */
    public function setId(string $id): Application
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
    public function setCreatedAt(string $createdAt): Application
    {
        $this->createdAt = new \DateTimeImmutable($createdAt);
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Application
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Application
    {
        $this->description = $description;
        return $this;
    }

    public function getVisibility(): string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): Application
    {
        $this->visibility = $visibility;
        return $this;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): Application
    {
        $this->domain = $domain;
        return $this;
    }

    public function getProfilePolicy(): string
    {
        return $this->profilePolicy;
    }

    public function setProfilePolicy(string $profilePolicy): Application
    {
        $this->profilePolicy = $profilePolicy;
        return $this;
    }

    public function getProfileTag(): string
    {
        return $this->profileTag;
    }

    public function setProfileTag(string $profileTag): Application
    {
        $this->profileTag = $profileTag;
        return $this;
    }

    public function getPublicUrl(): string
    {
        return $this->publicUrl;
    }

    /**
     * @internal
     */
    public function setPublicUrl(string $publicUrl): Application
    {
        $this->publicUrl = $publicUrl;
        return $this;
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    /**
     * @internal
     */
    public function setStats(array $stats): Application
    {
        $this->stats = $stats;
        return $this;
    }

    public function getLimits(): array
    {
        return $this->limits;
    }

    public function setLimits(array $limits): Application
    {
        $this->limits = $limits;
        return $this;
    }

    public function getOffer(): array
    {
        return $this->offer;
    }

    /**
     * @internal
     */
    public function setOffer(array $offer): Application
    {
        $this->offer = $offer;
        return $this;
    }
}
