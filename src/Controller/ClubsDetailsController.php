<?php

namespace App\Controller;

use App\Entity\Club;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClubsDetailsController extends AbstractController
{
    #[Route('/clubs/details/{id}', name: 'app_clubs_details')]
    public function index(Club $club): Response
    {
        return $this->render('clubs_details/clubs_details.html.twig', [
            'club' => $club,
        ]);
    }
}
