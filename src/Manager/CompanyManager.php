<?php

namespace App\Manager;

use App\Entity\Company;
use App\Exception\Expected\ExpectedBadRequestJsonHttpException;
use App\Validator\Helper\ApiObjectValidator;
use Doctrine\Persistence\ManagerRegistry;
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

    // Save company in DB
    private function saveCompany(Company $company): void
    {
        $this->doctrine->getManager()->persist($company);
        $this->doctrine->getManager()->flush();
    }
}
