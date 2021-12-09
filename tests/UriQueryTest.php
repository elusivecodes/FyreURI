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

        $this->assertEquals(
            $uri,
            $uri->addQuery('param3', 'c')
        );

        $this->assertEquals(
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

        $this->assertEquals(
            $uri,
            $uri->exceptQuery(['param1'])
        );

        $this->assertEquals(
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

        $this->assertEquals(
            $uri,
            $uri->onlyQuery(['param1'])
        );

        $this->assertEquals(
            [
                'param1' => 'a'
            ],
            $uri->getQuery()
        );
    }

}
