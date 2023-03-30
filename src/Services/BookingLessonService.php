<?php

namespace App\Services;

use App\Entity\BookingLesson;
use App\Entity\Club;
use App\Entity\Educator;
use App\Entity\TimeSlot;
use App\Entity\User;
use App\Repository\BookingLessonRepository;
use App\Repository\ChildRepository;
use App\Repository\ClubRepository;
use App\Repository\EducatorRepository;
use App\Repository\TimeSlotRepository;
use App\Repository\UserRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeImmutable;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Time;

class BookingLessonService extends AbstractEntityService
{

    public EducatorRepository $educatorRepository;
    public TimeSlotRepository $timeSlotRepository;
    public BookingLessonRepository $bookingLessonRepository;
    public ClubRepository $clubRepository;
    public UserRepository $userRepository;
    public RequestStack $requestStack;
    public SessionInterface $session;
    public ChildRepository $childRepository;

    public array $selectDateTmp = [];

    public $bookingData = [
        'bookingPage' => 0,
        'clubId' => 0,
        'dateFrom' => '',
        'dateTo' => '',
        'educatorId' => 0,
        'childsId' => 0,
        'weekNumber' => 0,
        'selectedDates' => [],
    ];

    public function __construct(
        EducatorRepository $educatorRepository,
        TimeSlotRepository $timeSlotRepository,
        BookingLessonRepository $bookingLessonRepository,
        ClubRepository $clubRepository,
        RequestStack $requestStack,
        UserRepository $userRepository,
        ChildRepository $childRepository

    )
    {
        parent::__construct($bookingLessonRepository);
        $this->educatorRepository = $educatorRepository;
        $this->timeSlotRepository = $timeSlotRepository;
        $this->bookingLessonRepository = $bookingLessonRepository;
        $this->clubRepository = $clubRepository;
        $this->requestStack = $requestStack;
        $this->userRepository = $userRepository;
        $this->childRepository = $childRepository;
        $this->session = $this->requestStack->getSession();
    }

    /**
     * @return array
     */
    public function getBookingData()
    {
        if ($this->checkIfBookingDataIsSet()) {
            return $this->session->get('booking');
        } else {
            $this->resetBookingData();
            return $this->session->get('booking');
        }
    }

    public function resetBookingData(): void
    {
        $this->bookingData = [
            'bookingPage' => 1,
            'clubId' => 0,
            'dateFrom' => '',
            'dateTo' => '',
            'educatorId' => 0,
            'childsId' => 0,
            'weekNumber' => 0,
            'selectedDates' => [],
        ];
        $this->session->set('booking', $this->bookingData);
    }

    public function checkIfBookingDataIsSet(): bool
    {
        $bd = $this->session->get('booking');
        if (empty($bd)){
            return false;
        }
        $initialValues = [
            'bookingPage' => 0,
            'clubId' => 0,
            'dateFrom' => '',
            'dateTo' => '',
            'educatorId' => 0,
            'childsId' => 0,
            'weekNumber' => 0,
            'selectedDates' => [],
        ];

        foreach ($initialValues as $key => $value) {
            if ($bd[$key] !== $value) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getClubs(): array
    {
        return $this->clubRepository->findAll();
    }

    public function getClubById(int $clubId): Club
    {
        return $this->clubRepository->find($clubId);
    }

    public function setPage(int $page): void
    {
        $this->bookingData = $this->session->get('booking');
        $this->bookingData['bookingPage'] = $page;
        $this->session->set('booking', $this->bookingData);
    }

    public function getPage(): int
    {
        return $this->getBookingData()['bookingPage'];
    }

    public function setClubId(int $clubId): void
    {
        $this->bookingData = $this->session->get('booking');
        $this->bookingData['clubId'] = $clubId;
        $this->session->set('booking', $this->bookingData);
    }

    public function getClubId(): int
    {
        return $this->getBookingData()['clubId'];
    }

    public function setEducatorId(int $educatorId): void
    {
        $this->bookingData = $this->session->get('booking');
        $this->bookingData['educatorId'] = $educatorId;
        $this->session->set('booking', $this->bookingData);
    }

    public function getEducatorId(): int
    {
        return $this->getBookingData()['educatorId'];
    }

    public function setDateFrom(DateTime $dateFrom): void
    {;
        $this->bookingData = $this->session->get('booking');
        $this->bookingData['dateFrom'] = $dateFrom;
        $this->session->set('booking', $this->bookingData);
    }

    public function setDateTo(\DateTime $dateTo): void
    {
        $this->bookingData = $this->session->get('booking');
        $this->bookingData['dateTo'] = $dateTo;
        $this->session->set('booking', $this->bookingData);
    }

    public function setChildsId(int $childsId): void
    {
        $this->bookingData = $this->session->get('booking');
        $this->bookingData['childsId'] = $childsId;
        $this->session->set('booking', $this->bookingData);
    }

    public function getWeekNumber(): int
    {
        return $this->getBookingData()['weekNumber'];
    }

    public function getTime(DateTime $date): array {
        $time = [];
        $newDate = $date;
        $newDate->setTime(10, 0, 0);
        $time[] = $newDate;

        for ($i = 0; $i < 24; $i++) {
            $newDate = new DateTime($newDate->format('Y-m-d H:i:s'));
            $time[] = $newDate->add(new DateInterval('PT20M'));
        }
        return $time;
    }

    public function getPreviousMonday(DateTime $date): DateTime
    {
        $dayOfWeek = $date->format('w');
        $previousMonday = $date->sub(new DateInterval('P' . $dayOfWeek . 'D'));
        return $previousMonday;
    }

    public function getPreviousMondayDays(DateTime $date): array
    {
        $newDate = new DateTime();
        $newDate->setTimestamp($date->getTimestamp());
        $previousMonday = $this->getPreviousMonday($newDate);
        $days = [];
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($previousMonday, $interval, $date);

        foreach ($period as $day) {
            $days[] = $day;
        }

        return $days;
    }

    public function getWeek(): array
    {
        $this->bookingData = $this->session->get('booking');
        $weekNumber = $this->bookingData['weekNumber'];
        $d1 = $this->bookingData['dateFrom'];
        $d2 = $this->bookingData['dateTo'];

        $weekDay = [
                'day' => '',
                'times' => [
                    'time' => '',
                    'isAvailable' => true,
                    'isSelect' => false,
                ],
                'isAvailable' => true,
        ];

        $weekDays = [];
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($d1, $interval, $d2);

        $week = [];
        $prevDays = $this->getPreviousMondayDays($d1);
        foreach ($prevDays as $day) {
            $week[] = $day;
        }
        foreach ($period as $day) {
            $week[] = $day;
        }

        $days = array_slice($week, $weekNumber*7, 7);
        foreach ($days as $day) {
            $timesAlreadyBooked = $this->getEducatorTimeSlots();
            $times = [];
            foreach ($this->getTime($day) as $time) {
                $isAvailable = true;
                $isSelect = false;
                if (in_array($time, $timesAlreadyBooked)) {
                   $isAvailable = false;
               } elseif (in_array($time, $this->bookingData['selectedDates'])) {
                   $isSelect = true;
               }
                $times[] = [
                    'time' => $time,
                    'isAvailable' => $isAvailable,
                    'isSelect' => $isSelect,
                ];
            }

            $isDayAvailable = true;

            if ($day < $this->bookingData['dateFrom']) {
                $isDayAvailable = false;
            }

            $weekDay = [
                'day' => $day,
                'times' => $times,
                'isAvailable' => $isDayAvailable
            ];
            if ($day->format('l') != 'Sunday') {
                $weekDays[] = $weekDay;
            }
        }
        return $weekDays;
    }

    public function setSelectedDate(DateTime $date): void
    {
        $this->bookingData = $this->session->get('booking');
        if (in_array($date, $this->bookingData['selectedDates'])) {
            $key = array_search($date, $this->bookingData['selectedDates']);
            unset($this->bookingData['selectedDates'][$key]);
        } else {
            $this->bookingData['selectedDates'][] = $date;
        }
        $this->session->set('booking', $this->bookingData);
    }

    public function nextPageCalandar(): void
    {
        $this->bookingData = $this->session->get('booking');
        $this->bookingData['weekNumber']++;
        $this->session->set('booking', $this->bookingData);
    }

    public function previousPageCalandar(): void
    {
        $this->bookingData = $this->session->get('booking');
        $this->bookingData['weekNumber']--;
        $this->session->set('booking', $this->bookingData);
    }

    public function isSelectedDate() : bool
    {
        $this->bookingData = $this->session->get('booking');
        $selectedDates = $this->bookingData['selectedDates'];
        if (isset($selectedDates) && !empty($selectedDates)) {
            return true;
        }
        return false;
    }

    public function validateShedule(UserInterface $user) : bool {
        $this->bookingData = $this->session->get('booking');
        $selectedDates = $this->bookingData['selectedDates'];

        if ($user->getSwimmingPackBalance()->getCalculateRemainingAmount() < count($selectedDates)) {
            return false;
        }

        foreach ($selectedDates as $selectedDate) {
            $bookingLesson = new BookingLesson();

            //check if the time slot is already exist
            $timeSlot = $this->timeSlotRepository->findOneBy([
                'educator' => $this->educatorRepository->find($this->bookingData['educatorId']),
                'startTime' => $selectedDate,
            ]);

            if (!$timeSlot) {
                $timeSlot = new TimeSlot();
                $timeSlot->setEducator($this->educatorRepository->find($this->bookingData['educatorId']));
                $newDate = new DateTime();
                $newDate->setTimestamp($selectedDate->getTimestamp());
                $timeSlot->setStartTime($selectedDate);
                $timeSlot->setEndTime($newDate->add(new DateInterval('PT20M')));
                $this->timeSlotRepository->save($timeSlot, true);
            }

            $bookingLesson->setChilds($this->childRepository->find($this->bookingData['childsId']));
            $bookingLesson->setStatus("Booked");
            $bookingLesson->setSwimmingPackBalance($user->getSwimmingPackBalance());
            $bookingLesson->setPurchaseDate(new DateTime());
            $bookingLesson->setTimeSlot($timeSlot);

            $this->bookingLessonRepository->save($bookingLesson, true);
        }
        $this->removeBookingSession();
        return true;
    }

    public function removeBookingSession() {
        $this->session->remove('booking');
    }

    public function getEducatorTimeSlots() {
        $this->bookingData = $this->session->get('booking');
        $educator = $this->educatorRepository->find($this->bookingData['educatorId']);
        $timeSlots = $educator->getTimeSlot();
        $timeSlotsArray = [];
        foreach ($timeSlots as $timeSlot) {
            if ($timeSlot->getBookingLessonsCount() > 2) {
                $timeSlotsArray[] = $timeSlot->getStartTime();
            }
        }
        return $timeSlotsArray;
    }
}