<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Uri\Exceptions\UriException,
    Fyre\Uri\Uri,
    PHPUnit\Framework\TestCase;

final class UriTest extends TestCase
{

    use
        UriAttributesGetTest,
        UriAttributesSetTest,
        UriQueryTest,
        UriRelativeTest;

    public function testUri(): void
    {
        $this->assertEquals(
            'https://domain.com/',
            Uri::create('https://domain.com/')->getUri()
        );
    }

    public function testUriWithPort(): void
    {
        $this->assertEquals(
            'https://domain.com:3000/',
            Uri::create('https://domain.com:3000/')->getUri()
        );
    }

    public function testUriWithUsername(): void
    {
        $this->assertEquals(
            'https://user@domain.com/',
            Uri::create('https://user@domain.com/')->getUri()
        );
    }

    public function testUriWithPassword(): void
    {
        $this->assertEquals(
            'https://user:password@domain.com/',
            Uri::create('https://user:password@domain.com/')->getUri()
        );
    }

    public function testUriWithPath(): void
    {
        $this->assertEquals(
            'https://domain.com/path/deep',
            Uri::create('https://domain.com/path/deep')->getUri()
        );
    }

    public function testUriWithQuery(): void
    {
        $this->assertEquals(
            'https://domain.com/?test=1',
            Uri::create('https://domain.com/?test=1')->getUri()
        );
    }

    public function testUriWithFragment(): void
    {
        $this->assertEquals(
            'https://domain.com/#test',
            Uri::create('https://domain.com/#test')->getUri()
        );
    }

    public function testUriWithoutHost(): void
    {
        $this->assertEquals(
            '/path/deep',
            Uri::create('/path/deep')->getUri()
        );
    }

    public function testUriWithTrailingSlash(): void
    {
        $this->assertEquals(
            '/path/deep/',
            Uri::create('/path/deep/')->getUri()
        );
    }

    public function testUriInvalid(): void
    {
        $this->expectException(UriException::class);

        Uri::create('https:///domain.com/');
    }

}
