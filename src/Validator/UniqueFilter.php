<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueFilter extends Constraint
{
    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
//    public $message = 'The value "{{ value }}" should contain ru, com, org';
    public $message = 'Такой пользователь уже существует';
}