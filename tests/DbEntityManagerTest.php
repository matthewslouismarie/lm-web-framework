<?php

declare(strict_types=1);

namespace LM\WebFramework\Tests;

use DateTimeImmutable;
use LM\WebFramework\Database\DbEntityManager;
use LM\WebFramework\Database\Exceptions\InvalidDbDataException;
use LM\WebFramework\Database\Exceptions\NullDbDataNotAllowedException;
use LM\WebFramework\DataStructures\AppObject;
use LM\WebFramework\Model\BoolModel;
use LM\WebFramework\Model\DateTimeModel;
use LM\WebFramework\Model\EntityModel;
use LM\WebFramework\Model\ForeignEntityModel;
use LM\WebFramework\Model\IntModel;
use LM\WebFramework\Model\ListModel;
use LM\WebFramework\Model\StringModel;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DbEntityManagerTest extends TestCase
{
    private DbEntityManager $em;

    public function setUp(): void
    {
        $this->em = new DbEntityManager();
    }

    public function testScalarTypes(): void
    {
        

        $this->assertTrue($this->em->convertDbScalar(1, new BoolModel()));
        $this->assertFalse($this->em->convertDbScalar(0, new BoolModel()));
        $this->assertNull($this->em->convertDbScalar(null, new BoolModel(isNullable: true)));

        $this->assertEquals(47, $this->em->convertDbScalar(47, new IntModel()));

        $date = '2024-07-13';
        $this->assertEquals(new DateTimeImmutable($date), $this->em->convertDbScalar($date, new DateTimeModel()));
    }

    public function testInvalidDbDataException(): void
    {
        $this->expectException(InvalidDbDataException::class);
        $this->em->convertDbScalar(true, new IntModel());
    }

    public function testNullDbDataNotAllowedException(): void
    {
        $this->expectException(NullDbDataNotAllowedException::class);
        $this->em->convertDbScalar(null, new BoolModel());
    }

    public function testConversionToEntity(): void
    {
        $dbRows = [
            [
                'entity_id' => 'test',
                'entity_child_id' => 'prout',
                'child_id' => 'prout',
                'child_name' => 'Annyong',
                'child_real_name' => 'Hel-Loh',
                'child_age' => '43',
            ],
        ];

        $expectedAppObject = new AppObject([
            'id' => 'test',
            'child_id' => 'prout',
            'child' => [
                'id' => 'prout',
                'name' => 'Annyong',
                'real_name' => 'Hel-Loh',
                'age' => 43,
            ],
        ]);

        $entity = new EntityModel(
            'entity',
            [
                'id' => new StringModel(),
                'child_id' => new StringModel(),
                'child' => new EntityModel(
                    'child',
                    [
                        'id' => new StringModel(),
                        'name' => new StringModel(),
                        'real_name' => new StringModel(),
                        'age' => new IntModel(),
                    ],
                )
            ],
        );

        $this->assertEquals(
            $expectedAppObject,
            $this->em->convertDbRowsToAppObject($dbRows, $entity),
        );
    }

    public function testForeignEntity(): void
    {
        $dbRows = [
            [
                'category_id' => 'all-articles',
                'category_parent_id' => null,
            ],
            [
                'category_id' => 'some-specific-articles',
                'category_parent_id' => 'all-articles',
            ],
            [
                'category_id' => 'some-even-more-specific-articles',
                'category_parent_id' => 'all-articles',
            ],
            [
                'category_id' => 'all-reviews',
                'category_parent_id' => null,
            ],
        ];

        $expectedAppObject = new AppObject([
            'id' => 'some-specific-articles',
            'parent' => [
                'id' => 'all-articles',
                // 'parent' => null, // @todo Would be great to have a way to have itself as a foreign entity.
            ],
        ]);

        $model = new EntityModel(
            'category',
            [
                'id' => new StringModel(),
                'parent' => new ForeignEntityModel(
                    'id',
                    'parent_id',
                    'category',
                    [
                        'id' => new StringModel(),
                    ],
                )
            ],
        );

        $this->assertEquals(
            $expectedAppObject,
            $this->em->convertDbRowsToAppObject($dbRows, $model, 1),
        );

        $parentModel = new EntityModel(
            'category',
            [
                'id' => new StringModel(),
                'children' => new ListModel(new ForeignEntityModel(
                    'parent_id',
                    'id',
                    'category',
                    [
                        'id' => new StringModel(),
                        'parent_id' => new StringModel(),
                    ],
                )),
            ]
        );

        $expectedParent = new AppObject([
            'id' => 'all-articles',
            'children' => new AppObject([
                new AppObject([
                    'id' => 'some-specific-articles',
                    'parent_id' => 'all-articles',
                ]),
                new AppObject([
                    'id' => 'some-even-more-specific-articles',
                    'parent_id' => 'all-articles',
                ]),
            ]),
        ]);

        var_dump($this->em->convertDbRowsToAppObject($dbRows, $parentModel, 0));

        $this->assertEquals(
            $expectedParent,
            $this->em->convertDbRowsToAppObject($dbRows, $parentModel, 0),
        );
    }

    // public function testListModel(): void
    // {
    //     $toAppDatad = $this->em->convertDbScalar(['1', '2', '3'], new ListModel(new UintModel()));
    //     $this->assertEquals([1, 2, 3], $toAppDatad);
    // }
}