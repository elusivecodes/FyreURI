<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Uri\Uri,
    InvalidArgumentException;

trait UriAttributesSetTest
{

    public function testSetAuthority(): void
    {
        $uri = new Uri();

        $this->assertSame(
            $uri,
            $uri->setAuthority('test.com')
        );

        $this->assertSame(
            'test.com',
            $uri->getAuthority()
        );
    }

    public function testSetAuthorityWithPort(): void
    {
        $uri = new Uri();

        $uri->setAuthority('test.com:3000');

        $this->assertSame(
            'test.com:3000',
            $uri->getAuthority()
        );
    }

    public function testSetAuthorityWithUserInfo(): void
    {
        $uri = new Uri();

        $uri->setAuthority('user:password@test.com');

        $this->assertSame(
            'user:password@test.com',
            $uri->getAuthority()
        );
    }

    public function testSetFragment(): void
    {
        $uri = new Uri();

        $this->assertSame(
            $uri,
            $uri->setFragment('test')
        );

        $this->assertSame(
            'test',
            $uri->getFragment()
        );
    }

    public function testSetFragmentWithHash(): void
    {
        $uri = new Uri();

        $uri->setFragment('#test');

        $this->assertSame(
            'test',
            $uri->getFragment()
        );
    }

    public function testSetHost(): void
    {
        $uri = new Uri();

        $this->assertSame(
            $uri,
            $uri->setHost('test.com')
        );

        $this->assertSame(
            'test.com',
            $uri->getHost()
        );
    }

    public function testSetPath(): void
    {
        $uri = new Uri();

        $this->assertSame(
            $uri,
            $uri->setPath('test/deep')
        );

        $this->assertSame(
            'test/deep',
            $uri->getPath()
        );
    }

    public function testSetPathWithLeadingSlash(): void
    {
        $uri = new Uri();

        $uri->setPath('/test/deep');

        $this->assertSame(
            '/test/deep',
            $uri->getPath()
        );
    }

    public function testSetPathWithDots(): void
    {
        $uri = new Uri();

        $uri->setPath('test/../deep');

        $this->assertSame(
            'deep',
            $uri->getPath()
        );
    }

    public function testSetPort(): void
    {
        $uri = new Uri();

        $this->assertSame(
            $uri,
            $uri->setPort(3000)
        );

        $this->assertSame(
            3000,
            $uri->getPort()
        );
    }

    public function testSetPortInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $uri = new Uri();

        $uri->setPort(0);
    }

    public function testSetQuery(): void
    {
        $uri = new Uri();

        $this->assertSame(
            $uri,
            $uri->setQuery([
                'test' => 'a'
            ])
        );

        $this->assertSame(
            [
                'test' => 'a'
            ],
            $uri->getQuery()
        );
    }

    public function testSetQueryString(): void
    {
        $uri = new Uri();

        $this->assertSame(
            $uri,
            $uri->setQueryString('test=a')
        );

        $this->assertSame(
            [
                'test' => 'a'
            ],
            $uri->getQuery()
        );
    }

    public function testSetQueryStringWithQuestionMark(): void
    {
        $uri = new Uri();

        $uri->setQueryString('?test=a');

        $this->assertSame(
            [
                'test' => 'a'
            ],
            $uri->getQuery()
        );
    }

    public function testSetScheme(): void
    {
        $uri = new Uri();

        $this->assertSame(
            $uri,
            $uri->setScheme('https')
        );

        $this->assertSame(
            'https',
            $uri->getScheme()
        );
    }

    public function testSetUserInfo(): void
    {
        $uri = new Uri();

        $this->assertSame(
            $uri,
            $uri->setUserInfo('test')
        );

        $this->assertSame(
            'test',
            $uri->getUserInfo()
        );
    }

    public function testSetUserInfoWithPassword(): void
    {
        $uri = new Uri();

        $uri->setUserInfo('test', 'pass');

        $this->assertSame(
            'test:pass',
            $uri->getUserInfo()
        );
    }

}
