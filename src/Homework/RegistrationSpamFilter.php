<?php


namespace App\Homework;


class RegistrationSpamFilter
{
    public function filter(string $email): bool
    {
        return !preg_match('/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[ru|com|org])/', $email);
    }
}