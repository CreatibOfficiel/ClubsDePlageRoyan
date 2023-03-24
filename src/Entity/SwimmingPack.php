<?php

namespace App\Entity;

use App\Repository\SwimmingPackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SwimmingPackRepository::class)]
class SwimmingPack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $lessonsAmount = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToMany(targetEntity: SwimmingPackBalance::class, mappedBy: 'swimmingP�acks')]
    private Collection $swimmingPackBalances;

    public function __construct()
    {
        $this->swimmingPackBalances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLessonsAmount(): ?int
    {
        return $this->lessonsAmount;
    }

    public function setLessonsAmount(int $lessonsAmount): self
    {
        $this->lessonsAmount = $lessonsAmount;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, SwimmingPackBalance>
     */
    public function getSwimmingPackBalances(): Collection
    {
        return $this->swimmingPackBalances;
    }

    public function addSwimmingPackBalance(SwimmingPackBalance $swimmingPackBalance): self
    {
        if (!$this->swimmingPackBalances->contains($swimmingPackBalance)) {
            $this->swimmingPackBalances->add($swimmingPackBalance);
            $swimmingPackBalance->addSwimmingPAck($this);
        }

        return $this;
    }

    public function removeSwimmingPackBalance(SwimmingPackBalance $swimmingPackBalance): self
    {
        if ($this->swimmingPackBalances->removeElement($swimmingPackBalance)) {
            $swimmingPackBalance->removeSwimmingPAck($this);
        }

        return $this;
    }
}
