<?php

namespace App\Form;

use App\DTO\BookingLessonPagesDto;
use App\Entity\Club;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingLessonPage1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'label' => 'Club',
                'placeholder' => 'Sectionner un club',
                'required' => true,
                'choice_label' => function (Club $club) {
                    return $club->getName();
                },
//                'attr' => [
//                    'class' => 'form-control',
//                ],
            ])
            ->add('dateFrom', DateType::class, [
                'label' => 'Date from',
                'format' => 'ddMMMMyyyy',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'days' => range(1,31),
                'months' => range(date('m'),12),
                'years' => range(date('Y'), date('Y')-1)
            ])
            ->add('dateTo', DateType::class, [
                'label' => 'Date to',
                'format' => 'ddMMMMyyyy',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'days' => range(1,31),
                'months' => range(date('m'),12),
                'years' => range(date('Y'), date('Y')-1)

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BookingLessonPagesDto::class
        ]);
    }
}
