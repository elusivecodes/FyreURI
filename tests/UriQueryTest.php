<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Uri\Uri;

trait UriQueryTest
{

    public function testAddQuery(): void
    {
        $uri = Uri::create('/?param1=a&param2=b&param3=c');

        $this->assertSame(
            $uri,
            $uri->addQuery('param3', 'c')
        );

        $this->assertSame(
            [
                'param1' => 'a',
                'param2' => 'b',
                'param3' => 'c'
            ],
            $uri->getQuery()
        );
    }

    public function testExceptQuery(): void
    {
        $uri = Uri::create('/?param1=a&param2=b&param3=c');

        $this->assertSame(
            $uri,
            $uri->exceptQuery(['param1'])
        );

        $this->assertSame(
            [
                'param2' => 'b',
                'param3' => 'c'
            ],
            $uri->getQuery()
        );
    }

    public function testOnlyQuery(): void
    {
        $uri = Uri::create('/?param1=a&param2=b&param3=c');

        $this->assertSame(
            $uri,
            $uri->onlyQuery(['param1'])
        );

        $this->assertSame(
            [
                'param1' => 'a'
            ],
            $uri->getQuery()
        );
    }

}
