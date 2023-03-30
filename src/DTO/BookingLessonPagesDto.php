<?php

namespace App\DTO;

use App\Entity\AbstractEntity;
use App\Entity\Child;
use App\Entity\Club;
use App\Entity\Educator;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class BookingLessonPagesDto extends AbstractDto
{

    public Club $club;

    #[Assert\Type(type: \DateTimeInterface::class)]
    public \DateTimeInterface $dateFrom;

    #[Assert\Type(type: \DateTimeInterface::class)]
    public \DateTimeInterface $dateTo;

    public Educator $educator;

    public Child $child;


    public function setFromEntity(AbstractEntity $entity): void
    {
        // Implement setFromEntity() method.
    }
}