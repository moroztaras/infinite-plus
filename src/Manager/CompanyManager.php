<?php

namespace App\Manager;

use App\Entity\Company;
use App\Exception\Expected\ExpectedBadRequestJsonHttpException;
use App\Validator\Helper\ApiObjectValidator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

class CompanyManager
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private ApiObjectValidator $apiObjectValidator,
    ) {
    }

    public function createNewCompany($content): Company
    {
        /** @var Company $company */
        $company = $this->apiObjectValidator->deserializeAndValidate($content, Company::class, [
            UnwrappingDenormalizer::UNWRAP_PATH => '[company]',
            'create' => true,
        ]);

        if ($this->doctrine->getRepository(Company::class)->findOneByName($company->getName())) {
            throw new ExpectedBadRequestJsonHttpException('Company already exists.');
        }

        $this->saveCompany($company);

        return $company;
    }

    public function editCompany(string $content, Company $company): Company
    {
        $validationGroups = ['edit'];
        $this->apiObjectValidator->deserializeAndValidate($content, Company::class, [
            AbstractNormalizer::OBJECT_TO_POPULATE => $company,
            AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true,
            UnwrappingDenormalizer::UNWRAP_PATH => '[company]',
        ], $validationGroups);

        $this->saveCompany($company);

        return $company;
    }

    // Save company in DB
    private function saveCompany(Company $company): void
    {
        $this->doctrine->getManager()->persist($company);
        $this->doctrine->getManager()->flush();
    }

    public function removeCompany(Company $company): void
    {
        $this->doctrine->getManager()->remove($company);
        $this->doctrine->getManager()->flush();
    }
}
