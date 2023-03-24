<?php

namespace App\DTO;

use App\Entity\AbstractEntity;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO extends AbstractDTO
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

    #[Assert\NotBlank(groups: ["add"])]
    public ?string $password = null;

    #[Assert\NotBlank(groups: ["add"])]
    public ?string $passwordConfirm = null;

    #[Assert\NotBlank]
    public ?string $address = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $mail;

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
    }
}