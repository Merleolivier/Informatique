<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaintenanceModeController extends AbstractController
{
    /**
     * @Route("/maintenance", name="maintenance_mode")
     */
    public function index(): Response
    {
        return $this->render('maintenance_mode/index.html.twig', [
            'controller_name' => 'MaintenanceModeController',
        ]);
    }
}
