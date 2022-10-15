<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

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
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-floating mb-3',
                    'placeholder' => 'Email',
                ],
                'required' => false,
            ])
            ->add('telephone', TelType::class, [
                'attr' => [
                    'class' => 'form-floating mb-3',
                    'placeholder' => 'Telephone',
                ],
                'required' => false,
            ])
            ->add('objet', TextType::class, [
                'attr' => [
                    'class' => 'form-floating mb-3',
                    'placeholder' => 'Object',
                ],
                'required' => false,
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'class' => 'form-floating mb-3',
                    'placeholder' => 'Message',
                ],
                'required' => false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'attr' => [
                    'class' => 'mb-3',
                ],
                'required' => false,
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
