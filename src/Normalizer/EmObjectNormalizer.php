<?php

namespace Emonsite\Emstorage\PhpSdk\Normalizer;

use Emonsite\Emstorage\PhpSdk\Model\EmObject;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EmObjectNormalizer extends ObjectNormalizer
{
    /**
     * @inheritdoc
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        // un item seul
        if (isset($data['object'])) {
            return parent::denormalize($data['object'], $class, $format, $context);
        }

        // une liste
        return parent::denormalize($data, $class, $format, $context);
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type == EmObject::class;
    }
}
