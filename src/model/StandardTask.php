<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\model;

use vadimcontenthunter\GitScripts\exception\GitScriptsException;
use vadimcontenthunter\GitScripts\interfaces\ObjectTask;
use vadimcontenthunter\GitScripts\TaskProgressLevel;

/**
 * Реализация стандартной задачи
 *
 * @package   GitScripts_Model
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class StandardTask implements ObjectTask
{
    /**
     * Параметр хранит индекс задачи
     *
     * @var string
     */
    protected string $index = '';

     /**
      * Параметр хранит наименование задачи
      *
      * @var string
      */
    protected string $title = '';

    /**
     * Путь к выполняющему скрипту
     *
     * @var string
     */
    protected string $executionPath = '';

    /**
     * Параметр хранит статус выполнения задачи
     *
     * @var string
     */
    protected string $executionStatus = '';

    /**
     * Initializes the StandardTask
     */
    public function __construct()
    {
        $this->executionStatus = TaskProgressLevel::WAITING;
    }

    /**
     * Метод устанавливает уникальный index для задачи, на основе существующих.
     *
     * @param array<int|string|ObjectTask> $index Массив существующих индексов.
     *
     * @return StandardTask
     *
     * @throws GitScriptsException
     */
    public function setIndex(array $index = []): StandardTask
    {
        $this->index = '1';

        $newIndices = array_map(
            function ($v) {
                if ($v instanceof ObjectTask) {
                    return $v->getIndex();
                }
                return $v;
            },
            $index
        );

        usort($newIndices, function ($a, $b) {
            if ($a instanceof ObjectTask) {
                $a = $a->getIndex();
            }

            if ($b instanceof ObjectTask) {
                $b = $b->getIndex();
            }

            if (!is_numeric($a)) {
                return 1;
            }

            if (is_numeric($a) && !is_numeric($b)) {
                return -1;
            }

            if ($a === $b) {
                return 0;
            }

            return ($a < $b) ? 1 : -1;
        });

        if (isset($newIndices[0]) && is_numeric($newIndices[0])) {
            $this->index = (string) ++$newIndices[0];
        }

        return $this;
    }

    /**
     * Метод устанавливает наименование для задачи.
     *
     * @param string $title
     *
     * @return StandardTask
     *
     * @throws GitScriptsException
     */
    public function setTitle(string $title): StandardTask
    {
        if ($title === '') {
            throw new GitScriptsException("Title is empty");
        }

        $this->title = $title;
        return $this;
    }

    /**
     * Метод устанавливает путь к выполняемому фалу текущей задачи
     *
     * @param string $path
     *
     * @return StandardTask
     *
     * @throws GitScriptsException
     */
    public function setExecutionPath(string $path): StandardTask
    {
        return $this;
    }

    /**
     * Метод выполняет текущую задачу.
     *
     * @return bool Возвращает в случае успеха true, иначе false.
     *
     * @throws GitScriptsException
     */
    public function execute(): bool
    {
        return false;
    }

    /**
     * Метод возвращает статус выполнения задачи.
     *
     * @return string
     */
    public function getExecutionStatus(): string
    {
        return $this->executionStatus;
    }

    /**
     * Метод возвращает уникальный index для задачи.
     *
     * @return mixed
     */
    public function getIndex(): string|int
    {
        return $this->index;
    }

    /**
     * Метод возвращает наименование для задачи.
     *
     * @return mixed
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Метод возвращает статус выполнения задачи.
     *
     * @return string
     */
    public function getExecutionPath(): string
    {
        return $this->executionPath;
    }
}
