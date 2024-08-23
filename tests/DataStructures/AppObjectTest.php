<?php

declare(strict_types=1);

namespace LM\WebFramework\Tests\Database;

use InvalidArgumentException;
use LM\WebFramework\DataStructures\AppObject;
use PHPUnit\Framework\TestCase;

final class AppObjectTest extends TestCase
{
    public function testCreatingAppObjectWithList(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new AppObject([7, null, true]);
    }

    public function testWithListProperty(): void
    {
        $appArray = [
            'id' => 4,
            'items' => [
                [
                    'name' => 'Item 1',
                ],
                [
                    'name' => 'Item 2',
                ],
            ],
        ];
        $appObject = new AppObject($appArray);
        $this->assertIsList($appObject['items']);
        $this->assertInstanceOf(AppObject::class, $appObject['items'][0]);
        $this->assertInstanceOf(AppObject::class, $appObject['items'][1]);
    }
}