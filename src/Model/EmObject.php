<?php

namespace Emonsite\Emstorage\PhpSdk\Model;

class EmObject implements ObjectSummaryInterface
{
    /**
     * @var string
     */
    private $id;

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
     * @var float
     */
    private $size;

    /**
     * @var string
     */
    private $sizeHuman;

    /**
     * @var array
     */
    private $meta = [];

    /**
     * @var array TODO
     */
    private $filters = [];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublicUrl()
    {
        return $this->publicUrl;
    }

    /**
     * @param string $publicUrl
     * @return $this
     */
    public function setPublicUrl($publicUrl)
    {
        $this->publicUrl = $publicUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @param string $mime
     * @return $this
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
        return $this;
    }

    /**
     * @return float
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param float $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return string
     */
    public function getSizeHuman()
    {
        return $this->sizeHuman;
    }

    /**
     * @param string $sizeHuman
     * @return $this
     */
    public function setSizeHuman($sizeHuman)
    {
        $this->sizeHuman = $sizeHuman;
        return $this;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     * @return $this
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }
}
