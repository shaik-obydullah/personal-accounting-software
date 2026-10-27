<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('app.html.twig');
    }

    #[Route('/{path}', name: 'app_spa_fallback', requirements: ['path' => '^(?!api|_profiler|_wdt).*'])]
    public function fallback(): Response
    {
        return $this->render('app.html.twig');
    }
}
