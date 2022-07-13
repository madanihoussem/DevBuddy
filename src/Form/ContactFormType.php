<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-floating mb-3',
                    'placeholder' => 'Nom',
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-floating mb-3',
                    'placeholder' => 'Email',
                ],
            ])
            ->add('objet', TextType::class, [
                'attr' => [
                    'class' => 'form-floating mb-3',
                    'placeholder' => 'Object',
                ],
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'class' => 'form-floating mb-3',
                    'placeholder' => 'Message',
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'attr' => [
                    'class' => 'mb-3',
                ],
                'mapped' => false,
                'label' => "Terms & conditions of data collection",
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
