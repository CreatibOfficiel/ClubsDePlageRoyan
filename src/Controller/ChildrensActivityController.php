<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChildrensActivityController extends AbstractController
{
    #[Route('/childrens/activity', name: 'app_childrens_activity')]
    public function index(): Response
    {
        return $this->render('childrens_activity/index.html.twig', [
            'controller_name' => 'ChildrensActivityController',
        ]);
    }
}
