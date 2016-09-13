<?php

namespace Emonsite\Emstorage\PhpSdk\Model;

class ProfileSummary
{
    private $id;

    private $name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return ProfileSummary
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return ProfileSummary
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    
}
