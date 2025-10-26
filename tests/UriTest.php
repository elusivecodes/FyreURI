<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Http\Exceptions\UriException;
use Fyre\Http\Uri;
use Fyre\Utility\Traits\MacroTrait;
use PHPUnit\Framework\TestCase;

use function class_uses;

final class UriTest extends TestCase
{
    use UriAttributesGetTestTrait;
    use UriAttributesSetTestTrait;
    use UriQueryTestTrait;
    use UriRelativeTestTrait;

    public function testMacroable(): void
    {
        $this->assertContains(
            MacroTrait::class,
            class_uses(Uri::class)
        );
    }

    public function testUri(): void
    {
        $this->assertEquals(
            'https://domain.com/',
            Uri::createFromString('https://domain.com/')->getUri()
        );
    }

    public function testUriFragment(): void
    {
        $this->assertEquals(
            'https://domain.com/#test',
            Uri::createFromString('https://domain.com/#test')->getUri()
        );
    }

    public function testUriInvalid(): void
    {
        $this->expectException(UriException::class);

        Uri::createFromString('https:///domain.com/');
    }

    public function testUriPassword(): void
    {
        $this->assertEquals(
            'https://user:password@domain.com/',
            Uri::createFromString('https://user:password@domain.com/')->getUri()
        );
    }

    public function testUriPath(): void
    {
        $this->assertEquals(
            'https://domain.com/path/deep',
            Uri::createFromString('https://domain.com/path/deep')->getUri()
        );
    }

    public function testUriPort(): void
    {
        $this->assertEquals(
            'https://domain.com:3000/',
            Uri::createFromString('https://domain.com:3000/')->getUri()
        );
    }

    public function testUriQuery(): void
    {
        $this->assertEquals(
            'https://domain.com/?test=1',
            Uri::createFromString('https://domain.com/?test=1')->getUri()
        );
    }

    public function testUriUsername(): void
    {
        $this->assertEquals(
            'https://user@domain.com/',
            Uri::createFromString('https://user@domain.com/')->getUri()
        );
    }

    public function testUriWithoutHost(): void
    {
        $this->assertEquals(
            '/path/deep',
            Uri::createFromString('/path/deep')->getUri()
        );
    }

    public function testUriWithTrailingSlash(): void
    {
        $this->assertEquals(
            '/path/deep/',
            Uri::createFromString('/path/deep/')->getUri()
        );
    }
}
