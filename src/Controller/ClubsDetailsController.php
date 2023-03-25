<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClubsDetailsController extends AbstractController
{
    #[Route('/clubs/details', name: 'app_clubs_details')]
    public function index(): Response
    {
        return $this->render('clubs_details/clubs_details.html.twig', [
            'controller_name' => 'ClubsDetailsController',
        ]);
    }
}
