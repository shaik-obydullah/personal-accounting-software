<?php

namespace App\Controller\Api;

use App\Entity\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/configurations')]
class ConfigurationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    #[Route('', name: 'api_configurations_list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $configs = $this->em->getRepository(Configuration::class)->findBy(['deletedAt' => null]);
        $result = [];
        foreach ($configs as $c) {
            $result[$c->getName()] = json_decode($c->getSetting(), true) ?? $c->getSetting();
        }
        return $this->json($result);
    }

    #[Route('/{name}', name: 'api_configurations_get', methods: ['GET'])]
    public function get(string $name): JsonResponse
    {
        $config = $this->em->getRepository(Configuration::class)->findOneBy(['name' => $name, 'deletedAt' => null]);
        if (!$config) {
            return $this->json(['error' => 'Not found'], 404);
        }
        return $this->json(['name' => $config->getName(), 'setting' => json_decode($config->getSetting(), true) ?? $config->getSetting()]);
    }

    #[Route('/{name}', name: 'api_configurations_update', methods: ['PUT'])]
    public function update(string $name, Request $request): JsonResponse
    {
        $config = $this->em->getRepository(Configuration::class)->findOneBy(['name' => $name, 'deletedAt' => null]);
        if (!$config) {
            return $this->json(['error' => 'Not found'], 404);
        }
        $data = json_decode($request->getContent(), true);
        $setting = $data['setting'] ?? null;
        if ($setting === null) {
            return $this->json(['error' => 'setting is required'], 400);
        }
        $encoded = is_string($setting) ? $setting : json_encode($setting);
        $config->setSetting($encoded);
        $config->setUpdatedAt(new \DateTimeImmutable());
        $this->em->flush();
        return $this->json(['name' => $config->getName(), 'setting' => json_decode($config->getSetting(), true) ?? $config->getSetting()]);
    }
}
