<?php

namespace App\Controller\Api;

use App\Entity\Cashbook;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/cashbook')]
class CashbookController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    #[Route('', name: 'api_cashbook_list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $entries = $this->em->getRepository(Cashbook::class)->findBy(['deletedAt' => null], ['createdAt' => 'DESC']);
        return $this->json($entries);
    }
}
