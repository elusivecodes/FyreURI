<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Uri\Exceptions\UriException,
    Fyre\Uri\Uri;

trait UriAttributesSetTest
{

    public function testUriSetAuthority(): void
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

    public function testUriSetAuthorityWithPort(): void
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

    public function testUriSetAuthorityWithUserInfo(): void
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

    public function testUriSetFragment(): void
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

    public function testUriSetFragmentWithHash(): void
    {
        $uri = new Uri();

        $uri->setFragment('#test');

        $this->assertEquals(
            'test',
            $uri->getFragment()
        );
    }

    public function testUriSetHost(): void
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

    public function testUriSetPath(): void
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

    public function testUriSetPathWithLeadingSlash(): void
    {
        $uri = new Uri();

        $uri->setPath('/test/deep');

        $this->assertEquals(
            '/test/deep',
            $uri->getPath()
        );
    }

    public function testUriSetPathWithDots(): void
    {
        $uri = new Uri();

        $uri->setPath('test/../deep');

        $this->assertEquals(
            'deep',
            $uri->getPath()
        );
    }

    public function testUriSetPort(): void
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

    public function testUriSetPortInvalid(): void
    {
        $this->expectException(UriException::class);

        $uri = new Uri();

        $uri->setPort(0);
    }

    public function testUriSetQuery(): void
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

    public function testUriSetQueryString(): void
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

    public function testUriSetQueryStringWithQuestionMark(): void
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

    public function testUriSetScheme(): void
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

    public function testUriSetUserInfo(): void
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

    public function testUriSetUserInfoWithPassword(): void
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
