<?php

namespace App\Controller\Api;

use App\Entity\Cashbook;
use App\Entity\Expense;
use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/expenses')]
class ExpenseController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    #[Route('', name: 'api_expenses_list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $expenses = $this->em->getRepository(Expense::class)->findBy(['deletedAt' => null], ['createdAt' => 'DESC']);
        return $this->json($expenses);
    }

    #[Route('', name: 'api_expenses_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $expense = new Expense();
        $expense->setDescription($data['description']);
        $expense->setAmount($data['amount']);
        $expense->setCurrency($data['currency'] ?? null);
        $expense->setCreatedBy($this->getUser()?->getId());
        if (isset($data['wallet_id'])) {
            $wallet = $this->em->getRepository(Wallet::class)->find($data['wallet_id']);
            $expense->setWallet($wallet);
        }
        $this->em->persist($expense);
        $this->em->flush();

        $cashbook = new Cashbook();
        $cashbook->setOutAmount($data['amount']);
        $cashbook->setReferenceId($expense->getId());
        $cashbook->setReferenceType('expense');
        $cashbook->setDescription($data['description']);
        $cashbook->setCreatedBy($this->getUser()?->getId());
        $this->em->persist($cashbook);
        $this->em->flush();
        return $this->json($expense, 201);
    }

    #[Route('/{id}', name: 'api_expenses_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $expense = $this->em->getRepository(Expense::class)->find($id);
        if (!$expense || $expense->getDeletedAt()) {
            return $this->json(['error' => 'Not found'], 404);
        }
        $data = json_decode($request->getContent(), true);
        if (isset($data['description'])) $expense->setDescription($data['description']);
        if (isset($data['amount'])) $expense->setAmount($data['amount']);
        if (array_key_exists('currency', $data)) $expense->setCurrency($data['currency']);
        if (isset($data['wallet_id'])) {
            $wallet = $this->em->getRepository(Wallet::class)->find($data['wallet_id']);
            $expense->setWallet($wallet);
        }
        $expense->setUpdatedBy($this->getUser()?->getId());
        $this->em->flush();
        return $this->json($expense);
    }

    #[Route('/{id}', name: 'api_expenses_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $expense = $this->em->getRepository(Expense::class)->find($id);
        if (!$expense || $expense->getDeletedAt()) {
            return $this->json(['error' => 'Not found'], 404);
        }
        $expense->setDeletedAt(new \DateTimeImmutable());
        $expense->setUpdatedBy($this->getUser()?->getId());
        $this->em->flush();
        return $this->json(['message' => 'Deleted']);
    }
}
