<?php

namespace App\ReadModel\Activity\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('action', Type\TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Action',
                    'onchange' => 'this.form.submit()',
                ],
            ])
            ->add('user', Type\TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'User',
                    'onchange' => 'this.form.submit()',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Filter::class, 'method' => 'GET', 'csrf_protection' => false]);
    }
}