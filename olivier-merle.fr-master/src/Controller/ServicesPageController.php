<?php

namespace App\Controller;

use App\Repository\ServicesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesPageController extends AbstractController
{
    /**
     * @Route("/services", name="services_page")
     */
    public function index(ServicesRepository $servicesRepository): Response
    {
        $getService = $servicesRepository->findBy(['valid' => 1]);
        return $this->render('services_page/index.html.twig', [
            'controller_name' => 'ServicesPageController',
            'services' => $getService
        ]);
    }
}
