<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Http\Uri;

trait UriQueryTestTrait
{

    public function testAddQuery(): void
    {
        $uri1 = Uri::fromString('/?param1=a&param2=b');
        $uri2 = $uri1->addQuery('param3', 'c');

        $this->assertSame(
            [
                'param1' => 'a',
                'param2' => 'b'
            ],
            $uri1->getQuery()
        );

        $this->assertSame(
            [
                'param1' => 'a',
                'param2' => 'b',
                'param3' => 'c'
            ],
            $uri2->getQuery()
        );
    }

    public function testExceptQuery(): void
    {
        $uri1 = Uri::fromString('/?param1=a&param2=b&param3=c');
        $uri2 = $uri1->exceptQuery(['param1']);

        $this->assertSame(
            [
                'param1' => 'a',
                'param2' => 'b',
                'param3' => 'c'
            ],
            $uri1->getQuery()
        );

        $this->assertSame(
            [
                'param2' => 'b',
                'param3' => 'c'
            ],
            $uri2->getQuery()
        );
    }

    public function testOnlyQuery(): void
    {
        $uri1 = Uri::fromString('/?param1=a&param2=b&param3=c');
        $uri2 = $uri1->onlyQuery(['param1']);

        $this->assertSame(
            [
                'param1' => 'a',
                'param2' => 'b',
                'param3' => 'c'
            ],
            $uri1->getQuery()
        );

        $this->assertSame(
            [
                'param1' => 'a'
            ],
            $uri2->getQuery()
        );
    }

}
