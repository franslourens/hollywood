<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
    * @Route("/")
    */
    public function index(): Response
    {
      return $this->render('dashboard/index.html.twig', []);
    }
}
