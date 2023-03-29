<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingLessonController extends AbstractController
{
    #[Route('/booking/lesson', name: 'app_booking_lesson')]
    public function index(): Response
    {
        return $this->render('booking_lesson/booking_lesson.html.twig', [
            'controller_name' => 'BookingLessonController',
        ]);
    }


}
