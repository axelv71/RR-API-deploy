<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class StatTestController extends AbstractController
{
    #[Route('/stat_test', name: 'stat-test', methods: ['GET'])]
    public function index() {
        return $this->render('stats_test.html.twig');
    }
}