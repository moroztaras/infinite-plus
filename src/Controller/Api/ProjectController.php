<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Entity\Project;
use App\Exception\Api\BadRequestJsonHttpException;
use App\Manager\ProjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/project')]
class ProjectController extends ApiController
{
    public function __construct(
        private ProjectManager $projectManager
    ) {
    }

    // Create new project
    #[Route(path: '', name: 'api_project_create', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        /** @var Employee $employee */
        $employee = $this->getEmployee($request);

        if (!($content = $request->getContent())) {
            throw new BadRequestJsonHttpException('Bad Request.');
        }

        return $this->json(['project' => $this->projectManager->createNewProject($content)], Response::HTTP_OK, [], ['create' => true]);
    }

    // Show project
    #[Route(path: '/{uuid}', name: 'api_project_show', methods: 'GET')]
    public function show(Request $request, Project $project): JsonResponse
    {
        $this->getEmployee($request);

        return $this->json(['project' => $project], Response::HTTP_OK);
    }
}
