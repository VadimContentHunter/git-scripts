<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\Tests;

use PHPUnit\Framework\TestCase;
use vadimcontenthunter\GitScripts\TaskProgressLevel;
use vadimcontenthunter\GitScripts\exception\GitScriptsException;
use vadimcontenthunter\GitScripts\Tests\src\fakes\ObjectTaskFake;
use vadimcontenthunter\GitScripts\Tests\src\fakes\StandardTaskFake;

class StandardTaskTest extends TestCase
{
    protected StandardTaskFake $standardTaskFake;

    public function setUp(): void
    {
        $this->standardTaskFake = new StandardTaskFake();
    }

    /** @test */
    public function test_constructor_withoutParameters_shouldChangeTheParameterToWaiting(): void
    {
        $this->assertEquals(
            TaskProgressLevel::WAITING,
            $this->standardTaskFake->fakeGetParameterExecutionStatus()
        );
    }

    /**
     * @test
     * @dataProvider providerSetIndex
     *
     * @param array $indexParameter Параметр для метода
     * @param string $expectableResult Ожидаемый результат
     */
    public function test_setIndex_withIndexParameter_shouldChangeTheIndexParameter(array $indexParameter, string $expectableResult): void
    {
        $this->standardTaskFake->setIndex($indexParameter);
        $this->assertEquals(
            $expectableResult,
            $this->standardTaskFake->fakeGetParameterIndex()
        );
    }

    public function providerSetIndex(): array
    {
        return [
            'Empty index array' => [
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
                    $this->createMock(ObjectTaskFake::class)
                        ->method('getIndex')
                        ->willReturn(7),
                    $this->createMock(ObjectTaskFake::class)
                        ->method('getIndex')
                        ->willReturn(5),
                    $this->createMock(ObjectTaskFake::class)
                        ->method('getIndex')
                        ->willReturn(3),
                    $this->createMock(ObjectTaskFake::class)
                        ->method('getIndex')
                        ->willReturn(2),
                ],
                '8',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerSetTitle
     *
     * @param array $titleParameter Параметр для метода
     * @param string $expectableResult Ожидаемый результат
     */
    public function test_setTitle_withTitleParameter_shouldChangeTheTitleParameter(string $titleParameter, string $expectableResult): void
    {
        $this->standardTaskFake->setTitle($titleParameter);
        $this->assertEquals(
            $expectableResult,
            $this->standardTaskFake->fakeGetParameterTitle()
        );
    }

    public function providerSetTitle(): array
    {
        return [
            'Title is a string' => [
                'phpcs',
                'phpcs',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerSetTitleException
     *
     * @param array $titleParameter Параметр для метода
     * @param string $expectableResult Ожидаемый результат
     */
    public function test_setTitle_withTitleParameter_shouldThrowAnException(string $titleParameter, \Exception $expectableResult): void
    {
        $this->expectException(GitScriptsException::class);
        $this->standardTaskFake->setTitle($titleParameter);
    }

    public function providerSetTitleException(): array
    {
        return [
            'Title is empty' => [
                '',
                new GitScriptsException(),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerSetExecutionPath
     *
     * @param array $titleParameter Параметр для метода
     * @param string $expectableResult Ожидаемый результат
     */
    public function test_setExecutionPath_withTitleParameter_shouldChangeTheTitleParameter(string $titleParameter, string $expectableResult): void
    {
        $this->standardTaskFake->setExecutionPath($titleParameter);
        $this->assertEquals(
            $expectableResult,
            $this->standardTaskFake->fakeGetParameterExecutionPath()
        );
    }

    public function providerSetExecutionPath(): array
    {
        return [
            'ExecutionPath' => [
                '',
                '',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerSetExecutionPathExceptions
     *
     * @param array $titleParameter Параметр для метода
     * @param string $expectableResult Ожидаемый результат
     */
    public function test_setExecutionPath_withTitleParameter_shouldThrowAnException(string $titleParameter, \Exception $expectableResult): void
    {
        $this->expectException(GitScriptsException::class);
        $this->standardTaskFake->setExecutionPath($titleParameter);
    }

    public function providerSetExecutionPathExceptions(): array
    {
        return [
            'ExecutionPath' => [
                '',
                new GitScriptsException(),
            ],
        ];
    }

    /** @test */
    public function test_getExecutionStatus_withoutParameters_shouldReturnExecutionStatus(): void
    {
        $expectableResult = TaskProgressLevel::PROGRESS;
        $this->standardTaskFake->fakeSetParameterExecutionStatus($expectableResult);
        $this->assertEquals(
            $expectableResult,
            $this->standardTaskFake->getExecutionStatus()
        );
    }

    /** @test */
    public function test_getIndex_withoutParameters_shouldReturnIndex(): void
    {
        $expectableResult = '3';
        $this->standardTaskFake->fakeSetParameterIndex($expectableResult);
        $this->assertEquals(
            $expectableResult,
            $this->standardTaskFake->getIndex()
        );
    }

    /** @test */
    public function test_getTitle_withoutParameters_shouldReturnTheTitleAsAString(): void
    {
        $expectableResult = 'phpcs';
        $this->standardTaskFake->fakeSetParameterTitle($expectableResult);
        $this->assertEquals(
            $expectableResult,
            $this->standardTaskFake->getTitle()
        );
    }

    /** @test */
    public function test_getExecutionPath_withoutParameters_shouldReturnExecutionPath(): void
    {
        $expectableResult = './folder/test.php';
        $this->standardTaskFake->fakeSetParameterExecutionPath($expectableResult);
        $this->assertEquals(
            $expectableResult,
            $this->standardTaskFake->getExecutionPath()
        );
    }
}
