# FyreUri

**FyreUri** is a free, URI library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Uri Creation](#uri-creation)
- [Methods](#methods)



## Installation

**Using Composer**

```
composer require fyre/uri
```

In PHP:

```php
use Fyre\URI\Uri;
```

## Uri Creation

- `$uriString` is a string representing the uri.

```php
$uri = new Uri($uriString);
```

Alternatively, you can use the `create` method for easier chaining.

```php
$uri = Uri::create($uriString);
```


## Methods

**Add Query**

Add a query parameter.

- `$key` is a string representing the query key.
- `$value` is the query value.

```php
$uri->addQuery($key, $value);
```

**Except Query**

Remove query parameters.

- `$keys` is an array containing the query parameters to remove.

```php
$uri->exceptQuery($keys);
```

**Get Authority**

Get the URI authority string.

```php
$authority = $uri->getAuthority();
```

**Get Fragment**

Get the URI fragment.

```php
$fragment = $uri->getFragment();
```

**Get Host**

Get the URI host.

```php
$host = $uri->getHost();
```

**Get Path**

Get the URI path.

```php
$path = $uri->getPath();
```

**Get Port**

Get the URI port.

```php
$port = $uri->getPort();
```

**Get Query**

Get the URI query array.

```php
$query = $uri->getQuery();
```

**Get Query String**

Get the URI query string.

```php
$query = $uri->getQueryString();
```

**Get Scheme**

Get the URI scheme.

```php
$scheme = $uri->getScheme();
```

**Get Segment**

Get a specified URI segment.

- `$segment` is a number indicating the segment index.

```php
$part = $uri->getSegment($segment);
```

**Get Segments**

Get the URI segments.

```php
$parts = $uri->getSegments();
```

**Get Total Segments**

Get the URI segments count.

```php
$segments = $uri->getTotalSegments();
```

**Get Uri**

Get the URI string.

```php
$uriString = $uri->getUri();
```

**Get User Info**

Get the user info string.

```php
$userInfo = $uri->getUserInfo();
```

**Only Query**

Filter query parameters.

- `$keys` is an array containing the query parameters to keep.

```php
$uri->onlyQuery($keys);
```

**Resolve Relative Uri**

Resolve a relative URI.

- `$uriString` is a string representing the uri.

```php
$uri->resolveRelativeUri($uriString);
```

**Set Authority**

Set the URI authority string.

- `$authority` is a string representing the authority.

```php
$uri->setAuthority($authority);
```

**Set Fragment**

Set the URI fragment.

- `$fragment` is a string representing the fragment.

```php
$uri->setFragment($fragment);
```

**Set Host**

Set the URI host.

- `$host` is a string representing the host.

```php
$uri->setHost($host);
```

**Set Path**

Set the URI path.

- `$path` is a string representing the path.

```php
$uri->setPath($path);
```

**Set Port**

Get the URI port.

- `$port` is a number representing the port.

```php
$uri->setPort($port);
```

**Set Query**

Get the URI query array.

- `$query` is an array containing the query parameters.

```php
$uri->setQuery($query);
```

**Set Query String**

Get the URI query string.

- `$query` is a string representing the query parameters.

```php
$uri->setQueryString($query);
```

**Set Scheme**

Get the URI scheme.

- `$scheme` is a string representing the scheme.

```php
$uri->setScheme($scheme);
```

**Set User Info**

Get the user info string.

- `$username` is a string representing the username.
- `$password` is a string representing the password, and will default to *""*.

```php
$uri->setUserInfo($username, $password);
```