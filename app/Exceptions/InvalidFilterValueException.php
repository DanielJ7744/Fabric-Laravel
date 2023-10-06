<?php

namespace App\Exceptions;

use Exception;

class InvalidFilterValueException extends Exception
{
    protected $message = 'Invalid value provided for filter field';
}
