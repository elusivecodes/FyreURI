# FyreURI

**FyreURI** is a free, open-source immutable URI library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [Methods](#methods)



## Installation

**Using Composer**

```
composer require fyre/uri
```

In PHP:

```php
use Fyre\Http\Uri;
```

## Basic Usage

- `$uriString` is a string representing the uri.

```php
$uri = new Uri($uriString);
```

Alternatively, you can use the `createFromString` method for easier chaining.

```php
$uri = Uri::createFromString($uriString);
```


## Methods

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

Get the URI query string.

```php
$query = $uri->getQuery();
```

**Get Query Params**

Get the URI query params.

```php
$params = $uri->getQueryParams();
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
$segments = $uri->getSegments();
```

**Get Total Segments**

Get the URI segments count.

```php
$segmentCount = $uri->getTotalSegments();
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

**Resolve Relative Uri**

Clone the *Uri* with a resolved relative URI.

- `$relativeUri` is a string representing the relative uri.

```php
$newUri = $uri->resolveRelativeUri($relativeUri);
```

**With Added Query**

Clone the *Uri* with a new query parameter.

- `$key` is a string representing the query key.
- `$value` is the query value.

```php
$newUri = $uri->withAddedQuery($key, $value);
```

**With Authority**

Clone the *Uri* with a new authority.

- `$authority` is a string representing the authority.

```php
$newUri = $uri->withAuthority($authority);
```

**With Fragment**

Clone the *Uri* with a new fragment.

- `$fragment` is a string representing the fragment.

```php
$newUri = $uri->withFragment($fragment);
```

**With Host**

Clone the *Uri* with a new host.

- `$host` is a string representing the host.

```php
$newUri = $uri->withHost($host);
```

**With Only Query**

Clone the *Uri* with only specific query parameters.

- `$keys` is an array containing the query parameters to keep.

```php
$newUri = $uri->withOnlyQuery($keys);
```

**Without Query**

Clone the *Uri* without query parameters.

- `$keys` is an array containing the query parameters to remove.

```php
$newUri = $uri->withoutQuery($keys);
```

**With Path**

Clone the *Uri* with a new path.

- `$path` is a string representing the path.

```php
$newUri = $uri->withPath($path);
```

**With Port**

Clone the *Uri* with a new port.

- `$port` is a number representing the port.

```php
$newUri = $uri->withPort($port);
```

**With Query**

Clone the *Uri* with a new query string.

- `$query` is a string representing the query parameters.

```php
$newUri = $uri->withQuery($query);
```

**With Query Params**

Clone the *Uri* with new query parameters.

- `$params` is an array containing the query parameters.

```php
$newUri = $uri->withQueryParams($params);
```

**With Scheme**

Clone the *Uri* with a new scheme.

- `$scheme` is a string representing the scheme.

```php
$newUri = $uri->withScheme($scheme);
```

**With User Info**

Clone the *Uri* with new user info.

- `$username` is a string representing the username.
- `$password` is a string representing the password, and will default to *null*.

```php
$newUri = $uri->withUserInfo($username, $password);
```