<?php

namespace App\Controller\Api;

use App\Entity\Cashbook;
use App\Entity\Expense;
use App\Entity\Income;
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

        $incomeIds = [];
        $expenseIds = [];
        foreach ($entries as $entry) {
            if ($entry->getInAmount() !== null) {
                $incomeIds[] = $entry->getReferenceId();
            } else {
                $expenseIds[] = $entry->getReferenceId();
            }
        }

        $descriptions = [];
        if ($incomeIds) {
            foreach ($this->em->getRepository(Income::class)->findBy(['id' => array_unique($incomeIds)]) as $i) {
                $descriptions['income-' . $i->getId()] = $i->getDescription();
            }
        }
        if ($expenseIds) {
            foreach ($this->em->getRepository(Expense::class)->findBy(['id' => array_unique($expenseIds)]) as $e) {
                $descriptions['expense-' . $e->getId()] = $e->getDescription();
            }
        }

        $result = [];
        foreach ($entries as $entry) {
            $key = ($entry->getInAmount() !== null ? 'income-' : 'expense-') . $entry->getReferenceId();
            $result[] = [
                'id' => $entry->getId(),
                'inAmount' => $entry->getInAmount(),
                'outAmount' => $entry->getOutAmount(),
                'referenceId' => $entry->getReferenceId(),
                'description' => $descriptions[$key] ?? null,
                'createdAt' => $entry->getCreatedAt()?->format(\DateTimeInterface::ATOM),
            ];
        }

        return $this->json($result);
    }
}
