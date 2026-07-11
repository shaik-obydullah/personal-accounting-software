<?php

namespace App\Controller\Api;

use App\Entity\Expense;
use App\Entity\Income;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/dashboard')]
class DashboardController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    #[Route('/stats', name: 'api_dashboard_stats', methods: ['GET'])]
    public function stats(): JsonResponse
    {
        $incomeRepo = $this->em->getRepository(Income::class);
        $expenseRepo = $this->em->getRepository(Expense::class);

        $totalIncome = $incomeRepo->createQueryBuilder('i')
            ->select('SUM(i.amount)')
            ->where('i.deletedAt IS NULL')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        $totalExpense = $expenseRepo->createQueryBuilder('e')
            ->select('SUM(e.amount)')
            ->where('e.deletedAt IS NULL')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        $incomeCount = $incomeRepo->count(['deletedAt' => null]);
        $expenseCount = $expenseRepo->count(['deletedAt' => null]);

        return $this->json([
            'totalIncome' => (float) $totalIncome,
            'totalExpense' => (float) $totalExpense,
            'balance' => (float) $totalIncome - (float) $totalExpense,
            'incomeCount' => $incomeCount,
            'expenseCount' => $expenseCount,
        ]);
    }
}
