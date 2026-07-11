<?php

namespace App\Controller\Api;

use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/wallets')]
class WalletController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    #[Route('', name: 'api_wallets_list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $wallets = $this->em->getRepository(Wallet::class)->findBy(['deletedAt' => null]);
        return $this->json($wallets);
    }

    #[Route('', name: 'api_wallets_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $wallet = new Wallet();
        $wallet->setName($data['name']);
        $wallet->setCategory($data['category'] ?? null);
        $wallet->setCreatedBy($this->getUser()?->getId());
        $this->em->persist($wallet);
        $this->em->flush();
        return $this->json($wallet, 201);
    }

    #[Route('/{id}', name: 'api_wallets_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $wallet = $this->em->getRepository(Wallet::class)->find($id);
        if (!$wallet || $wallet->getDeletedAt()) {
            return $this->json(['error' => 'Not found'], 404);
        }
        $data = json_decode($request->getContent(), true);
        if (isset($data['name'])) $wallet->setName($data['name']);
        if (array_key_exists('category', $data)) $wallet->setCategory($data['category']);
        $wallet->setUpdatedBy($this->getUser()?->getId());
        $this->em->flush();
        return $this->json($wallet);
    }

    #[Route('/{id}', name: 'api_wallets_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $wallet = $this->em->getRepository(Wallet::class)->find($id);
        if (!$wallet || $wallet->getDeletedAt()) {
            return $this->json(['error' => 'Not found'], 404);
        }
        $wallet->setDeletedAt(new \DateTimeImmutable());
        $wallet->setUpdatedBy($this->getUser()?->getId());
        $this->em->flush();
        return $this->json(['message' => 'Deleted']);
    }
}
