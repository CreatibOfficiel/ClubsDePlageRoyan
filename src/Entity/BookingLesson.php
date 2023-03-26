<?php

namespace App\Entity;

use App\Repository\BookingLessonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingLessonRepository::class)]
class BookingLesson extends AbstractEntity
{
    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $purchaseDate = null;

    #[ORM\ManyToOne(inversedBy: 'bookingLessons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TimeSlot $timeSlot = null;

    #[ORM\ManyToOne(inversedBy: 'bookingLessons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Child $childs = null;

    #[ORM\ManyToOne(inversedBy: 'bookingLessons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SwimmingPackBalance $swimmingPackBalance = null;

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(\DateTimeInterface $purchaseDate): self
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    public function getTimeSlot(): ?TimeSlot
    {
        return $this->timeSlot;
    }

    public function setTimeSlot(?TimeSlot $timeSlot): self
    {
        $this->timeSlot = $timeSlot;

        return $this;
    }

    public function getChilds(): ?Child
    {
        return $this->childs;
    }

    public function setChilds(?Child $childs): self
    {
        $this->childs = $childs;

        return $this;
    }

    public function getSwimmingPackBalance(): ?SwimmingPackBalance
    {
        return $this->swimmingPackBalance;
    }

    public function setSwimmingPackBalance(?SwimmingPackBalance $swimmingPackBalance): self
    {
        $this->swimmingPackBalance = $swimmingPackBalance;

        return $this;
    }
}
