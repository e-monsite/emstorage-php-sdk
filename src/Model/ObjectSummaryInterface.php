<?php

namespace Emonsite\Emstorage\PhpSdk\Model;

interface ObjectSummaryInterface
{
    /**
     * @return string
     */
    public function getId();

    public function getCreatedAt();

    /**
     * @return string
     */
    public function getFilename();

    /**
     * @return string
     */
    public function getPublicUrl();

    /**
     * @return string
     */
    public function getMime();

    /**
     * @return float
     */
    public function getSize();

    /**
     * @return string
     */
    public function getSizeHuman();

    public function getCustomDatas();

    public function hasCustomDatas();

    public function getFilters();
}
