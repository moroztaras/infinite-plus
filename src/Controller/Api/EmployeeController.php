<?php

namespace App\Controller\Api;

use App\Exception\Api\BadRequestJsonHttpException;
use App\Manager\EmployeeManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/employee')]
class EmployeeController extends ApiController
{
    public function __construct(
        private EmployeeManager $employeeManager
    ) {
    }

    // Employee login
    #[Route(path: '/login', name: 'api_employee_login', methods: 'POST')]
    public function login(Request $request): JsonResponse
    {
        if (!($content = $request->getContent())) {
            throw new BadRequestJsonHttpException('Bad Request.');
        }

        return $this->json(['employee' => $this->employeeManager->employeeAuthentication($content)], Response::HTTP_OK, [], ['login' => true]);
    }

    // Create new employee
    #[Route(path: '/create', name: 'api_employee_create', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        if (!($content = $request->getContent())) {
            throw new BadRequestJsonHttpException('Bad Request.');
        }

        return $this->json(['employee' => $this->employeeManager->createNewEmployee($content)], Response::HTTP_OK, [], ['registration' => true]);
    }
}
