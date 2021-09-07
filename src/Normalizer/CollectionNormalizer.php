<?php

namespace Emonsite\Emstorage\PhpSdk\Normalizer;

use Emonsite\Emstorage\PhpSdk\Model\Collection;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;

/**
 * Normalise une collection d'éléments
 */
class CollectionNormalizer extends ArrayDenormalizer
{
    /**
     * Nom du parametre à passer dans le contexte pour donner la clé dans laquelle les éléments se trouvent
     */
    const ELEMENTS_KEY = 'elements_key';

    /**
     * @inheritdoc
     */
    public function denormalize($data, string $type, $format = null, array $context = []): array
    {
        $collection = new Collection();

        $collection->setNav($data['nav']);
        $collection->setLinks($data['links']);
        $collection->setElements(parent::denormalize($data[$context[self::ELEMENTS_KEY]], $class, $format, $context));

        return $collection;
    }
}
