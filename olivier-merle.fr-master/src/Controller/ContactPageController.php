<?php

namespace App\Controller;

use App\Entity\ContactMessages;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactPageController extends AbstractController
{
    /**
     * @Route("/contact", name="contact_page")
     */
    public function indexAction(): Response
    {
        return $this->render('contact_page/index.html.twig', [
            'controller_name' => 'ContactPageController',
        ]);
    }

    /**
     * @Route("/contact/send", name="contact_page_send", methods={"POST","HEAD"})
     */
    public function sendAction(EntityManagerInterface $manager, Session $session, \Swift_Mailer $mailer)
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $tel = $_POST['telephone'];
        $messagePost = $_POST['message'];

        $insertMessage = new ContactMessages;
        $insertMessage->setUsername($name)
            ->setEmail($email)
            ->setPhone($tel)
            ->setMessage($messagePost);
        $manager->persist($insertMessage);
        $manager->flush();

        $message = (new \Swift_Message('ParisItService'));
        $message->setFrom('contact@olivier-merle.fr', 'ParisItService')
            ->setTo('contact@informatique-paris.fr')
            ->setBody(
                'Vous venez de recevoir un nouveau mail de informatique-paris.fr. <br />
                Nom : ' . $name . ' <br/>
                Tél : ' . $tel . '<br/>
                Message : ' . $messagePost . '',
                'text/html'
            );
        $mailer->send($message);

        $session->getFlashBag()->add('notice', '<div class="alert alert-valid"><strong><i class="fas fa-check"></i></strong> Votre message vient d\'être envoyé avec succès !</div>');
        return new RedirectResponse('/contact');
    }
}
