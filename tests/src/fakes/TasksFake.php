<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\Tests\src\fakes;

use vadimcontenthunter\GitScripts\Tasks;

/**
 * Класс подделка от оригинального Tasks
 *
 * @package   GitScripts_Fake
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class TasksFake extends Tasks
{
    /**
     * Возвращает список из защищенного параметра без каких либо взаимодействием.
     *
     * @return array
     */
    public function fakeGetTaskList(): array
    {
        return $this->taskList;
    }

    /**
     * Устанавливает новое значение для приватного параметра списка задач, без каких либо манипуляций.
     *
     * @param array $_taskList
     *
     * @return TasksFake
     */
    public function fakeSetTaskList(array $_taskList): TasksFake
    {
        $this->taskList = $_taskList;

        return $this;
    }
}
