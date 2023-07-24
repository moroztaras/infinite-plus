<?php

namespace App\Normalizer;

use App\Entity\Project;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProjectNormalizer implements NormalizerInterface
{
    /**
     * @@param Project $object object to normalize
     * @param null $format
     *
     * @return array|bool|float|int|string
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        return [
            'uuid' => $object->getUuid(),
            'name' => $object->getName(),
            'development_start_at' => $object->getDevelopmentStartAt()->format('d-m-Y'),
            'createdAt' => $object->getCreatedAt()->format('d-m-Y'),
        ];
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($context['edit']) && isset($context['object_to_populate'])) {
            $object = $context['object_to_populate'];

            if (isset($data['name'])) {
                $object->setName($data['name']);
            }

            return $object;
        }
    }

    public function supportsNormalization($project, $format = null)
    {
        return $project instanceof Project;
    }
}
