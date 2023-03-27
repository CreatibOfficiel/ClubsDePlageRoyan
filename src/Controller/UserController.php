<?php

namespace App\Controller;

use App\DTO\UserDto;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * @throws \Exception
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $userDto = new UserDto();

        $form = $this->createForm(RegistrationFormType::class, $userDto, ['validation_groups' => ['Default', 'add']]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($userDto->password && $userDto->password !== $userDto->passwordConfirm) {
                $form->get('plainPasswordConfirm')->addError(new FormError('Les mots de passes ne correspondent pas'));
            }

            if ($form->isValid()) {
                $user = new User();
                $this->userService->addOrUpdate($userDto, $user);

                $this->addFlash('success', 'Vous êtes inscrit !');

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('users/edit.html.twig', [
            'userForm' => $form->createView(),
            'isAnonymous' => true
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/user/edit', name: 'app_edit', methods: ['GET', 'POST'])]
    public function editIndex(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $userDto = new UserDto();
        $userDto->setFromEntity($user);

        $form = $this->createForm(UserType::class, $userDto);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($userDto->password && $userDto->password !== $userDto->passwordConfirm) {
                $form->get('passwordConfirm')->addError(new FormError('Les mots de passes ne correspondent pas'));
            }

            if ($form->isValid()) {
                $this->userService->addOrUpdate($userDto, $user);

                $this->addFlash('success', 'Vos informations ont été mises à jour !');

                return $this->redirectToRoute('app_user');
            }
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'userForm' => $form->createView(),
            'isAnonymous' => false
        ]);
    }

    #[Route('/user', name: 'app_user', methods: ['GET'])]
    public function show(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/delete', name: 'app_user_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte a été supprimé !');

        return $this->redirectToRoute('app_home');
    }
}
