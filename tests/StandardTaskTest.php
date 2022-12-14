<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use vadimcontenthunter\GitScripts\TaskProgressLevel;
use vadimcontenthunter\GitScripts\exception\GitScriptsException;
use vadimcontenthunter\GitScripts\Tests\src\fakes\ObjectTaskFake;
use vadimcontenthunter\GitScripts\Tests\src\fakes\StandardTaskFake;

/**
 * Тесты для класса StandardTaskTest
 *
 * @package   GitScripts_Tests
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class StandardTaskTest extends TestCase
{
    protected StandardTaskFake $standardTaskFake;

    public function setUp(): void
    {
        $this->standardTaskFake = new StandardTaskFake(new NullLogger());
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
        $stub = $this->createStub(ObjectTaskFake::class)
                    ->method('getIndex')
                    ->willReturn('7');
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
                    new ObjectTaskFake('4'),
                    new ObjectTaskFake('3'),
                    new ObjectTaskFake('7'),
                    new ObjectTaskFake('1'),
                ],
                '8',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerSetTitle
     *
     * @param string $titleParameter Параметр для метода
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
            'Heading in the form of numbers in a line' => [
                '145458612',
                '145458612',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerSetTitleException
     *
     * @param string $titleParameter Параметр для метода
     * @param \Exception $expectableResult Ожидаемый результат
     */
    public function test_setTitle_withTitleParameter_shouldThrowAnException(string $titleParameter, \Exception $expectableResult): void
    {
        $this->expectException($expectableResult::class);
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
     * @param string $executionPath Параметр для метода
     * @param string $expectableResult Ожидаемый результат
     */
    public function test_setExecutionPath_withExecutionPathParameter_shouldChangeTheTitleParameter(string $executionPath, string $expectableResult): void
    {
        $this->standardTaskFake->setExecutionPath($executionPath);
        $this->assertEquals(
            $expectableResult,
            $this->standardTaskFake->fakeGetParameterExecutionPath()
        );
    }

    public function providerSetExecutionPath(): array
    {
        $folder = preg_replace('~[\\/]*tests[\\/]*.*~ui', '', __DIR__);
        return [
            'Full path to the file' => [
                $folder . '\tests\StandardTaskTest.php',
                $folder . '\tests\StandardTaskTest.php',
            ],
            'Full path to the file 2' => [
                $folder . '/tests/StandardTaskTest.php',
                $folder . '/tests/StandardTaskTest.php',
            ],
            'Relative file path' => [
                '.\tests\StandardTaskTest.php',
                '.\tests\StandardTaskTest.php',
            ],
            'Relative file path 2' => [
                './tests/StandardTaskTest.php',
                './tests/StandardTaskTest.php',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerSetExecutionPathExceptions
     *
     * @param string $executionPath Параметр для метода
     * @param \Exception $expectableResult Ожидаемый результат
     */
    public function test_setExecutionPath_withExecutionPathParameter_shouldThrowAnException(string $executionPath, \Exception $expectableResult): void
    {
        $this->expectException($expectableResult::class);
        $this->standardTaskFake->setExecutionPath($executionPath);
    }

    public function providerSetExecutionPathExceptions(): array
    {
        $folder = preg_replace('~[\\/]*tests[\\/]*.*~ui', '', __DIR__);

        return [
            'ExecutionPath is empty' => [
                '',
                new GitScriptsException(),
            ],
            'File does not exist' => [
                $folder . '/non-existent-file.php',
                new GitScriptsException(),
            ],
            'File does not exist 2' => [
                $folder . '\non-existent-file.php',
                new GitScriptsException(),
            ],
            'File does not exist 3' => [
                './non-existent-file.php',
                new GitScriptsException(),
            ],
            'File does not exist 4' => [
                '.\non-existent-file.php',
                new GitScriptsException(),
            ],
            'Folder selected' => [
                $folder . '/git-scripts/tests',
                new GitScriptsException(),
            ],
            'Folder selected 2' => [
                $folder . '\git-scripts\tests',
                new GitScriptsException(),
            ],
            'Folder selected 3' => [
                './tests',
                new GitScriptsException(),
            ],
            'Folder selected 4' => [
                '.\tests',
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

    /**
     * @test
     * @dataProvider providerGetExecutionStatusExceptions
     *
     * @param string $executionStatus Параметр для метода
     * @param \Exception $expectableResult Ожидаемый результат
     */
    public function test_getExecutionStatus_withoutParameters_shouldThrowAnException(string $executionStatus, \Exception $expectableResult): void
    {
        $this->expectException($expectableResult::class);
        $this->standardTaskFake->fakeSetParameterExecutionStatus($executionStatus);
        $this->standardTaskFake->getExecutionStatus();
    }

    public function providerGetExecutionStatusExceptions(): array
    {
        return [
            'ExecutionStatus is empty' => [
                '',
                new GitScriptsException(),
            ],
        ];
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

    /**
     * @test
     * @dataProvider providerGetIndexExceptions
     *
     * @param string $index Параметр для метода
     * @param \Exception $expectableResult Ожидаемый результат
     */
    public function test_getIndex_withoutParameters_shouldThrowAnException(string $index, \Exception $expectableResult): void
    {
        $this->expectException($expectableResult::class);
        $this->standardTaskFake->fakeSetParameterExecutionStatus($index);
        $this->standardTaskFake->getIndex();
    }

    public function providerGetIndexExceptions(): array
    {
        return [
            'Index is empty' => [
                '',
                new GitScriptsException(),
            ],
        ];
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

    /**
     * @test
     * @dataProvider providerGetTitleExceptions
     *
     * @param string $title Параметр для метода
     * @param \Exception $expectableResult Ожидаемый результат
     */
    public function test_getTitle_withoutParameters_shouldThrowAnException(string $title, \Exception $expectableResult): void
    {
        $this->expectException($expectableResult::class);
        $this->standardTaskFake->fakeSetParameterExecutionStatus($title);
        $this->standardTaskFake->getTitle();
    }

    public function providerGetTitleExceptions(): array
    {
        return [
            'Title is empty' => [
                '',
                new GitScriptsException(),
            ],
        ];
    }

    /** @test */
    public function test_getExecutionPath_withoutParameters_shouldReturnExecutionPath(): void
    {
        $expectableResult = '.\tests\StandardTaskTest.php';
        $this->standardTaskFake->fakeSetParameterExecutionPath($expectableResult);
        $this->assertEquals(
            $expectableResult,
            $this->standardTaskFake->getExecutionPath()
        );
    }

    /**
     * @test
     * @dataProvider providerGetExecutionPathExceptions
     *
     * @param string $executionPath Параметр для метода
     * @param \Exception $expectableResult Ожидаемый результат
     */
    public function test_getExecutionPath_withoutParameters_shouldThrowAnException(string $executionPath, \Exception $expectableResult): void
    {
        $this->expectException($expectableResult::class);
        $this->standardTaskFake->fakeSetParameterExecutionPath($executionPath);
        $this->standardTaskFake->getExecutionPath();
    }

    public function providerGetExecutionPathExceptions(): array
    {
        $folder = preg_replace('~[\\/]tests[\\/]*.*~ui', '', __DIR__);
        return [
            'Title is empty' => [
                '',
                new GitScriptsException(),
            ],
            'File does not exist' => [
                $folder . '/non-existent-file.php',
                new GitScriptsException(),
            ],
            'File does not exist 2' => [
                $folder . '\non-existent-file.php',
                new GitScriptsException(),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerExecute
     *
     * @param string $title Параметр для метода
     * @param mixed $expectableResult Ожидаемый результат
     */
    public function test_execute_withoutParameters_shouldReturnTrue(string $executionPath, mixed $expectableResult): void
    {
        $this->standardTaskFake->setIndex();
        $this->standardTaskFake->setTitle('ScriptFake');
        $this->standardTaskFake->setExecutionPath($executionPath);
        $result = $this->standardTaskFake->execute();
        $this->assertEquals($expectableResult, $result);
    }

    public function providerExecute(): array
    {
        return [
            'Script Return 5' => [
                '.\tests\src\fakes\ScriptReturn5Fake.php',
                5,
            ],
            'Script Return 0' => [
                '.\tests\src\fakes\ScriptReturn0Fake.php',
                0,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerExecuteExceptions
     *
     * @param string $index Параметр для метода
     * @param string $title Параметр для метода
     * @param string $executionPath Параметр для метода
     * @param string $status Параметр для метода
     * @param \Exception $expectableResult Ожидаемый результат
     *
     * @return void
     */
    public function test_execute_withoutParameters_shouldThrowAnException(
        string $index,
        string $title,
        string $executionPath,
        string $status,
        \Exception $expectableResult
    ): void {
        $this->expectException($expectableResult::class);

        $this->standardTaskFake->fakeSetParameterIndex('');
        $this->standardTaskFake->fakeSetParameterTitle('');
        $this->standardTaskFake->fakeSetParameterExecutionPath('');
        $this->standardTaskFake->fakeSetParameterExecutionStatus('');
        $this->standardTaskFake->execute();
    }

    public function providerExecuteExceptions(): array
    {
        return [
            'Empty parameters' => [
                '',
                '',
                '',
                '',
                new GitScriptsException(),
            ],
            'Empty parameter index' => [
                '',
                'ScriptFake',
                '.\tests\src\fakes\ScriptReturn0Fake.php',
                TaskProgressLevel::WAITING,
                new GitScriptsException(),
            ],
            'Empty parameter title' => [
                '1',
                '',
                '.\tests\src\fakes\ScriptReturn0Fake.php',
                TaskProgressLevel::WAITING,
                new GitScriptsException(),
            ],
            'Empty parameter execution path' => [
                '1',
                'ScriptFake',
                '',
                TaskProgressLevel::WAITING,
                new GitScriptsException(),
            ],
            'Empty parameter status' => [
                '1',
                'ScriptFake',
                '.\tests\src\fakes\ScriptReturn0Fake.php',
                '',
                new GitScriptsException(),
            ],
        ];
    }

    /** @test */
    public function test_setWhenExecuteValue_withFunction_mustExecuteBeforeTheExecuteMethod(): void
    {
        $this->expectOutputString('setWhenExecuteTrue');

        $this->standardTaskFake->setIndex()
            ->setTitle('ScriptFake')
            ->setExecutionPath('.\tests\src\fakes\ScriptReturn0Fake.php')
            ->setWhenExecuteValue(
                function ($thisTask, $result) {
                    print('setWhenExecuteTrue');
                }
            )
            ->execute();
    }

    /** @test */
    public function test_addArgumentsAsString_withAStringOfArguments_shouldOutputTheSpecifiedMessage(): void
    {
        $result = $this->standardTaskFake->setIndex()
            ->setTitle('ScriptFake')
            ->setExecutionPath('.\tests\src\fakes\ScriptReturn1or0Fake.php')
            ->addArgumentsAsString('ScriptReturn1or0Fake.php')
            ->execute();

        $this->assertEquals(0, $result);
    }
}
