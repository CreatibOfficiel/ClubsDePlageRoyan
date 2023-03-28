<?php

namespace App\Entity;

use App\Repository\EducatorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EducatorRepository::class)]
class Educator extends AbstractEntity
{
    #[ORM\ManyToOne(inversedBy: 'educators')]
    private ?Club $club = null;

    #[ORM\OneToOne(inversedBy: 'educator', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'educator', targetEntity: TimeSlot::class, orphanRemoval: true)]
    private Collection $timeSlot;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $availableDateFrom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $availableDateTo = null;

    public function __construct()
    {
        $this->timeSlot = new ArrayCollection();
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, TimeSlot>
     */
    public function getTimeSlot(): Collection
    {
        return $this->timeSlot;
    }

    public function addTimeSlot(TimeSlot $timeSlot): self
    {
        if (!$this->timeSlot->contains($timeSlot)) {
            $this->timeSlot->add($timeSlot);
            $timeSlot->setEducator($this);
        }

        return $this;
    }

    public function removeTimeSlot(TimeSlot $timeSlot): self
    {
        if ($this->timeSlot->removeElement($timeSlot)) {
            // set the owning side to null (unless already changed)
            if ($timeSlot->getEducator() === $this) {
                $timeSlot->setEducator(null);
            }
        }

        return $this;
    }

    public function getAvailableDateFrom(): ?\DateTimeInterface
    {
        return $this->availableDateFrom;
    }

    public function setAvailableDateFrom(?\DateTimeInterface $availableDateFrom): self
    {
        $this->availableDateFrom = $availableDateFrom;

        return $this;
    }

    public function getAvailableDateTo(): ?\DateTimeInterface
    {
        return $this->availableDateTo;
    }

    public function setAvailableDateTo(?\DateTimeInterface $availableDateTo): self
    {
        $this->availableDateTo = $availableDateTo;

        return $this;
    }
}
