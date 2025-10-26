<?php
declare(strict_types=1);

namespace Fyre\Http;

use Fyre\Http\Exceptions\UriException;
use Fyre\Utility\Traits\MacroTrait;
use Psr\Http\Message\UriInterface;
use Stringable;

use function array_filter;
use function array_key_exists;
use function array_map;
use function array_pop;
use function count;
use function explode;
use function http_build_query;
use function implode;
use function in_array;
use function is_numeric;
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
class Uri implements Stringable, UriInterface
{
    use MacroTrait;

    protected const DEFAULT_PORTS = [
        'ftp' => 21,
        'sftp' => 22,
        'http' => 80,
        'https' => 443,
    ];

    protected string $fragment = '';

    protected string $host = '';

    protected string $password = '';

    protected string $path = '';

    protected int|null $port = null;

    protected string|null $query = null;

    protected array $queryParams = [];

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
    public static function createFromString(string $uri = ''): static
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
        $this->setUri($uri);
    }

    /**
     * Clone callback.
     */
    public function __clone(): void
    {
        $this->query = null;
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
     * Get the URI query string.
     *
     * @return string The URI query string.
     */
    public function getQuery(): string
    {
        return $this->query ??= http_build_query($this->queryParams);
    }

    /**
     * Get the URI query array.
     *
     * @return array The URI query array.
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
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

        if ($this->queryParams !== []) {
            $uri .= '?'.$this->getQuery();
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
     * Clone the Uri with a resolved relative URI.
     *
     * @param string $uri The URI string.
     * @return Uri A new Uri.
     */
    public function resolveRelativeUri(string $uri): static
    {
        $temp = clone $this;

        return $temp->setUri($uri);
    }

    /**
     * Clone the Uri with a new query parameter.
     *
     * @param string $key The key.
     * @param mixed $value The value.
     * @return Uri A new Uri.
     */
    public function withAddedQuery(string $key, mixed $value = null): static
    {
        $queryParams = $this->queryParams;

        $queryParams[$key] = $value;

        return $this->withQueryParams($queryParams);
    }

    /**
     * Clone the Uri with a new authority.
     *
     * @param string $authority The authority string.
     * @return Uri A new Uri.
     *
     * @throws UriException if the authority is not valid.
     */
    public function withAuthority(string $authority): static
    {
        if ($authority && strpos($authority, '://') === false) {
            $authority = '//'.ltrim($authority, '/');
        }

        $parts = parse_url($authority);

        if (!$parts || !array_key_exists('host', $parts)) {
            throw UriException::forInvalidAuthority($authority);
        }

        $temp = clone $this;

        $user = rawurldecode($parts['user'] ?? '');
        $password = rawurldecode($parts['pass'] ?? '');

        $temp->scheme = static::filterScheme($parts['scheme'] ?? '');
        $temp->user = static::trim($user);
        $temp->password = static::trim($password);
        $temp->host = static::filterHost($parts['host']);
        $temp->port = static::filterPort($parts['port'] ?? null);

        return $temp;
    }

    /**
     * Clone the Uri with a new fragment.
     *
     * @param string $fragment The URI fragment.
     * @return Uri A new Uri.
     */
    public function withFragment(string $fragment = ''): static
    {
        $temp = clone $this;

        $temp->fragment = static::filterFragment($fragment);

        return $temp;
    }

    /**
     * Clone the Uri with a new host.
     *
     * @param string $host The URI host.
     * @return Uri A new Uri.
     */
    public function withHost(string $host = ''): static
    {
        $temp = clone $this;

        $temp->host = static::filterHost($host);

        return $temp;
    }

    /**
     * Clone the Uri with only specific query parameters.
     *
     * @param array $keys The query parameters to keep.
     * @return Uri A new Uri.
     */
    public function withOnlyQuery(array $keys): static
    {
        $params = array_filter(
            $this->queryParams,
            fn(mixed $key): bool => in_array($key, $keys),
            ARRAY_FILTER_USE_KEY
        );

        return $this->withQueryParams($params);
    }

    /**
     * Clone the Uri without query parameters.
     *
     * @param array $keys The query parameters to remove.
     * @return Uri A new Uri.
     */
    public function withoutQuery(array $keys): static
    {
        $params = array_filter(
            $this->queryParams,
            fn(mixed $key): bool => !in_array($key, $keys),
            ARRAY_FILTER_USE_KEY
        );

        return $this->withQueryParams($params);
    }

    /**
     * Clone the Uri with a new path.
     *
     * @param string $path The URI path.
     * @return Uri A new Uri.
     */
    public function withPath(string $path): static
    {
        $temp = clone $this;

        $temp->path = static::filterPath($path);
        $temp->segments = static::filterSegments($temp->path);

        return $temp;
    }

    /**
     * Clone the Uri with a new port.
     *
     * @param int|null $port The URI port.
     * @return Uri A new Uri.
     */
    public function withPort(int|null $port = null): static
    {
        $temp = clone $this;

        $temp->port = static::filterPort($port);

        return $temp;
    }

    /**
     * Clone the Uri with a new query string.
     *
     * @param string $query The URI query string.
     * @return Uri A new Uri.
     */
    public function withQuery(string $query): static
    {
        $temp = clone $this;

        $query = static::trim($query, '?');

        parse_str($query, $temp->queryParams);

        return $temp;
    }

    /**
     * Clone the Uri with new query parameters.
     *
     * @param array $query The query array.
     * @return Uri A new Uri.
     */
    public function withQueryParams(array $query): static
    {
        $query = http_build_query($query);

        return $this->withQuery($query);
    }

    /**
     * Clone the Uri with a new scheme.
     *
     * @param string $scheme The URI scheme.
     * @return Uri A new Uri.
     */
    public function withScheme(string $scheme): static
    {
        $temp = clone $this;

        $temp->scheme = static::filterScheme($scheme);

        return $temp;
    }

    /**
     * Clone the Uri with new user info.
     *
     * @param string $user The user.
     * @param string $password The password.
     * @return Uri A new Uri.
     */
    public function withUserInfo(string $user, string|null $password = null): static
    {
        $temp = clone $this;

        $temp->user = static::trim($user);
        $temp->password = static::trim($password ?? '');

        return $temp;
    }

    /**
     * Set the URI string.
     *
     * @param string $uri The URI string.
     * @return Uri The Uri.
     *
     * @throws UriException if the URI is not valid.
     */
    protected function setUri(string $uri = ''): static
    {
        $parts = parse_url($uri);

        if (!$parts) {
            throw UriException::forInvalidUri($uri);
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
            $this->queryParams = [];
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
            $this->host = static::filterHost($parts['host']);
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
            parse_str($parts['query'], $this->queryParams);
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
     * Filter the host.
     * 
     * @param string $host The host.
     * @return string The filtered host.
     *
     * @throws UriException if the host is not valid.
     */
    protected static function filterHost(string $host): string
    {
        $host = static::trim($host);

        if ($host === '') {
            return $host;
        }

        if (!preg_match('/^(?:[a-z0-9.-]+|\[[a-f0-9:]+\])$/iu', $host)) {
            throw UriException::forInvalidHost($host);
        }

        return $host;

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
     * @throws UriException if the port is not valid.
     */
    protected static function filterPort(int|string|null $port): int|null
    {
        if ($port === null) {
            return null;
        }

        if (!is_numeric($port) || $port <= 0 || $port > 65535) {
            throw UriException::forInvalidPort((string) $port);
        }

        return (int) $port;
    }

    /**
     * Filter the scheme.
     *
     * @param string $scheme The scheme.
     * @return string The filtered scheme.
     *
     * @throws UriException if the scheme is not valid.
     */
    protected static function filterScheme(string $scheme): string
    {
        $scheme = strtolower($scheme);
        $scheme = static::trim($scheme, ':/');

        if ($scheme && !array_key_exists($scheme, static::DEFAULT_PORTS)) {
            throw UriException::forInvalidScheme($scheme);
        }

        return $scheme;
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
        return trim($string, " \n\r\t\v\x00".$extraChars);
    }
}
