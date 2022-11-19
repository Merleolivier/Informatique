<?php

namespace App\Controller;

use App\Entity\Reparations;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ClientsController extends AbstractController
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/clients/create", name="clients")
     */
    public function index(): Response
    {
        return $this->render('clients/index.html.twig', [
            'controller_name' => 'ClientsController',
        ]);
    }

    /**
     * @Route("/clients/create/send", name="clients_send", methods={"POST","HEAD"})
     */

    public function send(
        EntityManagerInterface $manager,
        Session $session,
        UserRepository $userRepository,
        \Swift_Mailer $mailer,
        LoggerInterface $logger
    ) {
        $charsnumber = '0123456789';
        $numberCommand = '';
        for ($f = 0; $f < '5'; $f++) {
            $numberCommand .= $charsnumber[rand(0, strlen($charsnumber) - 1)];
        }

        if (empty($_POST['userSelect'])) {
            $prenom = $_POST['prenom'];
            $nom = $_POST['nom'];
            $email = $_POST['email'];

            $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $passwordNoHashed = '';
            for ($i = 0; $i < '10'; $i++) {
                $passwordNoHashed .= $chars[rand(0, strlen($chars) - 1)];
            }

            // Création de l'utilisateur
            $createUser = new User;
            $hash = $this->encoder->encodePassword($createUser, $passwordNoHashed);
            $createUser->setUsername($prenom . '.' . $nom)
                ->setRoles(['ROLE_USER'])
                ->setEmail($email)
                ->setPassword($hash);
            $manager->persist($createUser);
            $manager->flush();

            // Envoi du mail à l'utilisateur
            $message = (new \Swift_Message('Olivier MERLE - Merci de me faire confiance pour la réparation de votre ' . $_POST['modele'] . ''));
            $message->setFrom('clients@olivier-merle.fr', 'Informaticien - Olivier MERLE')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'emails/create.html.twig',
                        [
                            'name' => $prenom . '.' . $nom,
                            'pass' => $passwordNoHashed,
                            'repair' => $_POST['modele']
                        ]
                    ),
                    'text/html'
                );
            $mailer->send($message);

            // Je récupère l'id de l'utilisateur
            $getUser = $userRepository->findOneBy(['email' => $email]);
            $type = 'Utilisateur et réparation créés avec succès.';
        } else {
            $getUser = $userRepository->findOneBy(['id' => $_POST['userSelect']]);
            $type = 'Réparation créée avec succès';

            // Envoi du mail à l'utilisateur
            $message = (new \Swift_Message('Olivier MERLE - Merci de me faire confiance pour la réparation de votre ' . $_POST['modele'] . ''));
            $message->setFrom('clients@olivier-merle.fr', 'Informaticien - Olivier MERLE')
                ->setTo($getUser->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/newrepair.html.twig',
                        [
                            'name' => $prenom . '.' . $nom,
                            'repair' => $_POST['modele']
                        ]
                    ),
                    'text/html'
                );
            $mailer->send($message);
        }

        $marque = $_POST['marque'];
        $modele = $_POST['modele'];
        $probleme = $_POST['probleme'];
        $prix = $_POST['prix'];
        $title = $_POST['title'];

        // Je crée la réparation
        $createRepair = new Reparations;
        $createRepair
            ->setNumero($numberCommand)
            ->setTitle($title)
            ->setDateCreate(new \DateTime('now', new \DateTimeZone('Europe/Paris')))
            ->setDateUpdate(new \DateTime('now', new \DateTimeZone('Europe/Paris')))
            ->setPrice($prix)
            ->setStatus(1)
            ->setModel($modele)
            ->setBrand($marque)
            ->setTechos('Olivier')
            ->setCommentary('Aucun commentaire')
            ->setPriceMatos('0')
            ->setProblem($probleme)
            ->setUser($getUser);
        $manager->persist($createRepair);
        $manager->flush();
        $session->getFlashBag()->add('notice', '<div class="alert alert-valid"><strong><i class="fas fa-check"></i></strong> ' . $type . '</div>');
        return new RedirectResponse('/reparations');
    }
}
