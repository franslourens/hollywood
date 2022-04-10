<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Movie;
use App\Entity\Screening;
use App\Entity\Seat;
use App\Entity\Seatreserved;
use App\Entity\Reservation;
use App\Form\ReservationFormType;
use App\Entity\ReservationType;
use App\Form\ScreeningFormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends AbstractController
{
  /**
   * @Route("/movies/{id}", name="movie_show")
   */
  public function show(ManagerRegistry $doctrine, int $id): Response
  {
      $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

      $movie = $doctrine->getRepository(Movie::class)->find($id);

      if (!$movie) {
          throw $this->createNotFoundException(
              'No movie found for id '.$id
          );
      }

      $reservation = new Reservation();

      $form = $this->createForm(ReservationFormType::class, $reservation, ['movie_id' => $id]);

      return $this->render('movie/show.html.twig', ['movie' => $movie, 'form' => $form->createView()]);
  }

  /**
   * @Route("/reservation", name="create_reservation")
   */
   public function reservationNew(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
   {
       $screeningId = $request->request->all()["reservation_form"]["screening_id"];
       $screening = $doctrine->getRepository(Screening::class)->find($screeningId);

       $reservation = new Reservation();
       $reservation->setUserReservedId($this->getUser());
       $reservation->setScreeningId($screening);
       $reservation->setContact($this->getUser()->getEmail());
       $reservation->setReserved(true);
       $reservation->setUserPaidId($this->getUser()->getId());

       $reservation_type = $doctrine->getRepository(ReservationType::class)->find(2);
       $reservation->setReservationTypeId($reservation_type);

       $screening->addReservation($reservation);

       $form = $this->createForm(ReservationFormType::class, $reservation);
       $form->handleRequest($request);

       $reservations = $doctrine->getRepository(Seatreserved::class)->findBy(['screening_id' => $screening->getId()],['id' => 'ASC']);
       $seats = $doctrine->getRepository(Seat::class)->findBy(['auditorium' => $screening->getAuditoriumId()],['id' => 'ASC']);

       $not_taken = Reservation::can_book($reservations, $seats);

       if (empty($not_taken)) {
          throw new Exception("Fully Booked!");
       }

       if ($form->isSubmitted())
       {
         #if($form->isValid()) {
             $entityManager->persist($reservation);
             $entityManager->persist($screening);

             $booking = new Seatreserved();
             $seat = $doctrine->getRepository(Seat::class)->find(reset($not_taken));
             $booking->setSeatId($seat);
             $booking->setReservationId($reservation);
             $booking->setScreeningId($screening);

             $entityManager->persist($booking);
             $entityManager->flush();
             $this->addFlash('success', 'Your booking reference is: ' . $reservation->getCode());
             return $this->redirect($this->generateUrl('app_account'));
       #} else {
       #
       #}
     }

    $this->addFlash('error', 'Something went horribly wrong.');
    return $this->redirectToRoute('movie_show', ['id' => $screening->getMovieId()->getId()]);
  }
}
