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
     * Метод возвращает список из защищенного параметра без каких либо взаимодействием.
     *
     * @return array
     */
    public function fakeGetTaskList(): array
    {
        return $this->taskList;
    }
}
