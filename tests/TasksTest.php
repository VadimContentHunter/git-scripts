<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\Tests;

use Psr\Log\NullLogger;
use PHPUnit\Framework\TestCase;
use vadimcontenthunter\GitScripts\TaskProgressLevel;
use vadimcontenthunter\GitScripts\Tasks;
use vadimcontenthunter\GitScripts\interfaces\ObjectTask;
use vadimcontenthunter\GitScripts\Tests\src\fakes\TasksFake;
use vadimcontenthunter\GitScripts\Tests\src\fakes\ObjectTaskFake;
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
     * @param array<ObjectTask> $taskList           Список задач который будет добавлен
     * @param array<ObjectTask> $expectableResult   Ожидаемый результат
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
}
