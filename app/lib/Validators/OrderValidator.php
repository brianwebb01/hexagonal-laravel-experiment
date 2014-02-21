<?php namespace Validators;

class OrderValidator extends Validator
{
    /**
     * Array of validation rules
     *
     * @var array
     */
    public static $rules = [
        'order_number' => 'required'
    ];
}
