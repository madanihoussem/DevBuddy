<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use App\Entity\User;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => "Username",
                    'class' => 'form-floating rounded',
                ],
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-floating rounded',
                    'placeholder' => "Email",
                ],
                'required' => false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'required' => false,
                'label' => 'I agree to all the Term, Privacy Policy and Fees.',
                'label_attr' => [
                    'class' => 'fs-6 text-muted text-start'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                'type' => PasswordType::class,
                "first_options" => [
                    'attr' => [
                    'class' => 'form-floating mb-0 rounded',
                    'placeholder' => "Password",
                    ],
                    'required' => false,
                ],
                "second_options" => [
                    'attr' => [
                    'class' => 'form-floating mb-0 rounded',
                    'placeholder' => "Repeat password",
                    ],
                    'required' => false,
                ],
                // this is read and encoded in the controller
                'mapped' => false,
                'required' => false,
                'attr' => ['autocomplete' => 'new-password'],
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
