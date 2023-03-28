<?php

namespace App\Entity;

use App\DTO\AbstractDto;
use App\DTO\UserDto;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['mail'], message: 'There is already an account with this mail')]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $roles = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastLogin = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Child::class, orphanRemoval: true)]
    private Collection $childrens;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Educator $educator = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?SwimmingPackBalance $swimmingPackBalance = null;

    public function __construct()
    {
        $this->childrens = new ArrayCollection();
        $this->roles = ['ROLE_USER'];
    }

    /**
     * @param UserDto $dto
     */
    public function setFromDto(AbstractDto $dto): void
    {
        $this->setFirstName($dto->firstName);
        $this->setLastName($dto->lastName);
        $this->setPhoneNumber($dto->phoneNumber);
        $this->setAddress($dto->address);
        $this->setMail($dto->mail);
        if ($dto->role) {
            $this->setRoles([$dto->role]);
        }
        if ($dto->password) {
            $this->setPassword($dto->password);
        }
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

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getStringRoles(): string
    {
        return implode(', ', $this->roles);
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * @return Collection<int, Child>
     */
    public function getChildrens(): Collection
    {
        return $this->childrens;
    }

    public function addChildren(Child $children): self
    {
        if (!$this->childrens->contains($children)) {
            $this->childrens->add($children);
            $children->setParent($this);
        }

        return $this;
    }

    public function removeChildren(Child $children): self
    {
        if ($this->childrens->removeElement($children)) {
            // set the owning side to null (unless already changed)
            if ($children->getParent() === $this) {
                $children->setParent(null);
            }
        }

        return $this;
    }

    public function getEducator(): ?Educator
    {
        return $this->educator;
    }

    public function setEducator(Educator $educator): self
    {
        // set the owning side of the relation if necessary
        if ($educator->getUser() !== $this) {
            $educator->setUser($this);
        }

        $this->educator = $educator;

        return $this;
    }

    public function getSwimmingPackBalance(): ?SwimmingPackBalance
    {
        return $this->swimmingPackBalance;
    }

    public function setSwimmingPackBalance(?SwimmingPackBalance $swimmingPackBalance): self
    {
        // unset the owning side of the relation if necessary
        if ($swimmingPackBalance === null && $this->swimmingPackBalance !== null) {
            $this->swimmingPackBalance->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($swimmingPackBalance !== null && $swimmingPackBalance->getUser() !== $this) {
            $swimmingPackBalance->setUser($this);
        }

        $this->swimmingPackBalance = $swimmingPackBalance;

        return $this;
    }

    public function eraseCredentials() {
    }

    public function getUserIdentifier(): string
    {
        return $this->getMail();
    }
}
