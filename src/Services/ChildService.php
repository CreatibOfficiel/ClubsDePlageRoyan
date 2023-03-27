<?php

namespace App\Services;

use App\DTO\AbstractDto;
use App\DTO\ChildDto;
use App\Entity\AbstractEntity;
use App\Entity\Child;
use App\Repository\UserRepository;
use Exception;

class ChildService extends AbstractEntityService
{
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct($userRepository);
    }

    /**
     * @param ChildDto $dto
     * @param Child $entity
     */
    public function addOrUpdate(AbstractDto $dto, AbstractEntity $entity): void {
        $parent = $dto->parent;
        $childrens = $parent->getChildrens();
        foreach ($childrens as $children) {
            if ($children->getBirthdate() === $dto->birthdate && $children->getFirstName() === $dto->firstName
                && $children->getLastName() === $dto->lastName) {
                throw new Exception('Il y a déjà un enfant avec cette date de naissance et ce prénom');
            } else {
                //check if the birthdate is between 3 and 14 years old
                $birthdate = $dto->birthdate;
                $birthdate = $birthdate->format('Y-m-d');
                $birthdate = new \DateTime($birthdate);
                $now = new \DateTime();
                $interval = $now->diff($birthdate);
                $age = $interval->format('%y');
                if ($age < 3 || $age > 14) {
                    throw new Exception('L\'âge de l\'enfant doit être compris entre 3 et 14 ans');
                }
            }
        }

        parent::addOrUpdate($dto, $entity);
    }
}