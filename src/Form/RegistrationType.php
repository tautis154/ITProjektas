<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Prasome ivesti savo varda',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Vardas yra per trumpas',
                        // max length allowed by Symfony for security reasons
                        'max' => 210,
                        'maxMessage' => 'Vardas yra per ilgas',
                    ])
                ]
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Prasome ivesti savo asmens koda',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Asmens kodas yra per trumpas',
                        // max length allowed by Symfony for security reasons
                        'max' => 210,
                        'maxMessage' => 'Asmens kodas yra per ilgas',
                    ])
                ]
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Prasome ivesti savo asmens koda',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Asmens kodas yra per trumpas',
                        // max length allowed by Symfony for security reasons
                        'max' => 210,
                        'maxMessage' => 'Asmens kodas yra per ilgas',
                    ])
                ]
            ])
            ->add('register', SubmitType::class, ['label' => 'Registruotis'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
