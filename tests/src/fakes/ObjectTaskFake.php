<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\Tests\src\fakes;

use vadimcontenthunter\GitScripts\exception\GitScriptsException;
use vadimcontenthunter\GitScripts\interfaces\ObjectTask;
use vadimcontenthunter\GitScripts\TaskProgressLevel;

/**
 * Подделка реализующая интерфейс ObjectTask
 *
 * @package   GitScripts_Tests_Fakes
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ObjectTaskFake implements ObjectTask
{
    /**
     * Параметр хранит индекс задачи
     *
     * @var string
     */
    public string $index = '';

    public function __construct(string $_index = '')
    {
        $this->index = $_index;
    }

    /**
     * Метод устанавливает уникальный index для задачи, на основе существующих.
     *
     * @param array $index Массив существующих индексов.
     * @return ObjectTaskFake
     */
    public function setIndex(array $index): ObjectTaskFake
    {
        return $this;
    }

    /**
     * Метод устанавливает наименование для задачи.
     *
     * @param string $title
     * @return ObjectTaskFake
     */
    public function setTitle(string $title): ObjectTaskFake
    {
        return $this;
    }

    /**
     * Метод устанавливает путь к выполняемому фалу текущей задачи
     *
     * @param string $path
     * @return ObjectTaskFake
     */
    public function setExecutionPath(string $path): ObjectTaskFake
    {
        return $this;
    }

    /**
     * Метод выполняет текущую задачу.
     *
     * @return int Возвращает значение которое вернул скрипт.
     */
    public function execute(): int
    {
        return -1;
    }

    /**
     * Метод возвращает статус выполнения задачи.
     *
     * @return string
     */
    public function getExecutionStatus(): string
    {
        return '';
    }

    /**
     * Метод возвращает уникальный index для задачи.
     *
     * @return string
     */
    public function getIndex(): string|int
    {
        return $this->index;
    }

    /**
     * Метод возвращает наименование для задачи.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return '';
    }
}
