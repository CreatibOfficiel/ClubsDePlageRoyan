<?php

namespace App\Controller\Admin;

use App\DTO\UserDto;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/users')]
class AdminUsersController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/', name: 'app_admin_users_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_users_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/users/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
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

                $this->addFlash('success', 'Informations mise à jour !');

                return $this->redirectToRoute('app_admin_users_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_users_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_admin_users_index', [], Response::HTTP_SEE_OTHER);
    }
}
