<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\SpamFilter;
use App\Validator\SpamFilterValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints'   =>  [
                    new SpamFilter()
                ]
            ])
            ->add('firstName')
            ->add('plainPassword', PasswordType::class, [
                'mapped'    =>  false,
                'constraints'    =>  [
                    new NotBlank([
                        'message'   =>  'Пароль не указан'
                    ]),
                    new Length([
                        'min'   =>  6,
                        'minMessage'   =>  'Пароль должен содержать минимум 6 символов'
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped'    =>  false,
                'constraints'   =>  [
                    new IsTrue([
                        'message'   =>  'Это поле является обязательным'
                    ])
                ]
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