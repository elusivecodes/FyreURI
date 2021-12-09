<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Uri\Uri;

trait UriRelativeTest
{

    public function testRelativePath(): void
    {
        $uri1 = Uri::create('http://domain.com/path');
        $uri2 = $uri1->resolveRelativeUri('deep');

        $this->assertEquals(
            'http://domain.com/path',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://domain.com/path/deep',
            $uri2->getUri()
        );
    }

    public function testRelativeFullPath(): void
    {
        $uri1 = Uri::create('http://domain.com/path');
        $uri2 = $uri1->resolveRelativeUri('/new');

        $this->assertEquals(
            'http://domain.com/path',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://domain.com/new',
            $uri2->getUri()
        );
    }

    public function testRelativeFullPathWithDots(): void
    {
        $uri1 = Uri::create('http://domain.com/path/deep');
        $uri2 = $uri1->resolveRelativeUri('../new');

        $this->assertEquals(
            'http://domain.com/path/deep',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://domain.com/path/new',
            $uri2->getUri()
        );
    }

    public function testRelativeUri(): void
    {
        $uri1 = Uri::create('http://domain.com/path');
        $uri2 = $uri1->resolveRelativeUri('http://test.com');

        $this->assertEquals(
            'http://domain.com/path',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://test.com/',
            $uri2->getUri()
        );
    }

    public function testRelativeUriWithScheme(): void
    {
        $uri1 = Uri::create('http://domain.com/path');
        $uri2 = $uri1->resolveRelativeUri('https://test.com');

        $this->assertEquals(
            'http://domain.com/path',
            $uri1->getUri()
        );

        $this->assertEquals(
            'https://test.com/',
            $uri2->getUri()
        );
    }

    public function testRelativeUriWithoutScheme(): void
    {
        $uri1 = Uri::create('https://domain.com/path');
        $uri2 = $uri1->resolveRelativeUri('//test.com');

        $this->assertEquals(
            'https://domain.com/path',
            $uri1->getUri()
        );

        $this->assertEquals(
            'test.com/',
            $uri2->getUri()
        );
    }

    public function testRelativeUriWithPort(): void
    {
        $uri1 = Uri::create('http://domain.com/path');
        $uri2 = $uri1->resolveRelativeUri('http://test.com:3000');

        $this->assertEquals(
            'http://domain.com/path',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://test.com:3000/',
            $uri2->getUri()
        );
    }

    public function testRelativeUriWithoutPort(): void
    {
        $uri1 = Uri::create('http://domain.com:3000/path');
        $uri2 = $uri1->resolveRelativeUri('http://test.com');

        $this->assertEquals(
            'http://domain.com:3000/path',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://test.com/',
            $uri2->getUri()
        );
    }

    public function testRelativeUriWithUsername(): void
    {
        $uri1 = Uri::create('http://domain.com/path');
        $uri2 = $uri1->resolveRelativeUri('http://user@test.com');

        $this->assertEquals(
            'http://domain.com/path',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://user@test.com/',
            $uri2->getUri()
        );
    }

    public function testRelativeUriWithoutUsername(): void
    {
        $uri1 = Uri::create('http://user@domain.com/path');
        $uri2 = $uri1->resolveRelativeUri('http://test.com');

        $this->assertEquals(
            'http://user@domain.com/path',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://test.com/',
            $uri2->getUri()
        );
    }

    public function testRelativeUriWithPassword(): void
    {
        $uri1 = Uri::create('http://domain.com/path');
        $uri2 = $uri1->resolveRelativeUri('http://user:password@test.com');

        $this->assertEquals(
            'http://domain.com/path',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://user:password@test.com/',
            $uri2->getUri()
        );
    }

    public function testRelativeUriWithoutPassword(): void
    {
        $uri1 = Uri::create('http://user:password@domain.com/path');
        $uri2 = $uri1->resolveRelativeUri('http://test.com');

        $this->assertEquals(
            'http://user:password@domain.com/path',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://test.com/',
            $uri2->getUri()
        );
    }

    public function testRelativeUriWithQuery(): void
    {
        $uri1 = Uri::create('http://domain.com/path');
        $uri2 = $uri1->resolveRelativeUri('http://test.com/?test=1');

        $this->assertEquals(
            'http://domain.com/path',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://test.com/?test=1',
            $uri2->getUri()
        );
    }

    public function testRelativeUriWithoutQuery(): void
    {
        $uri1 = Uri::create('http://domain.com:3000/path?test=1');
        $uri2 = $uri1->resolveRelativeUri('http://test.com');

        $this->assertEquals(
            'http://domain.com:3000/path?test=1',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://test.com/',
            $uri2->getUri()
        );
    }

    public function testRelativeUriWithFragment(): void
    {
        $uri1 = Uri::create('http://domain.com/path');
        $uri2 = $uri1->resolveRelativeUri('http://test.com/#test');

        $this->assertEquals(
            'http://domain.com/path',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://test.com/#test',
            $uri2->getUri()
        );
    }

    public function testRelativeUriWithoutFragment(): void
    {
        $uri1 = Uri::create('http://domain.com:3000/path#test');
        $uri2 = $uri1->resolveRelativeUri('http://test.com');

        $this->assertEquals(
            'http://domain.com:3000/path#test',
            $uri1->getUri()
        );

        $this->assertEquals(
            'http://test.com/',
            $uri2->getUri()
        );
    }

}
