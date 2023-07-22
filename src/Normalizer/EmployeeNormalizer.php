<?php

namespace App\Normalizer;

use App\Entity\Employee;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EmployeeNormalizer implements NormalizerInterface
{
    /**
     * @@param Employee $object object to normalize
     * @param null $format
     *
     * @return array|bool|float|int|string
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        if (isset($context['registration']) || isset($context['login'])) {
            return [
                'apiKey' => $object->getApiKey(),
            ];
        }

        return [
            'apiKey' => $object->getApiKey(),
            'uuid' => $object->getUuid(),
            'firstName' => $object->getFirstName(),
            'lastName' => $object->getLastName(),
            'email' => $object->getEmail(),
            'gender' => $object->getGender(),
            'birthday' => $object->getBirthday()->format('d-m-Y'),
            'country' => $object->getCountry(),
        ];
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($context['edit']) && isset($context['object_to_populate'])) {
            $object = $context['object_to_populate'];

            if (isset($data['firstName'])) {
                $object->setFirstName($data['firstName']);
            }

            if (isset($data['lastName'])) {
                $object->setLastName($data['lastName']);
            }

            if (isset($data['email'])) {
                $object->setEmail($data['email']);
            }

            if (isset($data['gender'])) {
                $object->setGender($data['gender']);
            }

            if (isset($data['country'])) {
                $object->setCountry($data['country']);
            }

            if (isset($data['birthday'])) {
                $object->setBirtDay($data['birthday']);
            }

            return $object;
        }
    }

    public function supportsNormalization($employee, $format = null)
    {
        return $employee instanceof Employee;
    }
}
