<?php

namespace App\Controller\Api;

use App\Entity\Activity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/activities')]
class ActivityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    #[Route('', name: 'api_activities_list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $activities = $this->em->getRepository(Activity::class)->findBy([], ['createdAt' => 'DESC'], 50);
        return $this->json($activities);
    }
}
