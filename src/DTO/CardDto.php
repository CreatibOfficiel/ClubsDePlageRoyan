<?php

namespace App\DTO;

use App\Entity\AbstractEntity;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeInterface;

class CardDto extends AbstractDto
{

    public string $cardOwner;
    #[Assert\CardScheme(schemes: ['VISA', 'MASTERCARD'])]
    public int $cardNumber;
    public DateTimeInterface $expirationDate;

    #[Assert\Length(min: 3, max: 3)]
    public int $cvc;

    public function setFromEntity(AbstractEntity $entity): void {}
}