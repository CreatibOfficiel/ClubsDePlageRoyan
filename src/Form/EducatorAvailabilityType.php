<?php

namespace App\Form;

use App\Entity\Educator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EducatorAvailabilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('availableDateFrom', DateType::class, [
            'widget' => 'choice',
            'required' => true,
            'format' => 'ddMMMMyyyy',
            'days' => range(1,31),
            'months' => range(1,12),
            'years' => range(date('Y'), date('Y')-1)
        ])
        ->add('availableDateTo', DateType::class, [
            'widget' => 'choice',
            'required' => true,
            'format' => 'ddMMMMyyyy',
            'days' => range(1,31),
            'months' => range(1,12),
            'years' => range(date('Y'), date('Y')-1)
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Educator::class,
        ]);
    }
}
