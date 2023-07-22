<?php

namespace App\Manager;

use App\Entity\Employee;
use App\Exception\Expected\ExpectedBadRequestJsonHttpException;
use App\Validator\Helper\ApiObjectValidator;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

class EmployeeManager
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private UserPasswordHasherInterface $passwordEncoder,
        private ApiObjectValidator $apiObjectValidator,
    ) {
    }

    public function createNewEmployee($content): Employee
    {
        /** @var Employee $employee */
        $employee = $this->apiObjectValidator->deserializeAndValidate($content, Employee::class, [
            UnwrappingDenormalizer::UNWRAP_PATH => '[employee]',
            'create' => true,
        ]);

        if ($this->doctrine->getRepository(Employee::class)->findOneBy(['email' => $employee->getEmail()])) {
            throw new ExpectedBadRequestJsonHttpException('Employee already exists.');
        }

        $this->saveEmployee($employee, $employee->getPlainPassword());

        return $employee;
    }

    // Save employee in DB
    private function saveEmployee(Employee $employee, string $password = null): void
    {
        if ($password) {
            $employee
                ->setPassword($this->passwordEncoder->hashPassword($employee, $password))
                ->setApiKey(Uuid::uuid4()->toString());
        }

        $this->doctrine->getManager()->persist($employee);
        $this->doctrine->getManager()->flush();
    }
}
