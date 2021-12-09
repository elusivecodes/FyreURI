<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Uri\Exceptions\UriException,
    Fyre\Uri\Uri;

trait UriAttributesSetTest
{

    public function testSetAuthority(): void
    {
        $uri = new Uri();

        $this->assertEquals(
            $uri,
            $uri->setAuthority('test.com')
        );

        $this->assertEquals(
            'test.com',
            $uri->getAuthority()
        );
    }

    public function testSetAuthorityWithPort(): void
    {
        $uri = new Uri();

        $this->assertEquals(
            $uri,
            $uri->setAuthority('test.com:3000')
        );

        $this->assertEquals(
            'test.com:3000',
            $uri->getAuthority()
        );
    }

    public function testSetAuthorityWithUserInfo(): void
    {
        $uri = new Uri();

        $this->assertEquals(
            $uri,
            $uri->setAuthority('user:password@test.com')
        );

        $this->assertEquals(
            'user:password@test.com',
            $uri->getAuthority()
        );
    }

    public function testSetFragment(): void
    {
        $uri = new Uri();

        $this->assertEquals(
            $uri,
            $uri->setFragment('test')
        );

        $this->assertEquals(
            'test',
            $uri->getFragment()
        );
    }

    public function testSetFragmentWithHash(): void
    {
        $uri = new Uri();

        $uri->setFragment('#test');

        $this->assertEquals(
            'test',
            $uri->getFragment()
        );
    }

    public function testSetHost(): void
    {
        $uri = new Uri();

        $this->assertEquals(
            $uri,
            $uri->setHost('test.com')
        );

        $this->assertEquals(
            'test.com',
            $uri->getHost()
        );
    }

    public function testSetPath(): void
    {
        $uri = new Uri();

        $this->assertEquals(
            $uri,
            $uri->setPath('test/deep')
        );

        $this->assertEquals(
            'test/deep',
            $uri->getPath()
        );
    }

    public function testSetPathWithLeadingSlash(): void
    {
        $uri = new Uri();

        $uri->setPath('/test/deep');

        $this->assertEquals(
            '/test/deep',
            $uri->getPath()
        );
    }

    public function testSetPathWithDots(): void
    {
        $uri = new Uri();

        $uri->setPath('test/../deep');

        $this->assertEquals(
            'deep',
            $uri->getPath()
        );
    }

    public function testSetPort(): void
    {
        $uri = new Uri();

        $this->assertEquals(
            $uri,
            $uri->setPort(3000)
        );

        $this->assertEquals(
            3000,
            $uri->getPort()
        );
    }

    public function testSetPortInvalid(): void
    {
        $this->expectException(UriException::class);

        $uri = new Uri();

        $uri->setPort(0);
    }

    public function testSetQuery(): void
    {
        $uri = new Uri();

        $this->assertEquals(
            $uri,
            $uri->setQuery([
                'test' => 'a'
            ])
        );

        $this->assertEquals(
            [
                'test' => 'a'
            ],
            $uri->getQuery()
        );
    }

    public function testSetQueryString(): void
    {
        $uri = new Uri();

        $this->assertEquals(
            $uri,
            $uri->setQueryString('test=a')
        );

        $this->assertEquals(
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

        $this->assertEquals(
            [
                'test' => 'a'
            ],
            $uri->getQuery()
        );
    }

    public function testSetScheme(): void
    {
        $uri = new Uri();

        $this->assertEquals(
            $uri,
            $uri->setScheme('https')
        );

        $this->assertEquals(
            'https',
            $uri->getScheme()
        );
    }

    public function testSetUserInfo(): void
    {
        $uri = new Uri();

        $this->assertEquals(
            $uri,
            $uri->setUserInfo('test')
        );

        $this->assertEquals(
            'test',
            $uri->getUserInfo()
        );
    }

    public function testSetUserInfoWithPassword(): void
    {
        $uri = new Uri();

        $this->assertEquals(
            $uri,
            $uri->setUserInfo('test', 'pass')
        );

        $this->assertEquals(
            'test:pass',
            $uri->getUserInfo()
        );
    }

}
