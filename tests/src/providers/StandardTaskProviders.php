<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\Tests\src\providers;

use PHPUnit\Framework\TestCase;
use vadimcontenthunter\GitScripts\Tests\src\fakes\ObjectTaskFake;

class StandardTaskProviders
{
    public static function providerIndexes(TestCase $testCase): array
    {
        return [
            'An empty array of indices' => [
                [],
                '1',
            ],
            'Array of indexes with integer data' => [
                [9,5,3,1,2],
                '10',
            ],
            'Array of indices with integer data and strings' => [
                ['9','5',3,'1',2],
                '10',
            ],
            'Array of indices with integer data and text strings' => [
                ['number 9','number 5',3,'1',2],
                '4',
            ],
            'Array of indexes with ObjectTask objects' => [
                [
                    $testCase->createMock(ObjectTaskFake::class)
                        ->method('getIndex')
                        ->willReturn(7),
                    $testCase->createMock(ObjectTaskFake::class)
                        ->method('getIndex')
                        ->willReturn(5),
                    $testCase->createMock(ObjectTaskFake::class)
                        ->method('getIndex')
                        ->willReturn(3),
                    $testCase->createMock(ObjectTaskFake::class)
                        ->method('getIndex')
                        ->willReturn(2),
                ],
                '8',
            ],
        ];
    }
}
