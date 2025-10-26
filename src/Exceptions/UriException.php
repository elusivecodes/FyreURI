<?php
declare(strict_types=1);

namespace Fyre\Http\Exceptions;

use RuntimeException;

/**
 * UriException
 */
class UriException extends RuntimeException
{
    public static function forInvalidAuthority(string $authority): self
    {
        return new self('Invalid authority: '.$authority);
    }

    public static function forInvalidHost(string $host): self
    {
        return new self('Invalid host: '.$host);
    }

    public static function forInvalidPort(string $port): self
    {
        return new self('Invalid port: '.$port);
    }

    public static function forInvalidScheme(string $scheme): self
    {
        return new self('Invalid scheme: '.$scheme);
    }

    public static function forInvalidUri(string $uri): self
    {
        return new self('Invalid URI: '.$uri);
    }
}
