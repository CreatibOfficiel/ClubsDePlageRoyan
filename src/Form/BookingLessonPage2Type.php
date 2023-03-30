<?php

namespace App\Form;

use App\Entity\Child;
use App\Entity\Club;
use App\Entity\Educator;
use App\Entity\User;
use App\Repository\ChildRepository;
use App\Repository\ClubRepository;
use App\Repository\UserRepository;
use App\Services\UserService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingLessonPage2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('educator', EntityType::class, [
                'class' => Educator::class,
                'label' => 'Moniteur',
                'placeholder' => 'Sectionner un moniteur',
                'required' => true,
//                'query_builder' => function (UserRepository $er) use ($options) {
//                    return $er->findByEducatorByClub($options['club']);
//                },
                'choices' => $options['club']->getEducators(),
                'choice_label' => function (Educator $user) {
                    return $user->getUser()->getFullName();
                },
    //                'attr' => [
    //                    'class' => 'form-control',
    //                ],
            ])
            ->add('child', EntityType::class, [
                'class' => Child::class,
                'label' => 'Enfant',
                'placeholder' => 'Sectionner un enfant',
                'required' => true,
                'choices' => $options['user']->getChildrens(),
                'choice_label' => function (Child $child) {
                    return $child->getFullName();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "user" => null,
            "club" => null,
            "educator" => null,
        ]);
    }
}
