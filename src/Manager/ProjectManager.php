<?php

namespace App\Manager;

use App\Entity\Project;
use App\Exception\Expected\ExpectedBadRequestJsonHttpException;
use App\Validator\Helper\ApiObjectValidator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

class ProjectManager
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private ApiObjectValidator $apiObjectValidator,
    ) {
    }

    public function createNewProject($content): Project
    {
        /** @var Project $project */
        $project = $this->apiObjectValidator->deserializeAndValidate($content, Project::class, [
            UnwrappingDenormalizer::UNWRAP_PATH => '[project]',
            'create' => true,
        ]);

        if ($this->doctrine->getRepository(Project::class)->findOneBy(['name' => $project->getName()])) {
            throw new ExpectedBadRequestJsonHttpException('Project already exists.');
        }

        $this->saveProject($project);

        return $project;
    }

    // Remove project from DB
    public function removeProject(Project $project): void
    {
        $this->doctrine->getManager()->remove($project);
        $this->doctrine->getManager()->flush();
    }

    // Save project in DB
    private function saveProject(Project $project): void
    {
        $this->doctrine->getManager()->persist($project);
        $this->doctrine->getManager()->flush();
    }
}
