<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Entity\TicketsResponse;
use App\Repository\TicketsRepository;
use App\Repository\ReparationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class NewTicketController extends AbstractController
{
    /**
     * @Route("/new/ticket/{id}", name="new_ticket")
     */
    public function index(
        TicketsRepository $ticketsRepository,
        ReparationsRepository $reparationsRepository,
        Session $session,
        EntityManagerInterface $manager,
        UserInterface $user,
        int $id
    ): Response {
        if (isset($_POST['sendTicket'])) {
            $subject = $_POST['subject'];
            $description = $_POST['description'];
            $id = $_POST['id'];

            // Je vérifie si un ticket existe déjà
            $checkTicket = $ticketsRepository->findOneBy(['id' => $id]);
            if ($checkTicket) {
                $session->getFlashBag()->add('notice', '<div class="alert alert-error"><strong><i class="fas fa-times"></i></strong> Un ticket existe déjà pour cette réparation.</div>');
                return new RedirectResponse('/reparations');
            }
            // Je vérifie si la commande est bien à la bonne personne
            $checkReparations = $reparationsRepository->findOneBy([
                'id' => $id,
                'user' => $user->getId()
            ]);
            if (!$checkReparations) {
                $session->getFlashBag()->add('notice', '<div class="alert alert-error"><strong><i class="fas fa-times"></i></strong> Une erreur est survenue lors de la création du ticket.</div>');
                return new RedirectResponse('/reparations');
            }
            // Si l'utilisateur arrive ici, je peux créer le ticket
            $createTicket = new Tickets;
            $createTicket->setIdUser($user->getId())
                ->setSubject($subject)
                ->setMessage($description)
                ->setReparations($id);
            $manager->persist($createTicket);
            $manager->flush();
            // Je récupère l'id crée
            $getTickets = $ticketsRepository->findOneBy(['subject' => $subject]);
            $createMessage = new TicketsResponse;
            $createMessage->setMessage($description)
                ->setIdTicket($getTickets->getId())
                ->setUser($user->getId())
                ->setSendDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $manager->persist($createMessage);
            $manager->flush();
            $session->getFlashBag()->add('notice', '<div class="alert alert-valid"><strong><i class="fas fa-check"></i></strong> Ticket crée avec succès.</div>');
            return new RedirectResponse('/reparations');
        }

        $getReparations = $reparationsRepository->findOneBy(['id' => $id]);
        if ($getReparations->getUser()->getId() != $user->idToString()) {
            $session->getFlashBag()->add('notice', '<div class="alert alert-error"><strong><i class="fas fa-times"></i></strong> Ce ticket ne vous appartient pas.</div>');
            return new RedirectResponse('/reparations');
        }
        if (!$getReparations) {
            $session->getFlashBag()->add('notice', '<div class="alert alert-error"><strong><i class="fas fa-times"></i></strong> Une erreur est survenue lors de la création du ticket.</div>');
            return new RedirectResponse('/reparations');
        }
        return $this->render('new_ticket/index.html.twig', [
            'controller_name' => 'NewTicketController',
            'reparation' => $getReparations
        ]);
    }
}
