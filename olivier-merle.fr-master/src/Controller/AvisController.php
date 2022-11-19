<?php

namespace App\Controller;

use App\Entity\AvisClients;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AvisClientsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvisController extends AbstractController
{
    /**
     * @Route("/avis/{id}", name="avis")
     */
    public function index(int $id, AvisClientsRepository $avisClientsRepository, UserInterface $user, Session $session): Response
    {
        $getAvis = $avisClientsRepository->findOneBy(['username' => $user->getUsername()]);
        if ($getAvis) {
            $session->getFlashBag()->add('notice', '<div class="alert alert-error"><strong><i class="fas fa-times"></i></strong> Vous ne pouvez pas voter deux fois de suite.</div>');
            return new RedirectResponse('/reparations');
        }
        return $this->render('avis/index.html.twig', [
            'controller_name' => 'AvisController',
            'id' => $id
        ]);
    }

    /**
     * @Route("/aviscreate", name="avis_send", methods={"POST","HEAD"})
     */

    public function send(UserInterface $user, Session $session, EntityManagerInterface $manager)
    {
        $title = $_POST['title'];
        $description = $_POST['description'];

        $createAvis = new AvisClients;
        $createAvis->setUsername($user->getUsername())
            ->setStars('5')
            ->setText($description)
            ->setValid('0')
            ->setTitle($title);
        $manager->persist($createAvis);
        $manager->flush();

        $session->getFlashBag()->add('notice', '<div class="alert alert-valid"><strong><i class="fas fa-check"></i></strong> Votre avis vient d\'être envoyé.</div>');
        return new RedirectResponse('/reparations');
    }
}
