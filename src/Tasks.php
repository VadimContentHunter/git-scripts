<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts;

use vadimcontenthunter\GitScripts\exception\GitScriptsException;
use vadimcontenthunter\GitScripts\interfaces\ObjectTask;

/**
 * Класс работает с списком задач.
 *
 * @package   GitScripts
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class Tasks
{
    /**
     * Параметр хранит индекс задачи
     *
     * @var array<ObjectTask>
     */
    protected array $taskList = [];

    /**
     * Метод добавляет задачу в список на выполнение
     *
     * @param ObjectTask $objectTask
     *
     * @return Tasks
     */
    public function addTaskList(ObjectTask $objectTask): Tasks
    {
        $objectTask->setIndex($this->taskList);
        $this->taskList[] = $objectTask;
        return $this;
    }

    /**
     * Метод удаляет задачу по указанному индексу из писка на выполнения
     *
     * @param string $index
     *
     * @return bool Возвращает в случае успешного удаления true, иначе false.
     */
    public function deleteTaskList(string $index): bool
    {
        foreach ($this->taskList as $key => $task) {
            if (strcmp($task->getIndex(), $index) === 0) {
                unset($this->taskList[$key]);
                sort($this->taskList);
                return true;
            }
        }

        return false;
    }

    /**
     * Метод возвращает наименование для задачи.
     *
     * @return array<ObjectTask>
     *
     * @throws GitScriptsException
     */
    public function getTaskList(): array
    {
        foreach ($this->taskList as $key => $task) {
            if (!($task instanceof ObjectTask)) {
                throw new GitScriptsException("Incorrect array element type, must be an ObjectTask");
            }
        }
        return $this->taskList;
    }

    /**
     * Метод запускает список задач на выполнение.
     *
     * @return void
     *
     * @throws GitScriptsException
     */
    public function start(): void
    {
        foreach ($this->getTaskList() as $key => $task) {
            if ($task instanceof ObjectTask) {
                $task->execute();
            } else {
                throw new GitScriptsException("Incorrect array element type, must be an ObjectTask");
            }
        }
    }

    /**
     * Выполняет пользовательскую функцию к каждой задачи
     *
     * @param callable $_function Функция, которая будет выполнена после выполнения задачи.
     *                            `function(int $result): void`
     *                            В качестве 1 аргумент результат выполнения метода execute задачи.
     *
     * @return void
     */
    public function result(callable $_function): void
    {
        foreach ($this->getTaskList() as $key => $task) {
            if ($task instanceof ObjectTask) {
                $_function($task->execute());
            } else {
                throw new GitScriptsException("Incorrect array element type, must be an ObjectTask");
            }
        }
    }
}
