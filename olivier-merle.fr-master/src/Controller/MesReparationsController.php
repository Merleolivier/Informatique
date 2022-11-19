<?php

namespace App\Controller;

use DateTime;
use Swift_Mailer;
use Swift_Message;
use App\Entity\Factures;
use Spipu\Html2Pdf\Html2Pdf;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\UserRepository;
use App\Repository\TicketsRepository;
use App\Repository\FacturesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AvisClientsRepository;
use App\Repository\ReparationsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MesReparationsController extends AbstractController
{
	/**
	 * @Route("/reparations", name="mes_reparations")
	 */
	public function index(
		ReparationsRepository $reparationsRepository,
		TicketsRepository $ticketsRepository,
		UserInterface $user,
		AvisClientsRepository $avisClientsRepository
	): Response {
		foreach ($user->getRoles() as $role) {
			if ($role == "ROLE_ADMIN") {
				$getReparations = $reparationsRepository->findBy([], ['id' => 'DESC']);
				$admin = 1;
				break;
			} else {
				$getReparations = $reparationsRepository->findBy(['user' => $user->getId()]);
				$admin = 0;
				break;
			}
		}
		$getTickets = $ticketsRepository->findBy(['idUser' => $user->getId()]);
		$getAvisClient = $avisClientsRepository->findBy(['username' => $user->getUsername()]);
		return $this->render('mes_reparations/index.html.twig', [
			'controller_name' => 'MesReparationsController',
			'reparations' => $getReparations,
			'tickets' => $getTickets,
			'avis' => $getAvisClient,
			'admin' => $admin
		]);
	}

	/**
	 * @Route("/reparations/admin/change/{type}/{id}", name="mes_reparations_admin_change")
	 */

	public function admin_change(
		int $type,
		int $id,
		ReparationsRepository $reparationsRepository,
		Session $session,
		EntityManagerInterface $manager,
		UserRepository $userRepository,
		\Swift_Mailer $mailer
	) {
		$getReparations = $reparationsRepository->findOneBy(['id' => $id]);
		$getReparations->setStatus($type);
		$manager->persist($getReparations);
		$manager->flush();
		$idUser = $getReparations->getUser();
		$getEmail = $userRepository->findOneBy(['id' => $idUser]);

		// Envoi du mail à l'utilisateur
		if ($type == 4) {
			$subject = 'La réparation de votre appareil est terminée';
		} else {
			$subject = 'La réparation de votre appareil vient de passer à l\'étape suivante';
		}
		$message = (new \Swift_Message('Olivier MERLE - ' . $subject . ''));
		$message->setFrom('clients@olivier-merle.fr', 'Informaticien - Olivier MERLE')
			->setTo($getEmail->getEmail())
			->setBody(
				$this->renderView(
					'emails/status.html.twig',
					[
						'name' => $getEmail->getUsername(),
						'type' => $type
					]
				),
				'text/html'
			);
		$mailer->send($message);

		// Récuparations infos commandes

		$numeroCommande = $getReparations->getNumero();
		$dateCreation = $getReparations->getDateCreate()->format('d-m-Y');
		$maind = $getReparations->getPrice();
		$matos = $getReparations->getPriceMatos();
		$total = $maind + $matos;

		if ($type == 4) {
			$message = (new \Swift_Message('Olivier MERLE - Facture de votre réparation'));
			$message->setFrom('clients@olivier-merle.fr', 'Informaticien - Olivier MERLE')
				->setTo($getEmail->getEmail())
				->setBody(
					$this->renderView(
						'emails/facture.html.twig',
						[
							'name' => $getEmail->getUsername(),
							'produit' => $getReparations->getModel(),
							'main' => $getReparations->getPrice(),
							'matos' => $getReparations->getPriceMatos(),
							'email' => $getEmail->getEmail(),
							'dc' => $getReparations->getDateCreate()
						]
					),
					'text/html'
				);
			$mailer->send($message);
		}


		$session->getFlashBag()->add('notice', '<div class="alert alert-valid"><strong><i class="fas fa-check"></i></strong> Status de la commande modifié.</div>');
		return new RedirectResponse('/reparations');
	}

	/**
	 * @Route("/reparations/admin/edit", name="mes_reparations_admin_edit")
	 */

	public function admin_edit(
		ReparationsRepository $reparationsRepository,
		EntityManagerInterface $manager,
		UserRepository $userRepository,
		\Swift_Mailer $mailer
	) {
		$id = $_POST['id'];
		$commentary = $_POST['commentary'];
		$matos = $_POST['matos'];
		$repair = $_POST['repair'];

		$getReparations = $reparationsRepository->findOneBy(['id' => $id]);
		if (!$getReparations) {
			return new JsonResponse(array('code' => 1));
		}
		$getUser = $userRepository->findOneBy(['id' => $getReparations->getUser()]);

		$getReparations->setCommentary($commentary)
			->setPriceMatos($matos)
			->setPrice($repair);
		$manager->persist($getReparations);
		$manager->flush();

		// Envoi du mail à l'utilisateur
		$message = (new \Swift_Message('Olivier MERLE - Votre réparation vient de subir un changement'));
		$message->setFrom('clients@olivier-merle.fr', 'Informaticien - Olivier MERLE')
			->setTo($getUser->getEmail())
			->setBody(
				$this->renderView(
					'emails/changement.html.twig',
					[
						'name' => $getUser->getUsername(),
						'repair' => $getReparations->getModel()
					]
				),
				'text/html'
			);
		$mailer->send($message);


		return new JsonResponse(array('code' => 2));
	}

	/**
	 * @Route("/reparations/admin/create", name="mes_reparations_admin_create")
	 */

	public function admin_create(UserRepository $userRepository)
	{
		$getUsers = $userRepository->findBy([], ['id' => 'DESC']);
		return $this->render('mes_reparations/create.html.twig', [
			'controller_name' => 'ReparationsCreateController',
			'users' => $getUsers
		]);
	}
}
