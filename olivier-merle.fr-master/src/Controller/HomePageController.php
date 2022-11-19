<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AvisClientsRepository;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     */
    public function index(AvisClientsRepository $avisClientsRepository): Response
    {
        $getAvis = $avisClientsRepository->findBy([
            'valid' => 1
        ]);
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
            'avis' => $getAvis
        ]);
    }
}
