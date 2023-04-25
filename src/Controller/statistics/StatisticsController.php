<?php

namespace App\Controller\statistics;

use App\Repository\StatisticsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class StatisticsController extends AbstractController
{
    #[Route('/admin/statistics', name: 'statistics', methods: ['GET'])]
    public function index(StatisticsRepository $repository, SerializerInterface $serializer) {
        $stats = $repository->findAll();
        $serializedStats = $serializer->serialize($stats, 'json', ['groups' => 'getStats']);

        return $this->render('statistics/index.html.twig', [
            'stats' => $serializedStats,
        ]);
    }

    #[Route('/api/statistics', name: 'api_statistics', methods: ['GET'])]
    public function getStats(StatisticsRepository $repository): JsonResponse {
        $stats = $repository->findAll();

        return $this->json($stats, 200, [], ['groups' => 'getStats']);
    }
}