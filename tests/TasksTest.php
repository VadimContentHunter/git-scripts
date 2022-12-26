<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\Tests;

use Psr\Log\NullLogger;
use PHPUnit\Framework\TestCase;
use vadimcontenthunter\GitScripts\Tasks;
use vadimcontenthunter\GitScripts\TaskProgressLevel;
use vadimcontenthunter\GitScripts\interfaces\ObjectTask;
use vadimcontenthunter\GitScripts\Tests\src\fakes\TasksFake;
use vadimcontenthunter\GitScripts\exception\GitScriptsException;
use vadimcontenthunter\GitScripts\Tests\src\fakes\StandardTaskFake;

/**
 * Тесты для класса Tasks
 *
 * @package   GitScripts_Tests
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class TasksTest extends TestCase
{
    public TasksFake $tasksFake;

    public function setUp(): void
    {
        $this->tasksFake = new TasksFake();
    }

    /**
     * @test
     * @dataProvider providerAddTaskList
     *
     * @param array<ObjectTask> $taskList           Список задач который будет добавлен
     * @param array<ObjectTask> $expectableResult   Ожидаемый результат
     *
     * @return void
     */
    public function test_addTaskList_withTaskParameter_shouldChangeTheTaskList(
        array $taskList,
        array $expectableResult
    ): void {
        foreach ($taskList as $key => $task) {
            $this->tasksFake->addTaskList($task);
        }

        $this->assertEquals($expectableResult, $this->tasksFake->fakeGetTaskList());
    }

    public function providerAddTaskList(): array
    {
        $path = '.\\test\\test.test';
        return [
            'Test 1' => [
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath($path),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath($path),
                ],
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath($path)
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::WAITING),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('2')
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath($path)
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::WAITING),
                ],
            ],
            'Test 2' => [
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('2')
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath($path),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('4')
                        ->fakeSetParameterTitle('Task 4')
                        ->fakeSetParameterExecutionPath($path),
                ],
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath($path)
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::WAITING),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('2')
                        ->fakeSetParameterTitle('Task 4')
                        ->fakeSetParameterExecutionPath($path)
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::WAITING),
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerDeleteTaskList
     *
     * @param array<ObjectTask> $taskList Список задач который будет добавлен
     * @param string $index Индекс задачи для удаления
     * @param array<ObjectTask> $expectableResult Ожидаемый результат
     *
     * @return void
     */
    public function test_deleteTaskList_withIndexParameter_shouldChangeTheTaskList(
        array $taskList,
        string $index,
        array $expectableResult
    ): void {
        $this->tasksFake->fakeSetTaskList($taskList);
        $this->tasksFake->deleteTaskList($index);

        $this->assertEquals($expectableResult, $this->tasksFake->fakeGetTaskList());
    }

    public function providerDeleteTaskList(): array
    {
        $path = '.\\test\\test.test';
        return [
            'Test 1' => [
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath($path),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('2')
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath($path),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('3')
                        ->fakeSetParameterTitle('Task 3')
                        ->fakeSetParameterExecutionPath($path),
                ],
                '2',
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath($path)
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::WAITING),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('3')
                        ->fakeSetParameterTitle('Task 3')
                        ->fakeSetParameterExecutionPath($path)
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::WAITING),
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerGetTaskList
     *
     * @param array<ObjectTask> $taskList Список задач который будет добавлен
     * @param array<ObjectTask> $expectableResult Ожидаемый результат
     *
     * @return void
     */
    public function test_getTaskList_withoutParameters_shouldReturnTaskList(
        array $taskList,
        array $expectableResult
    ): void {
        $this->tasksFake->fakeSetTaskList($taskList);
        $taskList = $this->tasksFake->getTaskList();

        $this->assertEquals($expectableResult, $taskList);
    }

    public function providerGetTaskList(): array
    {
        $path = '.\\test\\test.test';
        return [
            'Test 1' => [
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath($path),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('2')
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath($path),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('3')
                        ->fakeSetParameterTitle('Task 3')
                        ->fakeSetParameterExecutionPath($path),
                ],
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath($path)
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::WAITING),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('2')
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath($path)
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::WAITING),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('3')
                        ->fakeSetParameterTitle('Task 3')
                        ->fakeSetParameterExecutionPath($path)
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::WAITING),
                ],
            ],
        ];
    }

     /**
     * @test
     * @dataProvider providerGetTaskListExceptions
     *
     * @param array $taskList Параметр для метода
     * @param \Exception $expectableResult Ожидаемый результат
     */
    public function test_getTaskList_withoutParameters_shouldThrowAnException(
        array $taskList,
        \Exception $expectableResult
    ): void {
        $this->expectException($expectableResult::class);

        $this->tasksFake->fakeSetTaskList($taskList);
        $this->tasksFake->getTaskList();
    }

    public function providerGetTaskListExceptions(): array
    {
        $path = '.\\test\\test.test';
        return [
            'Test 1' => [
                [
                    'test'
                ],
                new GitScriptsException(),
            ],
            'Test 2' => [
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath($path),
                    'test'
                ],
                new GitScriptsException(),
            ],
            'Test 3' => [
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath($path),
                    4442
                ],
                new GitScriptsException(),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerStart
     *
     * @param array<ObjectTask> $taskList Список задач который будет добавлен
     * @param array<ObjectTask> $expectableResult Ожидаемый результат
     *
     * @return void
     */
    public function test_start_withoutParameters_shouldChangeTheTaskList(
        array $taskList,
        array $expectableResult
    ): void {
        $this->tasksFake->fakeSetTaskList($taskList);
        $this->tasksFake->start();

        $this->assertEquals($expectableResult, $this->tasksFake->fakeGetTaskList());
    }

    public function providerStart(): array
    {
        return [
            'Test 1' => [
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturn0Fake.php'),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('2')
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturn5Fake.php'),
                ],
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturn0Fake.php')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::DONE),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('2')
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturn5Fake.php')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::ERROR),
                ],
            ],
            'Test 2' => [
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturn0Fake.php'),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('2')
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturn5Fake.php'),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('3')
                        ->fakeSetParameterTitle('Task 3')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturn0Fake.php')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::DONE),
                ],
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturn0Fake.php')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::DONE),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('2')
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturn5Fake.php')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::ERROR),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('3')
                        ->fakeSetParameterTitle('Task 3')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturn0Fake.php')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::DONE),
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerStartExceptions
     *
     * @param array $taskList Параметр для метода
     * @param \Exception $expectableResult Ожидаемый результат
     */
    public function test_start_withoutParameters_shouldThrowAnException(
        array $taskList,
        \Exception $expectableResult
    ): void {
        $this->expectException($expectableResult::class);

        $this->tasksFake->fakeSetTaskList($taskList);
        $this->tasksFake->start();
    }

    public function providerStartExceptions(): array
    {
        return [
            'Test 1' => [
                [
                    'test'
                ],
                new GitScriptsException(),
            ],
            'Test 2' => [
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturn0Fake.php'),
                    'test'
                ],
                new GitScriptsException(),
            ],
            'Test 3' => [
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturn0Fake.php'),
                    123,
                ],
                new GitScriptsException(),
            ],
            'Test 4' => [
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\NotFoundFile.php'),
                ],
                new GitScriptsException(),
            ]
        ];
    }

    /**
     * @test
     * @dataProvider providerResultAfterDeletion
     *
     * @param array<ObjectTask> $taskList Список задач который будет добавлен
     * @param array $deleteElements Массив с индексами на удаление
     * @param array<ObjectTask> $expectedResultForTaskList Ожидаемый результат  для Листа задач
     * @param array $expectedFunctionResult Ожидаемый результат для пользовательской функции
     *
     * @return void
     */
    public function test_result_withFunction_shouldChangeTheListOfTasksAfterDeletion(
        array $taskList,
        array $deleteElements,
        array $expectedResultForTaskList,
        array $expectedFunctionResult
    ): void {
        $customFunctionResults = [];
        $this->tasksFake->fakeSetTaskList($taskList);
        $this->tasksFake->result(function (Tasks $taskQueue, ObjectTask $thisTask, int $result) use ($deleteElements, &$customFunctionResults) {
            foreach ($deleteElements as $parameterIndex => $deleteIndexes) {
                if (strcmp($thisTask->getIndex(), (string)$parameterIndex) === 0) {
                    foreach ($deleteIndexes as $elementId => $deleteIndex) {
                        $taskQueue->deleteTaskList($deleteIndex);
                    }
                }
            }

            $customFunctionResults[] = $result;
        });

        $this->assertEquals($expectedResultForTaskList, $this->tasksFake->getTaskList());
        $this->assertEquals($expectedFunctionResult, $customFunctionResults);
    }

    public function providerResultAfterDeletion(): array
    {
        return [
            'Test 1' => [
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('5'),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('2')
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('0'),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('3')
                        ->fakeSetParameterTitle('Task 3')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('0'),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('4')
                        ->fakeSetParameterTitle('Task 4')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('4'),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('5')
                        ->fakeSetParameterTitle('Task 5')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('0'),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('6')
                        ->fakeSetParameterTitle('Task 6')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('1'),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('7')
                        ->fakeSetParameterTitle('Task 7')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('0'),
                ],
                [
                    '1' => ['2','1'],
                    '5' => ['6','1','7'],
                ],
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('3')
                        ->fakeSetParameterTitle('Task 3')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('0')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::DONE),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('4')
                        ->fakeSetParameterTitle('Task 4')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('4')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::ERROR),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('5')
                        ->fakeSetParameterTitle('Task 5')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('0')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::DONE),
                ],
                [5,0,4,0]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerResultAfterAdding
     *
     * @param array<ObjectTask> $taskList Список задач который будет добавлен
     * @param array $tasksToAdd Массив с объектами на добавление
     * @param array<ObjectTask> $expectedResultForTaskList Ожидаемый результат для Листа задач
     * @param array $expectedFunctionResult Ожидаемый результат для пользовательской функции
     *
     * @return void
     */
    public function test_result_withFunction_shouldChangeTheListOfTasksAfterAdding(
        array $taskList,
        array $tasksToAdd,
        array $expectedResultForTaskList,
        array $expectedFunctionResult
    ): void {
        $customFunctionResults = [];
        $this->tasksFake->fakeSetTaskList($taskList);
        $this->tasksFake->result(function (Tasks $taskQueue, ObjectTask $thisTask, int $result) use ($tasksToAdd, &$customFunctionResults) {
            foreach ($tasksToAdd as $parameterIndex => $addTasks) {
                if (strcmp($thisTask->getIndex(), (string)$parameterIndex) === 0) {
                    foreach ($addTasks as $elementId => $newTask) {
                        $taskQueue->addTaskList($newTask);
                    }
                }
            }

            $customFunctionResults[] = $result;
        });

        $this->assertEquals($expectedResultForTaskList, $this->tasksFake->getTaskList());
        $this->assertEquals($expectedFunctionResult, $customFunctionResults);
    }

    public function providerResultAfterAdding(): array
    {
        return [
            'Test 1' => [
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('5'),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('2')
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('0'),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('3')
                        ->fakeSetParameterTitle('Task 3')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('0'),
                ],
                [
                    '1' => [
                        (new StandardTaskFake(new NullLogger()))
                            // ->fakeSetParameterIndex('4')
                            ->fakeSetParameterTitle('Task 4')
                            ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                            ->fakeSetParameterArguments('4'),
                        (new StandardTaskFake(new NullLogger()))
                            // ->fakeSetParameterIndex('15')
                            ->fakeSetParameterTitle('Task 15')
                            ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                            ->fakeSetParameterArguments('0'),
                    ],
                    '3' => [
                        (new StandardTaskFake(new NullLogger()))
                            // ->fakeSetParameterIndex('6')
                            ->fakeSetParameterTitle('Task 6')
                            ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                            ->fakeSetParameterArguments('1'),
                        (new StandardTaskFake(new NullLogger()))
                            // ->fakeSetParameterIndex('7')
                            ->fakeSetParameterTitle('Task 7')
                            ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                            ->fakeSetParameterArguments('0'),
                    ],
                ],
                [
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('1')
                        ->fakeSetParameterTitle('Task 1')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('5')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::ERROR),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('2')
                        ->fakeSetParameterTitle('Task 2')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('0')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::DONE),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('3')
                        ->fakeSetParameterTitle('Task 3')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('0')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::DONE),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('4')
                        ->fakeSetParameterTitle('Task 4')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('4')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::ERROR),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('15')
                        ->fakeSetParameterTitle('Task 15')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('0')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::DONE),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('6')
                        ->fakeSetParameterTitle('Task 6')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('1')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::ERROR),
                    (new StandardTaskFake(new NullLogger()))
                        ->fakeSetParameterIndex('7')
                        ->fakeSetParameterTitle('Task 7')
                        ->fakeSetParameterExecutionPath('.\tests\src\fakes\ScriptReturnArgument.php')
                        ->fakeSetParameterArguments('0')
                        ->fakeSetParameterExecutionStatus(TaskProgressLevel::DONE),
                ],
                [5,0,0,4,0,1,0]
            ],
        ];
    }
}
