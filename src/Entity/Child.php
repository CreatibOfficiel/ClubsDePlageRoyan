<?php

namespace App\Entity;

use App\Repository\ChildRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChildRepository::class)]
class Child extends AbstractEntity
{
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\ManyToOne(inversedBy: 'childrens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $parent = null;

    #[ORM\OneToMany(mappedBy: 'childs', targetEntity: BookingLesson::class)]
    private Collection $bookingLessons;

    public function __construct()
    {
        $this->bookingLessons = new ArrayCollection();
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getParent(): ?User
    {
        return $this->parent;
    }

    public function setParent(?User $parent): self
    {
        $this->parent = $parent;

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
            $bookingLesson->setChilds($this);
        }

        return $this;
    }

    public function removeBookingLesson(BookingLesson $bookingLesson): self
    {
        if ($this->bookingLessons->removeElement($bookingLesson)) {
            // set the owning side to null (unless already changed)
            if ($bookingLesson->getChilds() === $this) {
                $bookingLesson->setChilds(null);
            }
        }

        return $this;
    }
}
