<?php

namespace Emonsite\Emstorage\PhpSdk\Normalizer;

use Emonsite\Emstorage\PhpSdk\Model\Application;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer as SfObjectNormalizer;

class ApplicationNormalizer extends SfObjectNormalizer
{
    /**
     * @inheritdoc
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        // TODO check prepend_profile & append_profile
        return parent::denormalize($data['application'], $class, $format, $context);
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type == Application::class;
    }
}
