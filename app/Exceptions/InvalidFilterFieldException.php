<?php

namespace App\Exceptions;

use Exception;

class InvalidFilterFieldException extends Exception
{
    protected $message = 'Invalid filter field provided for service';
}
