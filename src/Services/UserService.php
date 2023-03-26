<?php

namespace App\Services;

use App\DTO\AbstractDto;
use App\DTO\UserDto;
use App\Entity\AbstractEntity;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserService extends AbstractEntityService
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct($userRepository);
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param UserDto $dto
     * @param User $entity
     */
    public function addOrUpdate(AbstractDto $dto, AbstractEntity $entity): void {
        $userWithNewMail = $this->repository->findByMail($dto->mail);
        if ($userWithNewMail && ($dto->isNew || $userWithNewMail->getId() !== $entity->getId())) {
            throw new Exception('Il y a déjà un utilisateur avec cette adresse mail');
        }
        if ($dto->password) {
            $dto->password = $this->encodePassword($entity, $dto->password);
        }
        parent::addOrUpdate($dto, $entity);
    }

    public function encodePassword(PasswordAuthenticatedUserInterface $user, string $value): string {
        return $this->passwordHasher->hashPassword($user, $value);
    }

    public function updateLastLogin(User $user): void {
        $user->setLastLogin(new DateTime());
        $this->repository->save($user, true);
    }
}