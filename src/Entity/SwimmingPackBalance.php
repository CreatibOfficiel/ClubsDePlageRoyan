<?php

namespace App\Entity;

use App\Repository\SwimmingPackBalanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SwimmingPackBalanceRepository::class)]
class SwimmingPackBalance extends AbstractEntity
{
    #[ORM\Column]
    private ?int $initialAmount = null;

    #[ORM\OneToOne(inversedBy: 'swimmingPackBalance', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: SwimmingPack::class, inversedBy: 'swimmingPackBalances')]
    private Collection $swimmingPacks;

    #[ORM\OneToMany(mappedBy: 'swimmingPackBalance', targetEntity: BookingLesson::class)]
    private Collection $bookingLessons;

    public function __construct()
    {
        $this->swimmingPacks = new ArrayCollection();
        $this->bookingLessons = new ArrayCollection();
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

    public function setCalculateInitialAmount(): self
    {
        $amount = 0;
        foreach ($this->swimmingPacks as $swimmingPack) {
            $amount += $swimmingPack->getLessonsAmount();
        }
        $this->initialAmount = $amount;

        return $this;
    }

    public function getCalculateRemainingAmount(): int
    {
        // TODO : check if bookingLessons are validated
        return $this->initialAmount - $this->bookingLessons->count();
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
    public function getSwimmingPacks(): Collection
    {
        return $this->swimmingPacks;
    }

    public function addSwimmingPack(SwimmingPack $swimmingPack): self
    {
        if (!$this->swimmingPacks->contains($swimmingPack)) {
            $this->swimmingPacks->add($swimmingPack);
        }

        return $this;
    }

    public function removeSwimmingPack(SwimmingPack $swimmingPack): self
    {
        $this->swimmingPacks->removeElement($swimmingPack);

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
