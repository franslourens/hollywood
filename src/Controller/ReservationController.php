<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reservation;
use App\Entity\Seatreserved;

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
        $reservation->setActive(false);

        $seatReservedRepository = $doctrine->getRepository(Seatreserved::class);

        $screening = $reservation->getScreeningId();

        $seatReserved = $seatReservedRepository->findOneBy(['reservation_id' => $reservation->getId(), "screening_id" => $screening->getId()]);

        $reservation->removeSeatreserved($seatReserved);

        $entityManager->persist($reservation);
        $entityManager->flush();

        $this->addFlash('success', "Booking: " . $reference . " successfully cancelled.");
        return $this->redirect($this->generateUrl('app_account'));
    }
}
