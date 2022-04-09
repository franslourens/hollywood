<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i < 10; $i++) {
            $movie = new Movie();
            $movie->setTitle("movie" . $i);
            $movie->setDirector("movie" . $i);
            $movie->setCast("movie" . $i);
            $movie->setDurationMin($i);
            $movie->setDescription("movie" . $i);
            $manager->persist($movie);
        }

        $manager->flush();
    }
}
