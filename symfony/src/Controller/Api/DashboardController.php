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

    #[Route('/charts', name: 'api_dashboard_charts', methods: ['GET'])]
    public function charts(): JsonResponse
    {
        $incomeByMonth = $this->monthlyTotals(Income::class);
        $expenseByMonth = $this->monthlyTotals(Expense::class);

        $monthly = [];
        for ($i = 11; $i >= 0; $i--) {
            $first = (new \DateTimeImmutable('first day of this month'))->modify("-{$i} months");
            $key = $first->format('Y-m');
            $monthly[] = [
                'month' => $first->format('M y'),
                'income' => $incomeByMonth[$key] ?? 0.0,
                'expense' => $expenseByMonth[$key] ?? 0.0,
            ];
        }

        return $this->json([
            'monthly' => $monthly,
            'expenseByWallet' => $this->totalsByWallet(Expense::class),
            'incomeByWallet' => $this->totalsByWallet(Income::class),
        ]);
    }

    private function monthlyTotals(string $class): array
    {
        $rows = $this->em->createQueryBuilder()
            ->select('SUBSTRING(e.createdAt, 1, 7) AS ym, SUM(e.amount) AS total')
            ->from($class, 'e')
            ->where('e.deletedAt IS NULL')
            ->andWhere('e.createdAt IS NOT NULL')
            ->groupBy('ym')
            ->getQuery()
            ->getResult();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['ym']] = (float) $row['total'];
        }
        return $result;
    }

    private function totalsByWallet(string $class): array
    {
        $rows = $this->em->createQueryBuilder()
            ->select('w.name AS wname, SUM(e.amount) AS total')
            ->from($class, 'e')
            ->leftJoin('e.wallet', 'w')
            ->where('e.deletedAt IS NULL')
            ->groupBy('w.name')
            ->orderBy('total', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map(static fn (array $r) => [
            'name' => $r['wname'] ?? 'Uncategorized',
            'total' => (float) $r['total'],
        ], $rows);
    }
}
