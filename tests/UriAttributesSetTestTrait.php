<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Http\Uri;
use InvalidArgumentException;

trait UriAttributesSetTestTrait
{
    public function testSetAuthority(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setAuthority('test.com');

        $this->assertSame(
            '',
            $uri1->getAuthority()
        );

        $this->assertSame(
            'test.com',
            $uri2->getAuthority()
        );
    }

    public function testSetAuthorityWithPort(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setAuthority('test.com:3000');

        $this->assertSame(
            '',
            $uri1->getAuthority()
        );

        $this->assertSame(
            'test.com:3000',
            $uri2->getAuthority()
        );
    }

    public function testSetAuthorityWithUserInfo(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setAuthority('user:password@test.com');

        $this->assertSame(
            '',
            $uri1->getAuthority()
        );

        $this->assertSame(
            'user:password@test.com',
            $uri2->getAuthority()
        );
    }

    public function testSetFragment(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setFragment('test');

        $this->assertSame(
            '',
            $uri1->getFragment()
        );

        $this->assertSame(
            'test',
            $uri2->getFragment()
        );
    }

    public function testSetFragmentWithHash(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setFragment('#test');

        $this->assertSame(
            '',
            $uri1->getFragment()
        );

        $this->assertSame(
            'test',
            $uri2->getFragment()
        );
    }

    public function testSetHost(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setHost('test.com');

        $this->assertSame(
            '',
            $uri1->getHost()
        );

        $this->assertSame(
            'test.com',
            $uri2->getHost()
        );
    }

    public function testSetPath(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setPath('test/deep');

        $this->assertSame(
            '',
            $uri1->getPath()
        );

        $this->assertSame(
            'test/deep',
            $uri2->getPath()
        );
    }

    public function testSetPathWithDots(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setPath('test/../deep');

        $this->assertSame(
            '',
            $uri1->getPath()
        );

        $this->assertSame(
            'deep',
            $uri2->getPath()
        );
    }

    public function testSetPathWithLeadingSlash(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setPath('/test/deep');

        $this->assertSame(
            '',
            $uri1->getPath()
        );

        $this->assertSame(
            '/test/deep',
            $uri2->getPath()
        );
    }

    public function testSetPort(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setPort(3000);

        $this->assertNull(
            $uri1->getPort()
        );

        $this->assertSame(
            3000,
            $uri2->getPort()
        );
    }

    public function testSetPortInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $uri1 = new Uri();
        $uri2 = $uri1->setPort(0);
    }

    public function testSetQuery(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setQuery([
            'test' => 'a'
        ]);

        $this->assertSame(
            [],
            $uri1->getQuery()
        );

        $this->assertSame(
            [
                'test' => 'a'
            ],
            $uri2->getQuery()
        );
    }

    public function testSetQueryString(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setQueryString('test=a');

        $this->assertSame(
            [],
            $uri1->getQuery()
        );

        $this->assertSame(
            [
                'test' => 'a'
            ],
            $uri2->getQuery()
        );
    }

    public function testSetQueryStringWithQuestionMark(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setQueryString('?test=a');

        $this->assertSame(
            [],
            $uri1->getQuery()
        );

        $this->assertSame(
            [
                'test' => 'a'
            ],
            $uri2->getQuery()
        );
    }

    public function testSetScheme(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setScheme('https');

        $this->assertSame(
            '',
            $uri1->getScheme()
        );

        $this->assertSame(
            'https',
            $uri2->getScheme()
        );
    }

    public function testSetUserInfo(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setUserInfo('test');

        $this->assertSame(
            '',
            $uri1->getUserInfo()
        );

        $this->assertSame(
            'test',
            $uri2->getUserInfo()
        );
    }

    public function testSetUserInfoWithPassword(): void
    {
        $uri1 = new Uri();
        $uri2 = $uri1->setUserInfo('test', 'pass');

        $this->assertSame(
            '',
            $uri1->getUserInfo()
        );

        $this->assertSame(
            'test:pass',
            $uri2->getUserInfo()
        );
    }
}
