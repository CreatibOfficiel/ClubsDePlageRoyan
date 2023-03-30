<?php

namespace App\DTO;

use App\Entity\AbstractEntity;
use App\Entity\Child;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ChildDto extends AbstractDto
{

    #[Assert\NotBlank]
    #[Assert\Length(max: 250)]
    public string $firstName;

    #[Assert\NotBlank]
    #[Assert\Length(max: 250)]
    public string $lastName;

    #[Assert\NotBlank]
    #[Assert\Type(type: \DateTimeInterface::class)]
    public ?\DateTimeInterface $birthdate;

    #[Assert\NotBlank]
    #[Assert\Type(type: UserInterface::class)]
    public User $parent;

    public bool $isNew = true;

    /**
     * @param Child $child
     */
    public function setFromEntity(AbstractEntity $child): void
    {
        $this->isNew = false;
        $this->lastName = $child->getLastName();
        $this->firstName = $child->getFirstName();
        $this->birthdate = $child->getBirthdate();
    }
}