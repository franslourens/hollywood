<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Movie;

class DashboardController extends AbstractController
{
    /**
    * @Route("/")
    */
    public function index(ManagerRegistry $doctrine): Response
    {

      $repository = $doctrine->getRepository(Movie::class);
      $movies = $repository->findAll();

      return $this->render('dashboard/index.html.twig', ["movies" => $movies]);
    }
}
