<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Uri\Uri;

trait UriAttributesGetTest
{

    public function testGetAuthority(): void
    {
        $this->assertEquals(
            'domain.com',
            Uri::create('http://domain.com/')->getAuthority()
        );
    }

    public function testGetAuthorityWithPort(): void
    {
        $this->assertEquals(
            'domain.com:3001',
            Uri::create('http://domain.com:3001/')->getAuthority()
        );
    }

    public function testGetAuthorityWithUsername(): void
    {
        $this->assertEquals(
            'user@domain.com',
            Uri::create('http://user@domain.com/')->getAuthority()
        );
    }

    public function testGetAuthorityWithPassword(): void
    {
        $this->assertEquals(
            'user:password@domain.com',
            Uri::create('http://user:password@domain.com/')->getAuthority()
        );
    }

    public function testGetFragment(): void
    {
        $this->assertEquals(
            'test',
            Uri::create('http://domain.com/#test')->getFragment()
        );
    }

    public function testGetHost(): void
    {
        $this->assertEquals(
            'domain.com',
            Uri::create('http://domain.com/')->getHost()
        );
    }

    public function testGetPath(): void
    {
        $this->assertEquals(
            '/path/deep',
            Uri::create('http://domain.com/path/deep')->getPath()
        );
    }

    public function testGetPathEncoded(): void
    {
        $this->assertEquals(
            '/test%20path',
            Uri::create('http://domain.com/test%20path')->getPath()
        );
    }

    public function testGetPort(): void
    {
        $this->assertEquals(
            3001,
            Uri::create('http://domain.com:3001/')->getPort()
        );
    }

    public function testGetQuery(): void
    {
        $this->assertEquals(
            [
                'param1' => 'a',
                'param2' => 'b'
            ],
            Uri::create('http://domain.com/?param1=a&param2=b')->getQuery()
        );
    }

    public function testGetQueryString(): void
    {
        $this->assertEquals(
            'param1=a&param2=b',
            Uri::create('http://domain.com/?param1=a&param2=b')->getQueryString()
        );
    }

    public function testGetScheme(): void
    {
        $this->assertEquals(
            'https',
            Uri::create('https://domain.com/')->getScheme()
        );
    }

    public function testGetSchemeDefault(): void
    {
        $this->assertEquals(
            'http',
            Uri::create()->getScheme()
        );
    }

    public function testGetSegment(): void
    {
        $this->assertEquals(
            'deep',
            Uri::create('https://domain.com/path/deep')->getSegment(2)
        );
    }

    public function testGetSegmentDecoded(): void
    {
        $this->assertEquals(
            'test path',
            Uri::create('http://domain.com/test%20path')->getSegment(1)
        );
    }

    public function testGetSegmentInvalid(): void
    {
        $this->assertEquals(
            '',
            Uri::create('https://domain.com/path/deep')->getSegment(3)
        );
    }

    public function testGetSegments(): void
    {
        $this->assertEquals(
            ['path', 'deep'],
            Uri::create('https://domain.com/path/deep')->getSegments()
        );
    }

    public function testGetTotalSegments(): void
    {
        $this->assertEquals(
            2,
            Uri::create('https://domain.com/path/deep')->getTotalSegments()
        );
    }

    public function testGetUserInfo(): void
    {
        $this->assertEquals(
            'user',
            Uri::create('http://user@domain.com/')->getUserInfo()
        );
    }

    public function testGetUserInfoWithPassword(): void
    {
        $this->assertEquals(
            'user:password',
            Uri::create('http://user:password@domain.com/')->getUserInfo()
        );
    }

    public function testGetUserInfoEncoded(): void
    {
        $this->assertEquals(
            'test%20user:test%20password',
            Uri::create('http://test%20user:test%20password@domain.com/')->getUserInfo()
        );
    }

}
