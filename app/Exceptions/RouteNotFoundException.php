<?php

namespace App\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    protected $message = 'Not Found';
    protected $code = 404;
}
