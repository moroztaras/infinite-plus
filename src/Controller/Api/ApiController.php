<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Exception\Api\BadRequestJsonHttpException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiController.
 */
abstract class ApiController extends AbstractController
{
    protected ManagerRegistry $doctrine;

    /**
     * @required
     */
    public function setDoctrine(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    protected function getDoctrine(): ManagerRegistry
    {
        return $this->doctrine;
    }

    protected function getCurrentUser(Request $request): ?Employee
    {
        $employee = $this->doctrine->getManager()->getRepository(Employee::class)->findOneBy([
            'apiKey' => $request->headers->get('x-api-key'),
        ]);

        if (!$employee) {
            throw new BadRequestJsonHttpException('Employee Not Unauthorized');
        }

        return $employee instanceof Employee ? $employee : null;
    }
}
