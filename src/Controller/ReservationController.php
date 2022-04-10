<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reservation;


class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation/{reference}", name="app_reservation_delete", methods={"GET","DELETE"})
     */
    public function destroy(ManagerRegistry $doctrine, EntityManagerInterface $entityManager, $reference): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $repository = $doctrine->getRepository(Reservation::class);

        $reservation = $repository->findOneBy(['user_reserved_id' => $user, "code" => $reference]);
        $reservation->setActive(0);


        $this->addFlash('success', "Booking: " . $reference . " successfully cancelled.");
        return $this->redirect($this->generateUrl('app_account'));
    }
}
