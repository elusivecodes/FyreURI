<?php
declare(strict_types=1);

namespace Fyre\Http;

use InvalidArgumentException;

use function array_filter;
use function array_key_exists;
use function array_map;
use function array_pop;
use function count;
use function explode;
use function http_build_query;
use function implode;
use function in_array;
use function ltrim;
use function parse_str;
use function parse_url;
use function rawurldecode;
use function rawurlencode;
use function rtrim;
use function str_starts_with;
use function strpos;
use function strtolower;
use function substr;
use function trim;

use const ARRAY_FILTER_USE_KEY;

/**
 * Uri
 */
class Uri
{
    protected const DEFAULT_PORTS = [
        'ftp' => 21,
        'sftp' => 22,
        'http' => 80,
        'https' => 443,
    ];

    protected const WHITESPACE = " \n\r\t\v\x00";

    protected string $fragment = '';

    protected string $host = '';

    protected string $password = '';

    protected string $path = '';

    protected int|null $port = null;

    protected array $query = [];

    protected string|null $queryString = null;

    protected string $scheme = '';

    protected array $segments = [];

    protected bool $showPassword = true;

    protected string|null $uriString = null;

    protected string $user = '';

    /**
     * Create a new Uri.
     *
     * @param string $uri The URI string.
     * @return Uri A new Uri.
     */
    public static function fromString(string $uri = ''): static
    {
        return new static($uri);
    }

    /**
     * New Uri constructor.
     *
     * @param string $uri The URI string.
     */
    public function __construct(string $uri = '')
    {
        $this->parseUri($uri);
    }

    /**
     * Clone callback.
     */
    public function __clone(): void
    {
        $this->queryString = null;
        $this->uriString = null;
    }

    /**
     * Get the URI string.
     *
     * @return string The URI string.
     */
    public function __toString(): string
    {
        return $this->getUri();
    }

    /**
     * Add a query parameter.
     *
     * @param string $key The key.
     * @param mixed $value The value.
     * @return Uri A new Uri.
     */
    public function addQuery(string $key, mixed $value = null): static
    {
        $query = $this->query;

        $query[$key] = $value;

        return $this->setQuery($query);
    }

    /**
     * Remove query parameters.
     *
     * @param array $keys The query parameters to remove.
     * @return Uri A new Uri.
     */
    public function exceptQuery(array $keys): static
    {
        $query = array_filter(
            $this->query,
            fn(mixed $key): bool => !in_array($key, $keys),
            ARRAY_FILTER_USE_KEY
        );

        return $this->setQuery($query);
    }

    /**
     * Get the URI authority string.
     *
     * @return string The URI authority string.
     */
    public function getAuthority(): string
    {
        if (!$this->host) {
            return '';
        }

        $authority = $this->getUserInfo();

        if ($authority) {
            $authority .= '@';
        }

        $authority .= $this->host;

        if ($this->port && (!array_key_exists($this->scheme, static::DEFAULT_PORTS) || $this->port !== static::DEFAULT_PORTS[$this->scheme])) {
            $authority .= ':'.$this->port;
        }

        return $authority;
    }

    /**
     * Get the URI fragment.
     *
     * @return string The URI fragment.
     */
    public function getFragment(): string
    {
        return rawurlencode($this->fragment);
    }

    /**
     * Get the URI host.
     *
     * @return string The URI host.
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Get the URI path.
     *
     * @return string The URI path.
     */
    public function getPath(): string
    {
        $segments = explode('/', $this->path);
        $segments = array_map(fn(string $segment): string => rawurlencode($segment), $segments);

        return implode('/', $segments);
    }

    /**
     * Get the URI port.
     *
     * @return int|null The URI port.
     */
    public function getPort(): int|null
    {
        return $this->port;
    }

    /**
     * Get the URI query array.
     *
     * @return array The URI query array.
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Get the URI query string.
     *
     * @return string The URI query string.
     */
    public function getQueryString(): string
    {
        return $this->queryString ??= http_build_query($this->query);
    }

    /**
     * Get the URI scheme.
     *
     * @return string The URI scheme.
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Get a specified URI segment.
     *
     * @param int $segment The URI segment index.
     * @return string The URI segment.
     */
    public function getSegment(int $segment): string
    {
        return $this->segments[$segment - 1] ?? '';
    }

    /**
     * Get the URI segments.
     *
     * @return array The URI segments.
     */
    public function getSegments(): array
    {
        return $this->segments;
    }

    /**
     * Get the URI segments count.
     *
     * @return int The URI segments count.
     */
    public function getTotalSegments(): int
    {
        return count($this->segments);
    }

    /**
     * Get the URI string.
     *
     * @return string The URI.
     */
    public function getUri(): string
    {
        if ($this->uriString !== null) {
            return $this->uriString;
        }

        $authority = $this->getAuthority();
        $path = $this->getPath();

        $uri = '';

        if ($authority) {
            if ($this->scheme) {
                $uri .= $this->scheme.'://';
            }

            $uri .= $authority.'/';
        }

        if ($path && $uri) {
            $uri = rtrim($uri, '/').'/'.ltrim($path, '/');
        } else {
            $uri .= $path;
        }

        if ($this->query !== []) {
            $uri .= '?'.$this->getQueryString();
        }

        if ($this->fragment) {
            $uri .= '#'.$this->getFragment();
        }

        return $this->uriString = $uri;
    }

    /**
     * Get the user info string.
     *
     * @return string The user info.
     */
    public function getUserInfo(): string
    {
        $info = rawurlencode($this->user);

        if ($this->showPassword === true && $this->password) {
            $info .= ':'.rawurlencode($this->password);
        }

        return $info;
    }

    /**
     * Filter query parameters.
     *
     * @param array $keys The query parameters to keep.
     * @return Uri A new Uri.
     */
    public function onlyQuery(array $keys): static
    {
        $query = array_filter(
            $this->query,
            fn(mixed $key): bool => in_array($key, $keys),
            ARRAY_FILTER_USE_KEY
        );

        return $this->setQuery($query);
    }

    /**
     * Resolve a relative URI.
     *
     * @param string $uri The URI string.
     * @return Uri A new Uri.
     */
    public function resolveRelativeUri(string $uri): static
    {
        $temp = clone $this;

        return $temp->parseUri($uri);
    }

    /**
     * Set the URI authority string.
     *
     * @param string $authority The authority string.
     * @return Uri A new Uri.
     *
     * @throws InvalidArgumentException if the authority is not valid.
     */
    public function setAuthority(string $authority): static
    {
        if ($authority && strpos($authority, '://') === false) {
            $authority = '//'.ltrim($authority, '/');
        }

        $parts = parse_url($authority);

        if (!$parts || !array_key_exists('host', $parts)) {
            throw new InvalidArgumentException('Invalid authority: '.$authority);
        }

        $temp = clone $this;

        $user = rawurldecode($parts['user'] ?? '');
        $password = rawurldecode($parts['pass'] ?? '');

        $temp->scheme = static::filterScheme($parts['scheme'] ?? '');
        $temp->user = static::trim($user);
        $temp->password = static::trim($password);
        $temp->host = static::trim($parts['host']);
        $temp->port = static::filterPort($parts['port'] ?? null);

        return $temp;
    }

    /**
     * Set the URI fragment.
     *
     * @param string $fragment The URI fragment.
     * @return Uri A new Uri.
     */
    public function setFragment(string $fragment = ''): static
    {
        $temp = clone $this;

        $temp->fragment = static::filterFragment($fragment);

        return $temp;
    }

    /**
     * Set the URI host.
     *
     * @param string $host The URI host.
     * @return Uri A new Uri.
     */
    public function setHost(string $host = ''): static
    {
        $temp = clone $this;

        $temp->host = static::trim($host);

        return $temp;
    }

    /**
     * Set the URI path.
     *
     * @param string $path The URI path.
     * @return Uri A new Uri.
     */
    public function setPath(string $path): static
    {
        $temp = clone $this;

        $temp->path = static::filterPath($path);
        $temp->segments = static::filterSegments($temp->path);

        return $temp;
    }

    /**
     * Set the URI port.
     *
     * @param int|null $port The URI port.
     * @return Uri A new Uri.
     */
    public function setPort(int|null $port = null): static
    {
        $temp = clone $this;

        $temp->port = static::filterPort($port);

        return $temp;
    }

    /**
     * Set the query array.
     *
     * @param array $query The query array.
     * @return Uri A new Uri.
     */
    public function setQuery(array $query): static
    {
        $query = http_build_query($query);

        return $this->setQueryString($query);
    }

    /**
     * Set the URI query string.
     *
     * @param string $query The URI query string.
     * @return Uri A new Uri.
     */
    public function setQueryString(string $query): static
    {
        $temp = clone $this;

        $query = static::trim($query, '?');

        parse_str($query, $temp->query);

        return $temp;
    }

    /**
     * Set the URI scheme.
     *
     * @param string $scheme The URI scheme.
     * @return Uri A new Uri.
     */
    public function setScheme(string $scheme): static
    {
        $temp = clone $this;

        $temp->scheme = static::filterScheme($scheme);

        return $temp;
    }

    /**
     * Set the user info.
     *
     * @param string $user The user.
     * @param string $password The password.
     * @return Uri A new Uri.
     */
    public function setUserInfo(string $user, string $password = ''): static
    {
        $temp = clone $this;

        $temp->user = static::trim($user);
        $temp->password = static::trim($password);

        return $temp;
    }

    /**
     * Set the URI string.
     *
     * @param string $uri The URI string.
     * @return Uri The Uri.
     *
     * @throws InvalidArgumentException if the URI is not valid.
     */
    protected function parseUri(string $uri = ''): static
    {
        $parts = parse_url($uri);

        if (!$parts) {
            throw new InvalidArgumentException('Invalid URI: '.$uri);
        }

        if (array_key_exists('host', $parts)) {
            $this->scheme = '';
            $this->port = null;
            $this->user = '';
            $this->password = '';
            $this->path = '';
            $this->segments = [];
        }

        if (array_key_exists('host', $parts) || array_key_exists('path', $parts)) {
            $this->query = [];
            $this->fragment = '';
        }

        if (array_key_exists('scheme', $parts)) {
            $this->scheme = static::filterScheme($parts['scheme']);
        }

        if (array_key_exists('user', $parts)) {
            $user = rawurldecode($parts['user']);
            $password = rawurldecode($parts['pass'] ?? '');

            $this->user = static::trim($user);
            $this->password = static::trim($password);
        }

        if (array_key_exists('host', $parts)) {
            $this->host = static::trim($parts['host']);
        }

        if (array_key_exists('port', $parts)) {
            $this->port = static::filterPort($parts['port']);
        }

        if (array_key_exists('path', $parts) && $parts['path']) {
            $path = rawurldecode($parts['path']);

            if (!str_starts_with($path, '/')) {
                $path = rtrim($this->path, '/').'/'.$path;
            }

            $this->path = static::filterPath($path);
            $this->segments = static::filterSegments($this->path);
        }

        if (array_key_exists('query', $parts)) {
            parse_str($parts['query'], $this->query);
        }

        if (array_key_exists('fragment', $parts)) {
            $fragment = rawurldecode($parts['fragment']);

            $this->fragment = static::filterFragment($fragment);
        }

        return $this;
    }

    /**
     * Filter the fragment.
     *
     * @param string $fragment The fragment.
     * @return string The filtered fragment.
     */
    protected static function filterFragment(string $fragment): string
    {
        return static::trim($fragment, '#');
    }

    /**
     * Filter the path, and remove dot segments.
     *
     * @param string $path The path.
     * @return string The filtered path.
     */
    protected static function filterPath(string $path): string
    {
        if ($path === '' || $path === '/') {
            return $path;
        }

        $newSegments = [];

        $segments = explode('/', $path);

        foreach ($segments as $segment) {
            if ($segment === '' || $segment === '.') {
                continue;
            }

            if ($segment === '..') {
                array_pop($newSegments);
            } else {
                $newSegments[] = rawurldecode($segment);
            }
        }

        $newPath = implode('/', $newSegments);
        $newPath = trim($newPath, '/ ');

        if (str_starts_with($path, '/')) {
            $newPath = '/'.$newPath;
        }

        if (substr($path, -1, 1) === '/') {
            $newPath = rtrim($newPath).'/';
        }

        return $newPath;
    }

    /**
     * Filter the port.
     *
     * @param int|string|null $port The port.
     * @return int|null The filtered port.
     *
     * @throws InvalidArgumentException if the port is not valid.
     */
    protected static function filterPort(int|string|null $port): int|null
    {
        if ($port === null) {
            return null;
        }

        if ($port <= 0 || $port > 65535) {
            throw new InvalidArgumentException('Invalid Port: '.$port);
        }

        return (int) $port;
    }

    /**
     * Filter the scheme.
     *
     * @param string $scheme The scheme.
     * @return string The filtered scheme.
     */
    protected static function filterScheme(string $scheme): string
    {
        $scheme = strtolower($scheme);

        return static::trim($scheme, ':/');
    }

    /**
     * Filter the path segments.
     *
     * @param string $path The path.
     * @return array The segments.
     */
    protected static function filterSegments(string $path): array
    {
        $path = static::trim($path, '/');

        return explode('/', $path);
    }

    /**
     * Trim a string.
     *
     * @param string $string The input string.
     * @param string $extraChars Extra characters to trim.
     * @return string The trimmed string.
     */
    protected static function trim(string $string, string $extraChars = ''): string
    {
        return trim($string, static::WHITESPACE.$extraChars);
    }
}
