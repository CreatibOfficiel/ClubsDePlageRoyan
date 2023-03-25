<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountDetailsController extends AbstractController
{
    #[Route('/account/details', name: 'app_account_details')]
    public function index(): Response
    {
        return $this->render('account_details/account_details.html.twig', [
            'controller_name' => 'AccountDetailsController',
        ]);
    }
}
