<?php

namespace Emonsite\Emstorage\PhpSdk\Normalizer;

use Emonsite\Emstorage\PhpSdk\Model\Application;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer as SfObjectNormalizer;

class ApplicationNormalizer extends SfObjectNormalizer
{
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return parent::denormalize($data['application'], $class, $format, $context);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type == Application::class;
    }
}
