<?php

namespace App\Controller;

use App\DTO\ChildDto;
use App\DTO\UserDto;
use App\Entity\Child;
use App\Entity\Educator;
use App\Entity\User;
use App\Form\ChildType;
use App\Form\EducatorAvailabilityType;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\ChildRepository;
use App\Repository\EducatorRepository;
use App\Services\ChildService;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public UserService $userService;
    private ChildService $childService;

    public function __construct(
        UserService $userService,
        ChildService $childService
    ) {
        $this->userService = $userService;
        $this->childService = $childService;
    }

    /**
     * @throws \Exception
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $userDto = new UserDto();
        $userDto->role = 'ROLE_USER';
        $userDto->lessonInitialAmount = 0;

        $form = $this->createForm(RegistrationFormType::class, $userDto, ['validation_groups' => ['Default', 'add'], 'attr' => ['isNotAdmin' => true]]);
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

        $form = $this->createForm(UserType::class, $userDto, ['attr' => ['isNotAdmin' => true]]);
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

        if ($this->isGranted('ROLE_EDUCATOR') && !$this->isGranted('ROLE_ADMIN') && $user->getEducator()) {
            $nextCourses = $this->userService->getNextCourses($user);
        }

        return $this->render('users/show.html.twig', [
            'user' => $user,
            'childs' => $user->getChildrens(),
            'educatorAvailability' => $user->getEducator(),
            'nextCourses' => $nextCourses ?? null
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

    /**
     * @throws \Exception
     */
    #[Route('/user/add/child', name: 'app_add_child', methods: ['GET', 'POST'])]
    public function addChildIndex(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $childDto = new ChildDto();
        $childDto->parent = $user;

        $form = $this->createForm(ChildType::class, $childDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $child = new Child();
            $this->childService->addOrUpdate($childDto, $child);
            $this->addFlash('success', 'Enfant ajouté avec succès!');

            return $this->redirectToRoute('app_user');
        }

        return $this->render('users/edit_child.html.twig', [
            'user' => $user,
            'childForm' => $form->createView(),
            'isNewChild' => true
        ]);
    }

    #[Route('/user/delete/child/{id}', name: 'app_delete_child')]
    public function deleteChildIndex($id, ChildRepository $childRepository, ChildService $childService): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $child = $childRepository->find($id);
        $childService->delete($child);

        $this->addFlash('success', 'Enfant supprimé avec succès!');

        return $this->redirectToRoute('app_user');
    }

    #[Route('/user/edit/child/{id}', name: 'app_edit_child', methods: ['GET', 'POST'])]
    public function editChildIndex(Request $request, $id, ChildRepository $childRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $child = $childRepository->find($id);

        $childDto = new ChildDto();
        $childDto->setFromEntity($child);
        $childDto->parent = $user;

        $form = $this->createForm(ChildType::class, $childDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->childService->addOrUpdate($childDto, $child);
            $this->addFlash('success', 'Enfant ajouté avec succès!');

            return $this->redirectToRoute('app_user');
        }

        return $this->render('users/edit_child.html.twig', [
            'user' => $user,
            'childForm' => $form->createView(),
            'isNewChild' => false
        ]);
    }

    #[Route('/user/educator/availability', name: 'app_educator_availability', methods: ['GET', 'POST'])]
    public function educatorAvailabilityIndex(Request $request, EducatorRepository $educatorRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $educator = $user->getEducator();
        if (!$educator && $this->isGranted('ROLE_EDUCATOR')) {
            $educator = new Educator();
            $educator->setUser($user);
        }

        $form = $this->createForm(EducatorAvailabilityType::class, $educator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $educator->setAvailableDateFrom($form->get('availableDateFrom')->getData());
            $educator->setAvailableDateTo($form->get('availableDateTo')->getData());


            $educatorRepository->save($educator, true);

            return $this->redirectToRoute('app_user');
        }

        return $this->render('users/educator_availability.html.twig', [
            'user' => $user,
            'availabilityForm' => $form->createView()
        ]);
    }
}
