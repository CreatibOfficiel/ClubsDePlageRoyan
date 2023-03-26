<?php

namespace App\Services;

use App\DTO\AbstractDto;
use App\Entity\AbstractEntity;
use App\Repository\AbstractRepository;

abstract class AbstractEntityService
{

    protected AbstractRepository $repository;

    public function __construct(AbstractRepository $repository)
    {
        $this->repository = $repository;
    }

    public function addOrUpdate(AbstractDto $dto, AbstractEntity $entity): void
    {
        $entity->setFromDto($dto);
        $this->repository->save($entity, true);
    }

    public function delete(AbstractEntity $entity): void
    {
        $this->repository->remove($entity, true);
    }

}