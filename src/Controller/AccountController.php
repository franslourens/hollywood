<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Reservation;


class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function show(ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $repository = $doctrine->getRepository(Reservation::class);
        $user = $this->getUser();
        $reservations = $repository->findBy(['user_reserved_id' => $user, "active" => 1, "reserved" => 1], ['id' => 'DESC']);

        $data = Reservation::collection($reservations);

        return $this->render('account/index.html.twig', [
            'reservations' => $data,
        ]);
    }
}
