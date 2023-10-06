<?php

namespace App\Exceptions;

use Exception;

class InvalidFilterOperatorException extends Exception
{
    protected $message = 'Invalid operator provided for filter field';
}
