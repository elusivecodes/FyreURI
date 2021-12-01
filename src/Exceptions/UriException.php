<?php
declare(strict_types=1);

namespace Fyre\Uri\Exceptions;

use
    RunTimeException;

/**
 * UriException
 */
class UriException extends RunTimeException
{

    public static function forInvalidPort(int $port)
    {
        return new static('Invalid Port: '.$port);
    }

    public static function forInvalidUri(string $uri = '')
    {
        return new static('Invalid URI: '.$uri);
    }

}
