<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Http\Uri;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class UriTest extends TestCase
{

    use UriAttributesGetTestTrait;
    use UriAttributesSetTestTrait;
    use UriQueryTestTrait;
    use UriRelativeTestTrait;

    public function testUri(): void
    {
        $this->assertEquals(
            'https://domain.com/',
            Uri::fromString('https://domain.com/')->getUri()
        );
    }

    public function testUriWithPort(): void
    {
        $this->assertEquals(
            'https://domain.com:3000/',
            Uri::fromString('https://domain.com:3000/')->getUri()
        );
    }

    public function testUriWithUsername(): void
    {
        $this->assertEquals(
            'https://user@domain.com/',
            Uri::fromString('https://user@domain.com/')->getUri()
        );
    }

    public function testUriWithPassword(): void
    {
        $this->assertEquals(
            'https://user:password@domain.com/',
            Uri::fromString('https://user:password@domain.com/')->getUri()
        );
    }

    public function testUriWithPath(): void
    {
        $this->assertEquals(
            'https://domain.com/path/deep',
            Uri::fromString('https://domain.com/path/deep')->getUri()
        );
    }

    public function testUriWithQuery(): void
    {
        $this->assertEquals(
            'https://domain.com/?test=1',
            Uri::fromString('https://domain.com/?test=1')->getUri()
        );
    }

    public function testUriWithFragment(): void
    {
        $this->assertEquals(
            'https://domain.com/#test',
            Uri::fromString('https://domain.com/#test')->getUri()
        );
    }

    public function testUriWithoutHost(): void
    {
        $this->assertEquals(
            '/path/deep',
            Uri::fromString('/path/deep')->getUri()
        );
    }

    public function testUriWithTrailingSlash(): void
    {
        $this->assertEquals(
            '/path/deep/',
            Uri::fromString('/path/deep/')->getUri()
        );
    }

    public function testUriInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Uri::fromString('https:///domain.com/');
    }

}
