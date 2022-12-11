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
        return false;
    }

    /**
     * Метод запускает список задач на выполнение.
     * Добавить или удалить задачи из списка, после этого запуска нельзя.
     *
     * @return void
     *
     * @throws GitScriptsException
     */
    public function start(): void
    {
    }
}
