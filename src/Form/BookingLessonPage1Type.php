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
            ->add('dateFrom', DateType::class, [
                'label' => 'Date de départ',
//                'format' => 'ddMMMMyyyy',
                'required' => true,
                'html5' => true,
                'widget' => 'single_text',
//                'attr' => [
//                    'class' => 'form-control',
//                ],
//                'days' => range(1,31),
//                'months' => range(date('m'),12),
//                'years' => range(date('Y'), date('Y')-1)
            ])
            ->add('dateTo', DateType::class, [
                'label' => 'Date d\'arrivé',
//                'format' => 'ddMMMMyyyy',
                'required' => true,
                'html5' => true,
                'widget' => 'single_text',
//                'attr' => [
//                    'class' => 'form-control',
//                ],
//                'days' => range(1,31),
//                'months' => range(date('m'),12),
//                'years' => range(date('Y'), date('Y')-1)

            ])
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

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BookingLessonPagesDto::class
        ]);
    }
}
