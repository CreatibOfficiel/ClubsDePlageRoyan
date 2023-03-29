<?php

namespace App\Form;

use App\DTO\CardDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cardNumber', NumberType::class, [
                'label' => 'Numéro de carte',
                'attr' => [
                    'placeholder' => 'xxxx-xxxx-xxxx-xxxx',
                ],
            ])
            ->add('cvc', NumberType::class, [
                'label' => 'CVC',
                'attr' => [
                    'placeholder' => '•••',
                ],
            ])
            ->add('cardOwner', TextType::class, [
                'label' => 'Nom du titulaire de la carte',
                'attr' => [
                    'placeholder' => 'Entrez votre nom',
                ],
            ])
            ->add('expirationDate', DateType::class, [
                'label' => 'Date d\'expiration',
                'format' => 'ddMMyyyy',
                'months' => range(1,12),
                'years' => range(date('Y'), date('Y')+10),
                'attr' => [
                    'placeholder' => 'mm/aaaa',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CardDto::class,
        ]);
    }
}