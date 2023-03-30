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

    public function setBookingData(array $bookingData): void
    {
        $this->bookingData = $bookingData;
        $this->session->set('booking', $this->bookingData);
    }

    public function getClubs(): array
    {
        return $this->clubRepository->findAll();
    }

    public function getEducatorsByClubId(int $clubId): array
    {
        return $this->educatorRepository->findBy(['club' => $clubId]);
    }

    public function getEducatorById(int $educatorId): Educator
    {
        return $this->educatorRepository->find($educatorId);
    }

    public function getClubById(int $clubId): Club
    {
        return $this->clubRepository->find($clubId);
    }

    public function getTimeslotsByEducatorId(int $educatorId): array
    {
        return $this->timeSlotRepository->findBy(['educator' => $educatorId]);
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

    public function getDateFrom(): DateTime
    {
        return $this->session->get('booking')['dateFrom'];
    }

    public function getDateTo(): DateTime
    {
        return $this->session->get('booking')['dateTo'];
    }

    public function getUserByEducatorId(int $educatorId): User
    {
        return $this->educatorRepository->find($educatorId)->getUser();
    }

    public function getUsersByRoleEducator(): array
    {
        return $this->userRepository->findBy(['roles' => 'ROLE_EDUCATOR']);
    }

    public function getUsersFullNameByRoleEducator(): array
    {
        $users = $this->getUsersByRoleEducator();
        $usersFullName = [];
        foreach ($users as $user) {
            $usersFullName[$user->getId()] = $user->getFullName();
        }
//        return $usersFullName;
        return [];
    }


    public function getSelectedClub(): Club
    {
        return $this->clubRepository->find($this->getClubId());
    }

    public function getChildsFromUser(int $id): array
    {
        $user = $this->userRepository->find($id);
//        return $user->getChildrens()->toArray();
        return [];
    }

    public function setChildsId(int $childsId): void
    {
        $this->bookingData = $this->session->get('booking');
        $this->bookingData['childsId'] = $childsId;
        $this->session->set('booking', $this->bookingData);
    }

    public function getChildsId(): int
    {
        return $this->getBookingData()['childsId'];
    }

    //clear session
    public function clearSession(): void
    {
        $this->session->remove('booking');
    }

    public function getEducatorBySelectedClub(): array
    {
        return $this->educatorRepository->findBy(['club' => $this->getSelectedClub()->getId()]);
    }

    public function getWeekNumber(): int
    {
        return $this->getBookingData()['weekNumber'];
    }

    public function setWeekNumber(int $weekNumber): void
    {
        $this->bookingData = $this->session->get('booking');
        $this->bookingData['weekNumber'] = $weekNumber;
        $this->session->set('booking', $this->bookingData);
    }

    public function getTime(DateTime $date): array {
        //get array of 20 minutes DateTime between 10:00 and 18:00 for a specific day
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

    public function getWeek(DateTime $d1, DateTime $d2, int $weekNumber = 0): array
    {

        $weekDay = [
                'day' => '',
                'times' => [
                    'time' => '',
                    'isAvailable' => true,
                    'isSelect' => false,
                ],
        ];

        $weekDays = [];
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($d1, $interval, $d2);
        $week = [];
        foreach ($period as $day) {
            $week[] = $day;
        }

        $bookingData = $this->session->get('booking');

        $days = array_slice($week, $weekNumber*7, 7);
        foreach ($days as $day) {
            $timesAlreadyBooked = $this->getEducatorTimeSlots();
            $times = [];
            foreach ($this->getTime($day) as $time) {
                if (in_array($time, $timesAlreadyBooked)) {
                   $times[] = [
                       'time' => $time,
                       'isAvailable' => false,
                       'isSelect' => false,
                   ];
               } elseif (in_array($time, $bookingData['selectedDates'])) {
                   $times[] = [
                       'time' => $time,
                       'isAvailable' => true,
                       'isSelect' => true,
                   ];
               } else {
                   $times[] = [
                       'time' => $time,
                       'isAvailable' => true,
                       'isSelect' => false,
                   ];
               }
            }
            $weekDay = [
                'day' => $day,
                'times' => $times,
            ];
            if ($day->format('l') != 'Sunday') {
                $weekDays[] = $weekDay;
            }
        }
        return $weekDays;
    }

    public function getSelectedDates(): array
    {
        return $this->getBookingData()['selectedDates'];
    }

    public function addSelectedDate(DateTime $date): void
    {
        $this->bookingData = $this->session->get('booking');
        $this->bookingData['selectedDates'][] = $date;
        $this->session->set('booking', $this->bookingData);
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

    public function validateShedule(UserInterface $user) {
        $this->bookingData = $this->session->get('booking');
        $selectedDates = $this->bookingData['selectedDates'];

        foreach ($selectedDates as $selectedDate) {
            $bookingLesson = new BookingLesson();
            $timeSlot = new TimeSlot();

            $timeSlot->setEducator($this->educatorRepository->find($this->bookingData['educatorId']));
            $timeSlot->setStartTime($selectedDate);
            $newDate = $selectedDate;
            $newDate = $newDate->add(new DateInterval('PT20M'));
            $timeSlot->setEndTime($newDate);

            $this->timeSlotRepository->save($timeSlot, true);

            $bookingLesson->setChilds($this->childRepository->find($this->bookingData['childsId']));
            $bookingLesson->setStatus("Booked");
            $bookingLesson->setSwimmingPackBalance($user->getSwimmingPackBalance());
            $bookingLesson->setPurchaseDate(new DateTime());
            $bookingLesson->setTimeSlot($timeSlot);

            $this->bookingLessonRepository->save($bookingLesson, true);
        }
        $this->removeBookingSession();
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
            $timeSlotsArray[] = $timeSlot->getStartTime();
        }
        return $timeSlotsArray;
    }
}