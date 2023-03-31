<?php

namespace App\Controller;

use App\Services\BookingLessonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookedController extends AbstractController
{
    private $bookingLessonService;

    public function __construct(BookingLessonService $bookingLessonService)
    {
        $this->bookingLessonService = $bookingLessonService;
    }

    #[Route('/booked', name: 'app_booked')]
    public function index(): Response
    {
        $user = $this->getUser();
        $childsData = $this->bookingLessonService->getChildsBookingLessons($user->getId());



        return $this->render('booked/booked.html.twig', [
            'controller_name' => 'BookedController',
            'childsData' => $childsData,
        ]);
    }
}
