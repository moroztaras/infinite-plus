<?php

namespace App\Controller\Api;

use App\Entity\Company;
use App\Exception\Api\BadRequestJsonHttpException;
use App\Manager\CompanyManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/company')]
class CompanyController extends ApiController
{
    public function __construct(
        private CompanyManager $companyManager
    ) {
    }

    // Create new company
    #[Route(path: '', name: 'api_company_create', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        $this->getEmployee($request);

        if (!($content = $request->getContent())) {
            throw new BadRequestJsonHttpException('Bad Request.');
        }

        return $this->json(['company' => $this->companyManager->createNewCompany($content)], Response::HTTP_OK, [], ['create' => true]);
    }

    // Show company
    #[Route(path: '/{uuid}', name: 'api_company_show', methods: 'GET')]
    public function show(Company $company): JsonResponse
    {
        return $this->json(['company' => $company], Response::HTTP_OK, [], ['edit' => true]);
    }

    // Edit company
    #[Route(path: '/{uuid}', name: 'api_company_create', methods: 'PUT')]
    public function edit(Request $request, Company $company): JsonResponse
    {
        $this->getEmployee($request);

        if (!($content = $request->getContent())) {
            throw new BadRequestJsonHttpException('Bad Request.');
        }

        return $this->json(['company' => $this->companyManager->editCompany($content, $company)], Response::HTTP_OK, [], ['edit' => true]);
    }
}
