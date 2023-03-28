<?php

namespace App\Controller;

use App\Repository\SwimmingPackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PricesController extends AbstractController
{
    #[Route('/prices', name: 'app_prices')]
    public function index(SwimmingPackRepository $swimmingPackRepository): Response
    {
        $swimmingPack = $swimmingPackRepository->findAll();

        return $this->render('prices/prices.html.twig', [
            'controller_name' => 'PricesController',
            'swimming_packs' => $swimmingPack,
        ]);
    }
}
