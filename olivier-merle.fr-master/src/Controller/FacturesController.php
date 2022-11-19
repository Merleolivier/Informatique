<?php

namespace App\Controller;

use Spipu\Html2Pdf\Html2Pdf;
use App\Repository\FacturesRepository;
use App\Repository\ReparationsRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FacturesController extends AbstractController
{
    /**
     * @Route("/facture/{id}", name="factures")
     */
    public function index(
        int $id,
        FacturesRepository $facturesRepository,
        ReparationsRepository $reparationsRepository,
        Session $session,
        UserInterface $user
    ): Response {
        $getFacture = $facturesRepository->findOneBy(['repair' => $id]);
        $getReparations = $reparationsRepository->findOneBy(['id' => $getFacture->getRepair()]);

        foreach ($user->getRoles() as $role) {
            if ($role == "ROLE_ADMIN") {
                break;
            } else {
                if ($getReparations->getUser() != $user->idtoString()) {
                    $session->getFlashBag()->add('notice', '<div class="alert alert-error"><strong><i class="fas fa-times"></i></strong> Cette facture ne vous appartient pas.</div>');
                    return new RedirectResponse('/reparations');
                }
                break;
            }
        }
        if ($getReparations->getStatus() != 4) {
            $session->getFlashBag()->add('notice', '<div class="alert alert-error"><strong><i class="fas fa-times"></i></strong> Une erreur est survenue lors de la récupération de votre facture.</div>');
            return new RedirectResponse('/reparations');
        }

        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($getFacture->getContent());
        $html2pdf->output('Olivier-MERLE_facture_' . $getReparations->getNumero() . '.pdf');
    }
}
