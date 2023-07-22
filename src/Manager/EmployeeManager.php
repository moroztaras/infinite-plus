<?php

namespace App\Manager;

use App\Entity\Employee;
use App\Exception\Api\BadRequestJsonHttpException;
use App\Exception\Expected\ExpectedBadRequestJsonHttpException;
use App\Model\LoginModel;
use App\Repository\EmployeeRepository;
use App\Validator\Helper\ApiObjectValidator;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

class EmployeeManager
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private UserPasswordHasherInterface $passwordEncoder,
        private ApiObjectValidator $apiObjectValidator,
        private EmployeeRepository $employeeRepository,
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

    public function editEmployee($content, Employee $employee): Employee
    {
        $validationGroups = ['edit'];
        $this->apiObjectValidator->deserializeAndValidate($content, Employee::class, [
            AbstractNormalizer::OBJECT_TO_POPULATE => $employee,
            AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true,
            UnwrappingDenormalizer::UNWRAP_PATH => '[employee]',
        ], $validationGroups);

        $this->saveEmployee($employee, $employee->getPlainPassword());

        return $employee;
    }

    public function employeeAuthentication(string $content): Employee
    {
        /** @var LoginModel $login */
        $login = $this->apiObjectValidator->deserializeAndValidate($content, LoginModel::class, [
            UnwrappingDenormalizer::UNWRAP_PATH => '[login]',
        ]);
        $employee = $this->employeeRepository->findOneByEmail($login->getEmail());

        if (!$employee) {
            throw new BadRequestJsonHttpException('Authentication error');
        }

        if ($this->passwordEncoder->isPasswordValid($employee, $login->getPlainPassword())) {
            $employee->setApiKey(Uuid::uuid4());
            $this->saveEmployee($employee, null);
        }

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
