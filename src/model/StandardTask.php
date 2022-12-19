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
        if (!is_file($path)) {
            throw new GitScriptsException("Invalid file path.");
        }

        $this->executionPath = $path;

        return $this;
    }

    /**
     * Метод выполняет текущую задачу.
     *
     * @return bool Возвращает в случае успеха true, иначе false.
     *              Вернет true даже если задача была выполнена но не с нулевым кодом завершения.
     *
     * @throws GitScriptsException
     */
    public function execute(): bool
    {
        $index = $this->getIndex();
        $title = $this->getTitle();
        $executionPath = $this->getExecutionPath();
        $executionStatus = $this->getExecutionStatus();
        $output = null;
        $retval = null;

        $headString = PHP_EOL . 'Задача [ #' . $index . ' ' . $title . ' ] > ';

        if ($executionStatus !== TaskProgressLevel::WAITING) {
            print_r($headString . 'Нет в списках на ожидание.');
            print_r(PHP_EOL);
            return false;
        }

        $this->executionStatus = TaskProgressLevel::PROGRESS;
        print_r($headString . 'Выполняется . . .');
        if (exec('php ' . $executionPath, $output, $retval) === false) {
            throw new GitScriptsException("An unknown error occurred while executing.");
        }

        if ($retval === 0) {
            print_r($headString . 'Была выполнена, успешно.');
            print_r(PHP_EOL);
            $this->executionStatus = TaskProgressLevel::DONE;
            return true;
        }

        if ($retval !== 0) {
            print_r($headString . 'Была выполнена, с ошибкой.');
            $this->executionStatus = TaskProgressLevel::ERROR;
            if (count($output) !== 0 && $output !== null) {
                print_r($output);
                print_r(PHP_EOL);
            } else {
                print_r($headString . 'Сообщения от задачи нету!');
                print_r(PHP_EOL);
            }
            return false;
        }

        print_r($headString . 'Была не выполнена.');
        $this->executionStatus = TaskProgressLevel::NOT_IMPLEMENTED;
        if (count($output) !== 0 && $output !== null) {
            print_r($output);
            print_r(PHP_EOL);
        } else {
            print_r($headString . 'сообщения от задачи нету!');
            print_r(PHP_EOL);
        }

        return false;
    }

    /**
     * Метод возвращает статус выполнения задачи.
     *
     * @return string
     *
     * @throws GitScriptsException
     */
    public function getExecutionStatus(): string
    {
        if ($this->executionStatus === '') {
            throw new GitScriptsException("Execution status is empty.");
        }

        return $this->executionStatus;
    }

    /**
     * Метод возвращает уникальный index для задачи.
     *
     * @return mixed
     */
    public function getIndex(): string|int
    {
        if ($this->index === '') {
            throw new GitScriptsException("Index is empty.");
        }

        return $this->index;
    }

    /**
     * Метод возвращает наименование для задачи.
     *
     * @return mixed
     */
    public function getTitle(): string
    {
        if ($this->title === '') {
            throw new GitScriptsException("Title is empty.");
        }

        return $this->title;
    }

    /**
     * Метод возвращает статус выполнения задачи.
     *
     * @return string
     */
    public function getExecutionPath(): string
    {
        if ($this->executionPath === '') {
            throw new GitScriptsException("Execution path is empty.");
        }

        if (!is_file($this->executionPath)) {
            throw new GitScriptsException("Invalid file path.");
        }

        return $this->executionPath;
    }
}
