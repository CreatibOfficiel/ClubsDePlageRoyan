<?php

namespace App\Entity;

use App\Repository\TimeSlotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeSlotRepository::class)]
class TimeSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\ManyToOne(inversedBy: 'timeSlot')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Educator $educator = null;

    #[ORM\OneToMany(mappedBy: 'timeSlot', targetEntity: BookingLesson::class)]
    private Collection $bookingLessons;

    public function __construct()
    {
        $this->bookingLessons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getEducator(): ?Educator
    {
        return $this->educator;
    }

    public function setEducator(?Educator $educator): self
    {
        $this->educator = $educator;

        return $this;
    }

    /**
     * @return Collection<int, BookingLesson>
     */
    public function getBookingLessons(): Collection
    {
        return $this->bookingLessons;
    }

    public function addBookingLesson(BookingLesson $bookingLesson): self
    {
        if (!$this->bookingLessons->contains($bookingLesson)) {
            $this->bookingLessons->add($bookingLesson);
            $bookingLesson->setTimeSlot($this);
        }

        return $this;
    }

    public function removeBookingLesson(BookingLesson $bookingLesson): self
    {
        if ($this->bookingLessons->removeElement($bookingLesson)) {
            // set the owning side to null (unless already changed)
            if ($bookingLesson->getTimeSlot() === $this) {
                $bookingLesson->setTimeSlot(null);
            }
        }

        return $this;
    }
}
