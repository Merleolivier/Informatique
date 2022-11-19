<?php

namespace App\Controller;

use App\Entity\TicketsResponse;
use App\Repository\ReparationsRepository;
use App\Repository\TicketsRepository;
use App\Repository\TicketsResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TicketsController extends AbstractController
{
    /**
     * @Route("/tickets/{id}", name="tickets")
     */
    public function index(int $id, TicketsRepository $ticketsRepository, UserInterface $user, Session $session, ReparationsRepository $reparationsRepository, TicketsResponseRepository $ticketsResponseRepository): Response
    {
        $getTickets = $ticketsRepository->findOneBy([
            'id' => $id,
            'idUser' => $user->getId()
        ]);
        if (!$getTickets) {
            $session->getFlashBag()->add('notice', '<div class="alert alert-error"><strong><i class="fas fa-times"></i></strong> Une erreur est survenue lors de la récupération du ticket.</div>');
            return new RedirectResponse('/reparations');
        }
        $getReparations = $reparationsRepository->findOneBy([
            'id' => $getTickets->getReparations()
        ]);
        $getResponses = $ticketsResponseRepository->findBy(['idTicket' => $id]);
        return $this->render('tickets/index.html.twig', [
            'controller_name' => 'TicketsController',
            'ticket' => $getTickets,
            'reparation' => $getReparations,
            'responses' => $getResponses
        ]);
    }

    /**
     * @Route("/ticket/message/add", name="tickets_add", methods={"POST","HEAD"})
     */
    public function add(UserInterface $user, TicketsRepository $ticketsRepository, Session $session, EntityManagerInterface $manager)
    {
        // Je récupère les informations
        $message = $_POST['message'];
        $idUser = $user->getId();
        $idTicket = $_POST['id'];
        // Je vérifie si le ticket est bien à l'utilisateur
        $getTickets = $ticketsRepository->findOneBy(['id' => $idTicket, 'idUser' => $idUser]);
        if (!$getTickets) {
            $session->getFlashBag()->add('notice', '<div class="alert alert-error"><strong><i class="fas fa-times"></i></strong> Une erreur est survenue lors de la réponse à votre ticket.</div>');
            return new RedirectResponse('/tickets/' . $idTicket . '');
        }
        // J'insère le ticket
        $insertTicket = new TicketsResponse;
        $insertTicket->setIdTicket($idTicket)
            ->setMessage($message)
            ->setUser($idUser)
            ->setSendDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $manager->persist($insertTicket);
        $manager->flush();

        $session->getFlashBag()->add('notice', '<div class="alert alert-valid"><strong><i class="fas fa-check"></i></strong> Votre réponse vient d\'être envoyée.</div>');
        return new RedirectResponse('/tickets/' . $idTicket . '');
    }
}
