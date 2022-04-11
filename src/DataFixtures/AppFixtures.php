<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Movie;
use App\Entity\Auditorium;
use App\Entity\Screening;
use App\Entity\Seat;
use App\Entity\ReservationType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $auditorium1 = new Auditorium();
        $auditorium1->setName("Cape Town");
        $auditorium1->setSeatsNo(30);

        $auditorium2 = new Auditorium();
        $auditorium2->setName("Sea Point");
        $auditorium2->setSeatsNo(30);

        $reservation_type1 = new ReservationType();
        $reservation_type1->setReservationType("Phone");

        $reservation_type2 = new ReservationType();
        $reservation_type2->setReservationType("Online");

        $reservation_type3 = new ReservationType();
        $reservation_type3->setReservationType("Person");

        $manager->persist($reservation_type1);
        $manager->persist($reservation_type2);
        $manager->persist($reservation_type3);

        $manager->persist($auditorium1);
        $manager->persist($auditorium2);

        for ($i = 1; $i < 10; $i++) {
            $movie = new Movie();
            $movie->setTitle("movie" . $i);
            $movie->setDirector("movie" . $i);
            $movie->setCast("movie" . $i);
            $movie->setDurationMin($i);
            $movie->setDescription("movie" . $i);
            $manager->persist($movie);

            $datetime1 = new \DateTime("2022-04-20 20:00:00");
            $datetime2 = new \DateTime("2022-04-20 22:00:00");

            $screening = new Screening();
            $screening->setAuditoriumId($auditorium1);
            $screening->setStart($datetime1);
            $screening->setMovieId($movie);
            $manager->persist($screening);

            $screening = new Screening();
            $screening->setAuditoriumId($auditorium2);
            $screening->setStart($datetime2);
            $screening->setMovieId($movie);
            $manager->persist($screening);
        }

        for ($i = 1; $i <= 6; $i++) {
          $seat = new Seat();
          $seat->setAuditorium($auditorium1);
          $seat->setRow($i);
          $seat->setNumber(1);

          $manager->persist($seat);

          $seat = new Seat();
          $seat->setAuditorium($auditorium1);
          $seat->setRow($i);
          $seat->setNumber(2);

          $manager->persist($seat);

          $seat = new Seat();
          $seat->setAuditorium($auditorium1);
          $seat->setRow($i);
          $seat->setNumber(3);

          $manager->persist($seat);

          $seat = new Seat();
          $seat->setAuditorium($auditorium1);
          $seat->setRow($i);
          $seat->setNumber(4);

          $manager->persist($seat);

          $seat = new Seat();
          $seat->setAuditorium($auditorium1);
          $seat->setRow($i);
          $seat->setNumber(5);

          $manager->persist($seat);
        }

        for ($i = 1; $i <= 6; $i++) {
          $seat = new Seat();
          $seat->setAuditorium($auditorium2);
          $seat->setRow($i);
          $seat->setNumber(1);

          $manager->persist($seat);

          $seat = new Seat();
          $seat->setAuditorium($auditorium2);
          $seat->setRow($i);
          $seat->setNumber(2);

          $manager->persist($seat);

          $seat = new Seat();
          $seat->setAuditorium($auditorium2);
          $seat->setRow($i);
          $seat->setNumber(3);

          $manager->persist($seat);

          $seat = new Seat();
          $seat->setAuditorium($auditorium2);
          $seat->setRow($i);
          $seat->setNumber(4);

          $manager->persist($seat);

          $seat = new Seat();
          $seat->setAuditorium($auditorium2);
          $seat->setRow($i);
          $seat->setNumber(5);

          $manager->persist($seat);
        }

        $manager->flush();
    }
}
