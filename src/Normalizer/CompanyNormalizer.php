<?php

namespace App\Normalizer;

use App\Entity\Company;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CompanyNormalizer implements NormalizerInterface
{
    /**
     * @@param Company $object object to normalize
     * @param null $format
     *
     * @return array|bool|float|int|string
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        return [
            'name' => $object->getName(),
            'address' => $object->getAddress(),
            'country' => $object->getCountry(),
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

            if (isset($data['address'])) {
                $object->setAddress($data['address']);
            }

            if (isset($data['country'])) {
                $object->setCountry($data['country']);
            }

            return $object;
        }
    }

    public function supportsNormalization($company, $format = null)
    {
        return $company instanceof Company;
    }
}
