<?php

namespace App\Form;

use App\Entity\Club;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du club'
            ])
            ->add('description', TextType::class, [
                'label' => 'Description'
            ])
            ->add('picturePath', TextType::class, [
                'label' => 'Chemin de l\'image'
            ])
            ->add('openingTime', TimeType::class, [
                'label' => 'Heure d\'ouverture'
            ])
            ->add('closingTime', TimeType::class, [
                'label' => 'Heure de fermeture'
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
