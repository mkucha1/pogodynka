<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
                'attr' => ['placeholder' => 'Enter city name'],
                'constraints' => [
                    new Assert\NotBlank(groups: ['new', 'edit']),
                    new Assert\Length(min: 2, max: 255, groups: ['new', 'edit']),
                ],
            ])
            ->add('country', ChoiceType::class, [
                'choices' => [
                    'France' => 'FR',
                    'Italy' => 'IT',
                    'Spain' => 'ES',
                    'Poland' => 'PL',
                    'Germany' => 'DE',
                    'United Kingdom' => 'GB',
                    'United States' => 'US',
                ],
                'constraints' => [
                    new Assert\NotBlank(groups: ['new', 'edit']),
                    new Assert\Choice(
                        choices: ['FR', 'IT', 'ES', 'PL', 'DE', 'GB', 'US'],
                        groups: ['new', 'edit']
                    ),
                ],
            ])
            ->add('latitude', NumberType::class, [
                'scale' => 7,
                'attr' => ['min' => -90, 'max' => 90,],
                'constraints' => [
                    new Assert\NotBlank(groups: ['new', 'edit']),
                ],
            ])
            ->add('longitude', NumberType::class, [
                'scale' => 7,
                'attr' => ['min' => -180, 'max' => 180,],
                'constraints' => [
                    new Assert\NotBlank(groups: ['new', 'edit']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
