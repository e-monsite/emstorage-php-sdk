<?php

namespace Emonsite\Emstorage\PhpSdk\Model;

/**
 * Les informations d'une application EmStorage
 */
class Application
{
    const VISIBILITY_PUBLIC = 'public';
    const VISIBILITY_DOMAIN = 'domain';
    const VISIBILITY_API = 'API';

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
    private $profilePolicy;

    /**
     * @var string
     */
    private $profileTag;

    /**
     * @var int
     */
    private $apikeyCount;

    /**
     * @var int
     */
    private $containerCount;

    /**
     * @var int
     */
    private $profileCount;

    /**
     * @var int
     */
    private $objectCount;

    /**
     * @var int
     */
    private $size;

    /**
     * @var string
     */
    private $sizeHuman;

    /**
     * @var array
     */
    private $accept = [];

    /**
     * @var array
     */
    private $limits = [];

    /**
     * @var ProfileSummary|null
     */
    private $prependProfile;

    /**
     * @var ProfileSummary|null
     */
    private $appendProfile;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Application
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Application
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param mixed $visibility
     * @return Application
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfilePolicy()
    {
        return $this->profilePolicy;
    }

    /**
     * @param mixed $profilePolicy
     * @return Application
     */
    public function setProfilePolicy($profilePolicy)
    {
        $this->profilePolicy = $profilePolicy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfileTag()
    {
        return $this->profileTag;
    }

    /**
     * @param mixed $profileTag
     * @return Application
     */
    public function setProfileTag($profileTag)
    {
        $this->profileTag = $profileTag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApikeyCount()
    {
        return $this->apikeyCount;
    }

    /**
     * @param mixed $apikeyCount
     * @return Application
     */
    public function setApikeyCount($apikeyCount)
    {
        $this->apikeyCount = $apikeyCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContainerCount()
    {
        return $this->containerCount;
    }

    /**
     * @param mixed $containerCount
     * @return Application
     */
    public function setContainerCount($containerCount)
    {
        $this->containerCount = $containerCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfileCount()
    {
        return $this->profileCount;
    }

    /**
     * @param mixed $profileCount
     * @return Application
     */
    public function setProfileCount($profileCount)
    {
        $this->profileCount = $profileCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getObjectCount()
    {
        return $this->objectCount;
    }

    /**
     * @param mixed $objectCount
     * @return Application
     */
    public function setObjectCount($objectCount)
    {
        $this->objectCount = $objectCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     * @return Application
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSizeHuman()
    {
        return $this->sizeHuman;
    }

    /**
     * @param mixed $sizeHuman
     * @return Application
     */
    public function setSizeHuman($sizeHuman)
    {
        $this->sizeHuman = $sizeHuman;
        return $this;
    }

    /**
     * @return array
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * @param array $accept
     * @return Application
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLimits()
    {
        return $this->limits;
    }

    /**
     * @param mixed $limits
     * @return Application
     */
    public function setLimits($limits)
    {
        $this->limits = $limits;
        return $this;
    }

    /**
     * @return ProfileSummary|null
     */
    public function getPrependProfile()
    {
        return $this->prependProfile;
    }

    /**
     * @param mixed $prependProfile
     * @return Application
     */
    public function setPrependProfile($prependProfile = null)
    {
        $this->prependProfile = $prependProfile;
        return $this;
    }

    /**
     * @return ProfileSummary|null
     */
    public function getAppendProfile()
    {
        return $this->appendProfile;
    }

    /**
     * @param mixed $appendProfile
     * @return Application
     */
    public function setAppendProfile($appendProfile = null)
    {
        $this->appendProfile = $appendProfile;
        return $this;
    }
}
