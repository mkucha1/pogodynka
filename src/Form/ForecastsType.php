<?php

namespace App\Form;

use App\Entity\Forecasts;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ForecastsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\NotBlank(groups: ['new', 'edit']),
                    new Assert\Type(\DateTimeInterface::class, groups: ['new', 'edit']),
                ],
            ])
            ->add('temperature', NumberType::class, [
                'scale' => 1,
                'attr' => [
                    'min' => -90,
                    'max' => 60,
                    'step' => '0.1',
                    'placeholder' => 'e.g. 21.5',
                ],
                'constraints' => [
                    new Assert\NotBlank(groups: ['new', 'edit']),
                ],
            ])
            ->add('humidity', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                    'placeholder' => '0 - 100 (%)',
                ],
                'constraints' => [
                    new Assert\NotBlank(groups: ['new', 'edit']),
                ],
            ])
            ->add('wind', NumberType::class, [
                'scale' => 1,
                'attr' => [
                    'min' => 0,
                    'max' => 400,
                    'step' => '0.1',
                    'placeholder' => 'Wind speed (km/h)',
                ],
                'constraints' => [
                    new Assert\NotBlank(groups: ['new', 'edit']),
                    new Assert\Range(min: 0, max: 400, groups: ['new', 'edit']),
                ],
            ])
            ->add('pressure', IntegerType::class, [
                'attr' => [
                    'min' => 850,
                    'max' => 1100,
                    'placeholder' => 'e.g. 1013 (hPa)',
                ],
                'constraints' => [
                    new Assert\NotBlank(groups: ['new', 'edit']),
                    new Assert\Range(min: 850, max: 1100, groups: ['new', 'edit']),
                ],
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'city',
                'placeholder' => 'Select a location',
                'constraints' => [
                    new Assert\NotBlank(groups: ['new', 'edit']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forecasts::class,
        ]);
    }
}
