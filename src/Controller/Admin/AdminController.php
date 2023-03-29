<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_index', methods: ['GET'])]
    public function index(ClubRepository $clubRepository): Response
    {
        return $this->render('admin/show.html.twig');
    }
}