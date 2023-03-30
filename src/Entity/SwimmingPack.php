<?php

namespace App\Entity;

use App\Repository\SwimmingPackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SwimmingPackRepository::class)]
class SwimmingPack extends AbstractEntity
{
    #[ORM\Column]
    private ?int $lessonsAmount = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToMany(targetEntity: SwimmingPackBalance::class, mappedBy: 'swimmingPacks')]
    private Collection $swimmingPackBalances;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'swimmingPack', targetEntity: Order::class)]
    private Collection $orders;

    public function __construct()
    {
        $this->swimmingPackBalances = new ArrayCollection();
        $this->orders = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setSwimmingPack($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getSwimmingPack() === $this) {
                $order->setSwimmingPack(null);
            }
        }

        return $this;
    }
}
