<?php
declare(strict_types=1);

namespace Fyre\Uri;

use
    InvalidArgumentException;

use const
    ARRAY_FILTER_USE_KEY;

use function
    array_filter,
    array_key_exists,
    array_map,
    array_pop,
    count,
    explode,
    http_build_query,
    implode,
    in_array,
    ltrim,
    parse_str,
    parse_url,
    rawurldecode,
    rawurlencode,
    rtrim,
    str_starts_with,
    strpos,
    strtolower,
    substr,
    trim;

/**
 * Uri
 */
class Uri
{

    protected const DEFAULT_PORTS = [
        'ftp' => 21,
        'sftp' => 22,
        'http' => 80,
        'https' => 443
    ];

    protected string $scheme = 'http';
    protected string $user = '';
    protected string $password = '';
    protected string $host = '';
    protected int|null $port = null;
    protected string $path = '';
    protected array $segments = [];
    protected array $query = [];
    protected string $fragment = '';

    protected bool $showPassword = true;

    /**
     * Create a new Uri.
     * @param string $uri The URI string.
     * 
     */
    public static function create(string $uri = ''): static
    {
        return new static($uri);
    }

    /**
     * New Uri constructor.
     * @param string $uri The URI string.
     */
    public function __construct(string $uri = '')
    {
        $this->parseUri($uri);
    }

    /**
     * Get the URI string.
     * @return string The URI string.
     */
    public function __toString(): string
    {
        return $this->getUri();
    }

    /**
     * Add a query parameter.
     * @param string $key The key.
     * @param mixed $value The value.
     * @return URI The URI.
     */
    public function addQuery(string $key, $value = null): static
    {
        $this->query[$key] = $value;

        return $this;
    }

    /**
     * Remove query parameters.
     * @param array $keys The query parameters to remove.
     * @return URI The URI.
     */
    public function exceptQuery(array $keys): static
    {
        $this->query = array_filter(
            $this->query,
            fn($key) => !in_array($key, $keys),
            ARRAY_FILTER_USE_KEY
        );

        return $this;
    }

    /**
     * Get the URI authority string.
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
     * @return string The URI fragment.
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * Get the URI host.
     * @return string The URI host.
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Get the URI path.
     * @return string The URI path.
     */
    public function getPath(): string
    {
        $segments = explode('/', $this->path);
        $segments = array_map(fn($segment) => rawurlencode($segment), $segments);

        return implode('/', $segments);
    }

    /**
     * Get the URI port.
     * @return int|null The URI port.
     */
    public function getPort(): int|null
    {
        return $this->port;
    }

    /**
     * Get the URI query array.
     * @return array The URI query array.
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Get the URI query string.
     * @return string The URI query string.
     */
    public function getQueryString(): string
    {
        return http_build_query($this->query);
    }

    /**
     * Get the URI scheme.
     * @return string The URI scheme.
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Get a specified URI segment.
     * @param int $segment The URI segment index.
     * @return string The URI segment.
     */
    public function getSegment(int $segment): string
    {
        return $this->segments[$segment - 1] ?? '';
    }

    /**
     * Get the URI segments.
     * @return array The URI segments.
     */
    public function getSegments(): array
    {
        return $this->segments;
    }

    /**
     * Get the URI segments count.
     * @return int The URI segments count.
     */
    public function getTotalSegments(): int
    {
        return count($this->segments);
    }

    /**
     * Get the URI string.
     * @return string The URI.
     */
    public function getUri(): string
    {
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
            $uri .= '#'.$this->fragment;
        }

        return $uri;
    }

    /**
     * Get the user info string.
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
     * @param array $keys The query parameters to keep.
     * @return URI The URI.
     */
    public function onlyQuery(array $keys): static
    {
        $this->query = array_filter(
            $this->query,
            fn($key) => in_array($key, $keys),
            ARRAY_FILTER_USE_KEY
        );

        return $this;
    }

    /**
     * Set the URI string.
     * @param string $uri The URI string.
     * @return URI The URI.
     * @throws InvalidArgumentException if the URI is invalid.
     */
    public function parseUri(string $uri = ''): static
    {
        if (!$uri) {
            return $this;
        }

        $parts = parse_url($uri);

        if (!$parts) {
            throw new InvalidArgumentException('Invalid URI: '.$uri);
        }

        $path = rawurldecode($parts['path'] ?? '');

        if (array_key_exists('host', $parts)) {
            $this->setScheme($parts['scheme'] ?? '');

            $user = rawurldecode($parts['user'] ?? '');
            $password = rawurldecode($parts['pass'] ?? '');
            $this->setUserInfo($user, $password);

            $this->setHost($parts['host']);

            if (array_key_exists('port', $parts)) {
                $this->setPort((int) $parts['port']);
            } else {
                $this->setPort(null);
            }

            $this->setPath($path);
        } else if (str_starts_with($path, '/')) {
            $this->setPath($path);
        } else if ($path) {
            $newPath = rtrim($this->path, '/').'/'.$path;
            $this->setPath($newPath);
        }

        $this->setQueryString($parts['query'] ?? '');
        $this->setFragment($parts['fragment'] ?? '');

        return $this;
    }

    /**
     * Resolve a relative URI.
     * @param string $uri The URI string.
     * @return URI The URI.
     */
    public function resolveRelativeUri(string $uri): static
    {
        $temp = clone $this;

        return $temp->parseUri($uri);
    }

    /**
     * Set the URI authority string.
     * @param string $authority The authority string.
     * @return URI The URI.
     */
    public function setAuthority(string $authority)
    {
        if ($authority && strpos($authority, '://') === false) {
            $authority = '//'.ltrim($authority, '/');
        }

        $parts = parse_url($authority);

        if ($parts && array_key_exists('host', $parts)) {
            if (array_key_exists('scheme', $parts)) {
                $this->setScheme($parts['scheme']);
            }

            $user = rawurldecode($parts['user'] ?? '');
            $password = rawurldecode($parts['pass'] ?? '');
            $this->setUserInfo($user, $password);

            $this->setHost($parts['host']);

            if (array_key_exists('port', $parts)) {
                $this->setPort((int) $parts['port']);
            } else {
                $this->setPort(null);
            }
        }

        return $this;
    }

    /**
     * Set the URI fragment.
     * @param string $fragment The URI fragment.
     * @return URI The URI.
     */
    public function setFragment(string $fragment = ''): static
    {
        $this->fragment = trim($fragment, '# ');

        return $this;
    }

    /**
     * Set the URI host.
     * @param string $host The URI host.
     * @return URI The URI.
     */
    public function setHost(string $host = ''): static
    {
        $this->host = trim($host);

        return $this;
    }

    /**
     * Set the URI path.
     * @param string $path The URI path.
     * @return URI The URI.
     */
    public function setPath(string $path)
    {
        $this->path = static::removeDotSegments($path);

        $path = trim($this->path, '/');
        $this->segments = explode('/', $path);

        return $this;
    }

    /**
     * Set the URI port.
     * @param int|null $port The URI port.
     * @return URI The URI.
     * @throws InvalidArgumentException if the port is invalid.
     */
    public function setPort(int|null $port = null): static
    {
        if ($port !== null && ($port <= 0 || $port > 65535)) {
            throw new InvalidArgumentException('Invalid Port: '.$port);
        }

        $this->port = $port;

        return $this;
    }

    /**
     * Set the query array.
     * @param array $query The query array.
     * @return URI The URI.
     */
    public function setQuery(array $query): static
    {
        $query = http_build_query($query);

        return $this->setQueryString($query);
    }

    /**
     * Set the URI query string.
     * @param string $query The URI query string.
     * @return URI The URI.
     */
    public function setQueryString(string $query): static
    {
        $query = trim($query, '? ');

        parse_str($query, $this->query);

        return $this;
    }

    /**
     * Set the URI scheme.
     * @param string $scheme The URI scheme.
     * @return URI The URI.
     */
    public function setScheme(string $scheme): static
    {
        $scheme = strtolower($scheme);
        $this->scheme = trim($scheme, ':/ ');

        return $this;
    }

    /**
     * Set the user info.
     * @param string $user The user.
     * @param string $password The password.
     * @return URI The URI.
     */
    public function setUserInfo(string $user, string $password = ''): static
    {
        $this->user = trim($user);
        $this->password = trim($password);

        return $this;
    }

    /**
     * Remove dot segments from a path.
     * @param string $path The path.
     * @return string The path with dot segments removed.
     */
    protected static function removeDotSegments(string $path): string
    {
        if ($path === '' || $path === '/') {
            return $path;
        }

        $newSegments = [];

        $segments = explode('/', $path);

        foreach ($segments AS $segment) {
            if (!$segment || $segment === '.') {
                continue;
            }

            if ($segment === '..') {
                array_pop($newSegments);
            } else {
                $newSegments[] = $segment;
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

}
