<?php

namespace App\Controller;

use App\DTO\UserDto;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $userDto = new UserDto($user);

        $form = $this->createForm(RegistrationFormType::class, $userDto);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            // TODO : ask teacher about this, because it's not working with dto
            $password = $form->get('plainPassword')->getData();
            $passwordConfirm = $form->get('plainPasswordConfirm')->getData();

            if ($password && $password !== $passwordConfirm) {
                $form->get('plainPasswordConfirm')->addError(new FormError('Les mots de passes ne correspondent pas'));
            }

            if ($form->isValid()) {
                $user->setMail($userDto->mail);
                var_dump($userDto);
                $user->setFirstName($userDto->firstName);
                $user->setLastName($userDto->lastName);
                $user->setAddress($userDto->address);
                $user->setPhoneNumber($userDto->phoneNumber);

                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $password
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
