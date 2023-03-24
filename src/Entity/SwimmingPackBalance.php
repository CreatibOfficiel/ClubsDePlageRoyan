<?php

namespace App\Entity;

use App\Repository\SwimmingPackBalanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SwimmingPackBalanceRepository::class)]
class SwimmingPackBalance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $initialAmount = null;

    #[ORM\OneToOne(inversedBy: 'swimmingPackBalance', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: SwimmingPack::class, inversedBy: 'swimmingPackBalances')]
    private Collection $swimmingP�acks;

    #[ORM\OneToMany(mappedBy: 'swimmingPackBalance', targetEntity: BookingLesson::class)]
    private Collection $bookingLessons;

    public function __construct()
    {
        $this->swimmingP�acks = new ArrayCollection();
        $this->bookingLessons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInitialAmount(): ?int
    {
        return $this->initialAmount;
    }

    public function setInitialAmount(int $initialAmount): self
    {
        $this->initialAmount = $initialAmount;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, SwimmingPack>
     */
    public function getSwimmingP�acks(): Collection
    {
        return $this->swimmingP�acks;
    }

    public function addSwimmingPAck(SwimmingPack $swimmingPAck): self
    {
        if (!$this->swimmingP�acks->contains($swimmingPAck)) {
            $this->swimmingP�acks->add($swimmingPAck);
        }

        return $this;
    }

    public function removeSwimmingPAck(SwimmingPack $swimmingPAck): self
    {
        $this->swimmingP�acks->removeElement($swimmingPAck);

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
            $bookingLesson->setSwimmingPackBalance($this);
        }

        return $this;
    }

    public function removeBookingLesson(BookingLesson $bookingLesson): self
    {
        if ($this->bookingLessons->removeElement($bookingLesson)) {
            // set the owning side to null (unless already changed)
            if ($bookingLesson->getSwimmingPackBalance() === $this) {
                $bookingLesson->setSwimmingPackBalance(null);
            }
        }

        return $this;
    }
}
