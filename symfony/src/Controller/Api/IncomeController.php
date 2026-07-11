<?php

namespace App\Controller\Api;

use App\Entity\Cashbook;
use App\Entity\Income;
use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/incomes')]
class IncomeController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    #[Route('', name: 'api_incomes_list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $incomes = $this->em->getRepository(Income::class)->findBy(['deletedAt' => null], ['createdAt' => 'DESC']);
        return $this->json($incomes);
    }

    #[Route('', name: 'api_incomes_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $income = new Income();
        $income->setDescription($data['description']);
        $income->setAmount($data['amount']);
        $income->setCurrency($data['currency'] ?? null);
        $income->setCreatedBy($this->getUser()?->getId());
        if (isset($data['wallet_id'])) {
            $wallet = $this->em->getRepository(Wallet::class)->find($data['wallet_id']);
            $income->setWallet($wallet);
        }
        $this->em->persist($income);
        $this->em->flush();

        $cashbook = new Cashbook();
        $cashbook->setInAmount($data['amount']);
        $cashbook->setReferenceId($income->getId());
        $cashbook->setReferenceType('income');
        $cashbook->setDescription($data['description']);
        $cashbook->setCreatedBy($this->getUser()?->getId());
        $this->em->persist($cashbook);
        $this->em->flush();
        return $this->json($income, 201);
    }

    #[Route('/{id}', name: 'api_incomes_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $income = $this->em->getRepository(Income::class)->find($id);
        if (!$income || $income->getDeletedAt()) {
            return $this->json(['error' => 'Not found'], 404);
        }
        $data = json_decode($request->getContent(), true);
        if (isset($data['description'])) $income->setDescription($data['description']);
        if (isset($data['amount'])) $income->setAmount($data['amount']);
        if (array_key_exists('currency', $data)) $income->setCurrency($data['currency']);
        if (isset($data['wallet_id'])) {
            $wallet = $this->em->getRepository(Wallet::class)->find($data['wallet_id']);
            $income->setWallet($wallet);
        }
        $income->setUpdatedBy($this->getUser()?->getId());
        $this->em->flush();
        return $this->json($income);
    }

    #[Route('/{id}', name: 'api_incomes_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $income = $this->em->getRepository(Income::class)->find($id);
        if (!$income || $income->getDeletedAt()) {
            return $this->json(['error' => 'Not found'], 404);
        }
        $income->setDeletedAt(new \DateTimeImmutable());
        $income->setUpdatedBy($this->getUser()?->getId());
        $this->em->flush();
        return $this->json(['message' => 'Deleted']);
    }
}
