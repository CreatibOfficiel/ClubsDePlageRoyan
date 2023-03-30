<?php

namespace App\DTO;

use App\Entity\AbstractEntity;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserDto extends AbstractDto
{

    #[Assert\NotBlank]
    #[Assert\Length(max: 250)]
    public string $firstName;

    #[Assert\NotBlank]
    #[Assert\Length(max: 250)]
    public string $lastName;

    #[Assert\NotBlank]
    #[Assert\Length(max: 250)]
    public string $phoneNumber;

    public ?string $password = null;

    public ?string $passwordConfirm = null;

    #[Assert\NotBlank]
    public ?string $address = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $mail;

    #[Assert\NotBlank]
    public string $role;

    #[Assert\NotBlank]
    public int $lessonInitialAmount;

    public bool $isNew = true;

    /**
     * @param User $user
     */
    public function setFromEntity(AbstractEntity $user): void
    {
        $this->isNew = false;
        $this->address = $user->getAddress();
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->phoneNumber = $user->getPhoneNumber();
        $this->mail = $user->getMail();
        $this->role = $user->getStringRoles();
        $this->lessonInitialAmount = $user->getSwimmingPackBalance()->getInitialAmount();
    }
}