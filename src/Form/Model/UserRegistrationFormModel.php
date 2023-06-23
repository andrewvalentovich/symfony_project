<?php

namespace App\Form\Model;


use App\Validator\SpamFilter;
use App\Validator\UniqueFilter;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank(message="Это поле является обязательным")
     * @UniqueFilter()
     */
    public $email;

    /**
     * @Assert\NotBlank(message="Это поле является обязательным")
     */
    public $firstName;

    /**
     * @Assert\NotBlank(message="Это поле является обязательным")
     * @Assert\Length(min="6", minMessage="Пароль должен содержать минимум 6 символов")
     */
    public $plainPassword;

    /**
     * @Assert\IsTrue(message="Это поле является обязательным")
     */
    public $agreeTerms;

}