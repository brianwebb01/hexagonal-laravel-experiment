<?php

/**
 * This file contains a class that abstracts Laravel's Validator facade. Extend
 * this class and declare your errors and messages as static variables to clean
 * up validation in controllers.
 *
 * @package AnswerAutomationReports
 * @author  John Arstingstall <jarstingstall@indatus.com>
 */

namespace Validators;

use Validator as V;

/**
 * This class abstracts Laravel's Validator facade. Extend this and declare
 * your errors and messages as static variables to clean up validation in
 * controllers.
 *
 * @author John Arstingstall <jarstingstall@indatus.com>
 */
abstract class Validator
{
    /**
     * Validation errors
     *
     * @var Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Abstracts Laravel's Validator::make()
     *
     * @param array $input Array of input data
     *
     * @return boolean
     */
    public function validate($input)
    {
        $messages = (isset(static::$messages)) ? static::$messages : array();

        $validator = V::make($input, static::$rules, $messages);

        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return false;
        }

        return true;
    }

    /**
     * Getter method for the errors property
     *
     * @return Illuminate\Support\MessageBag
     */
    public function errors()
    {
        return $this->errors;
    }
}
