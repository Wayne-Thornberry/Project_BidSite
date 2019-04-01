<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'mapped' => true,
                'required' => true,
                'attr' =>[
                    'class' => 'form-control',
                    'placeholder' => 'Username',
                ],
                'label' => 'Username',
                'label_attr' =>[
                    'class' => 'sr-only'
                ]
                ))
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'attr' =>[
                    'class' => 'form-control',
                    'placeholder' => 'Password',
                ],
                'label' => 'Password',
                'label_attr' =>[
                    'class' => 'sr-only'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
