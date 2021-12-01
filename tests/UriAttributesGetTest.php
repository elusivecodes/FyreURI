<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Uri\Uri;

trait UriAttributesGetTest
{

    public function testUriGetAuthority(): void
    {
        $this->assertEquals(
            'domain.com',
            Uri::create('http://domain.com/')->getAuthority()
        );
    }

    public function testUriGetAuthorityWithPort(): void
    {
        $this->assertEquals(
            'domain.com:3001',
            Uri::create('http://domain.com:3001/')->getAuthority()
        );
    }

    public function testUriGetAuthorityWithUsername(): void
    {
        $this->assertEquals(
            'user@domain.com',
            Uri::create('http://user@domain.com/')->getAuthority()
        );
    }

    public function testUriGetAuthorityWithPassword(): void
    {
        $this->assertEquals(
            'user:password@domain.com',
            Uri::create('http://user:password@domain.com/')->getAuthority()
        );
    }

    public function testUriGetFragment(): void
    {
        $this->assertEquals(
            'test',
            Uri::create('http://domain.com/#test')->getFragment()
        );
    }

    public function testUriGetHost(): void
    {
        $this->assertEquals(
            'domain.com',
            Uri::create('http://domain.com/')->getHost()
        );
    }

    public function testUriGetPath(): void
    {
        $this->assertEquals(
            '/path/deep',
            Uri::create('http://domain.com/path/deep')->getPath()
        );
    }

    public function testUriGetPathEncoded(): void
    {
        $this->assertEquals(
            '/test%20path',
            Uri::create('http://domain.com/test%20path')->getPath()
        );
    }

    public function testUriGetPort(): void
    {
        $this->assertEquals(
            3001,
            Uri::create('http://domain.com:3001/')->getPort()
        );
    }

    public function testUriGetQuery(): void
    {
        $this->assertEquals(
            [
                'param1' => 'a',
                'param2' => 'b'
            ],
            Uri::create('http://domain.com/?param1=a&param2=b')->getQuery()
        );
    }

    public function testUriGetQueryString(): void
    {
        $this->assertEquals(
            'param1=a&param2=b',
            Uri::create('http://domain.com/?param1=a&param2=b')->getQueryString()
        );
    }

    public function testUriGetScheme(): void
    {
        $this->assertEquals(
            'https',
            Uri::create('https://domain.com/')->getScheme()
        );
    }

    public function testUriGetSchemeDefault(): void
    {
        $this->assertEquals(
            'http',
            Uri::create()->getScheme()
        );
    }

    public function testUriGetSegment(): void
    {
        $this->assertEquals(
            'deep',
            Uri::create('https://domain.com/path/deep')->getSegment(2)
        );
    }

    public function testUriGetSegmentDecoded(): void
    {
        $this->assertEquals(
            'test path',
            Uri::create('http://domain.com/test%20path')->getSegment(1)
        );
    }

    public function testUriGetSegmentInvalid(): void
    {
        $this->assertEquals(
            '',
            Uri::create('https://domain.com/path/deep')->getSegment(3)
        );
    }

    public function testUriGetSegments(): void
    {
        $this->assertEquals(
            ['path', 'deep'],
            Uri::create('https://domain.com/path/deep')->getSegments()
        );
    }

    public function testUriGetTotalSegments(): void
    {
        $this->assertEquals(
            2,
            Uri::create('https://domain.com/path/deep')->getTotalSegments()
        );
    }

    public function testUriGetUserInfo(): void
    {
        $this->assertEquals(
            'user',
            Uri::create('http://user@domain.com/')->getUserInfo()
        );
    }

    public function testUriGetUserInfoWithPassword(): void
    {
        $this->assertEquals(
            'user:password',
            Uri::create('http://user:password@domain.com/')->getUserInfo()
        );
    }

    public function testUriGetUserInfoEncoded(): void
    {
        $this->assertEquals(
            'test%20user:test%20password',
            Uri::create('http://test%20user:test%20password@domain.com/')->getUserInfo()
        );
    }

}
