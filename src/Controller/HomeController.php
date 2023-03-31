<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ClubRepository;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    #[Route('/', name: 'app_home')]
    public function index(ClubRepository $clubRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user) {
            $this->userService->updateLastLogin($user);
        }

        return $this->render('home/home.html.twig', [
            'clubs' => $clubRepository->findAll(),
        ]);
    }
}
