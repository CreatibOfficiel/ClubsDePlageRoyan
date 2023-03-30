<?php

namespace App\Controller;

use App\DTO\BookingLessonPagesDto;
use App\Form\BookingLessonPage1Type;
use App\Form\BookingLessonPage2Type;
use App\Services\BookingLessonService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingLessonController extends AbstractController
{
    private BookingLessonService $bookingLessonService;
    public function __construct(BookingLessonService $bookingLessonService)
    {
        $this->bookingLessonService = $bookingLessonService;
    }

    #[Route('/booking/lesson', name: 'app_booking_lesson')]
    public function index(): Response
    {
//        $this->bookingLessonService->setPage(1);
        $bookingData = $this->bookingLessonService->getBookingData();
        
        return $this->redirectToRoute('app_booking_lesson_' . $bookingData['bookingPage']);
    }

    #[Route('/booking/lesson/1', name: 'app_booking_lesson_1', methods: ['GET', 'POST'])]
    public function bookingPage1index(Request $request): Response
    {
        $bookingData = $this->bookingLessonService->getBookingData();

        $bookingPageDto = new BookingLessonPagesDto();
        $bookingPageDto->dateFrom = new \DateTime();
        $bookingPageDto->dateTo = new \DateTime();

        $form = $this->createForm(BookingLessonPage1Type::class, $bookingPageDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookingLessonService->setClubId($bookingPageDto->club->getId());
            $this->bookingLessonService->setDateFrom($bookingPageDto->dateFrom);
            $this->bookingLessonService->setDateTo($bookingPageDto->dateTo);
            $this->bookingLessonService->setPage(2);
            return $this->redirectToRoute('app_booking_lesson');
        }

        return $this->render('booking_lesson/booking_lesson.html.twig', [
            'controller_name' => 'BookingLessonController',
            'page' => $bookingData['bookingPage'],
            'form' => $form->createView(),
        ]);
    }

    #[Route('/booking/lesson/2', name: 'app_booking_lesson_2', methods: ['GET', 'POST'])]
    public function bookingPage2index(Request $request): Response
    {
        $bookingData = $this->bookingLessonService->getBookingData();
        $bookingPageDto = new BookingLessonPagesDto();

        $form = $this->createForm(BookingLessonPage2Type::class, $bookingPageDto, [
            'educator' => $this->getUser()->getEducator(),
            'user' => $this->getUser(),
            'club' => $this->bookingLessonService->getClubById($bookingData['clubId']),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookingLessonService->setEducatorId($bookingPageDto->educator->getId());
            $this->bookingLessonService->setChildsId($bookingPageDto->child->getId());
            $this->bookingLessonService->setPage(3);
            return $this->redirectToRoute('app_booking_lesson');
        }

        return $this->render('booking_lesson/booking_lesson.html.twig', [
            'controller_name' => 'BookingLessonController',
            'page' => $bookingData['bookingPage'],
            'form' => $form->createView(),
        ]);
    }

    #[Route('/booking/lesson/3', name: 'app_booking_lesson_3')]
    public function bookingPage3index(): Response
    {
        $bookingData = $this->bookingLessonService->getBookingData();
//        var_dump($bookingData);

        $week = $this->bookingLessonService->getWeek();
//        var_dump($week);
        return $this->render('booking_lesson/booking_lesson.html.twig', [
            'controller_name' => 'BookingLessonController',
            'page' => $bookingData['bookingPage'],
            'week' => $week,
            'bookingData' => $bookingData,
            'isSelectedDate' => $this->bookingLessonService->isSelectedDate(),
        ]);
    }

    #[Route('/booking/lesson/back', name: 'app_booking_page_back')]
    public function backPageIndex(): Response
    {
        $bookingData = $this->bookingLessonService->getBookingData();
        $this->bookingLessonService->setPage($bookingData['bookingPage'] - 1);
        return $this->redirectToRoute('app_booking_lesson');
    }

    #[Route('/booking/lesson/addOrRemoveDate/{date}', name: 'app_booking_page_add_or_remove_date')]
    public function addOrRemoveDatePageIndex(Request $request, $date): Response
    {
        $bookingData = $this->bookingLessonService->getBookingData();

        $newDate = DateTime::createFromFormat('d-m-Y_H:i', $date);
        $this->bookingLessonService->setSelectedDate($newDate);
        return $this->redirectToRoute('app_booking_lesson');
    }

    #[Route('/booking/lesson/nextClendar', name: 'app_booking_page_next_calendar')]
    public function nextClendarPageIndex(Request $request): Response
    {
        $this->bookingLessonService->nextPageCalandar();
        return $this->redirectToRoute('app_booking_lesson');
    }

    #[Route('/booking/lesson/prevClendar', name: 'app_booking_page_prev_calendar')]
    public function prevClendarPageIndex(Request $request): Response
    {
        $this->bookingLessonService->previousPageCalandar();
        return $this->redirectToRoute('app_booking_lesson');
    }

    #[Route('/booking/lesson/validateShedule', name: 'app_booking_lesson_validate_shedule')]
    public function bookingPageValidateSheduleIndex(): Response
    {
        $bookingData = $this->bookingLessonService->getBookingData();
        $valid = $this->bookingLessonService->validateShedule($this->getUser());

        if ($valid) {
            return $this->redirectToRoute('app_home');
        } else {
            return $this->redirectToRoute('app_prices');
        }
    }

    #[Route('/booking/lesson/cancel', name: 'app_booking_lesson_cancel')]
    public function bookingPageCancelIndex(): Response
    {

        $this->bookingLessonService->removeBookingSession();

        return $this->redirectToRoute('app_home');
    }
}
