<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\model;

use Psr\Log\LoggerInterface;
use stdClass;
use vadimcontenthunter\GitScripts\TaskProgressLevel;
use vadimcontenthunter\GitScripts\interfaces\ObjectTask;
use vadimcontenthunter\GitScripts\exception\GitScriptsException;
use vadimcontenthunter\GitScripts\Tasks;

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
     * Хранит callback функцию, которая будет выполняться,
     * если метод execute будет возвращать true. По умолчанию null.
     *
     * @var \Closure|null
     */
    protected ?\Closure $functionWhenExecuteTrue = null;

    /**
     * Хранит callback функцию, которая будет выполняться,
     * если метод execute будет возвращать false. По умолчанию null.
     *
     * @var \Closure|null
     */
    protected ?\Closure $functionWhenExecuteFalse = null;

    /**
     * Хранит в строке аргументы для запуска файла
     *
     * @var string
     */
    protected string $arguments = '';

    /**
     * Saves the initialization of the LoggerInterface
     *
     * @var LoggerInterface
     */
    protected LoggerInterface $loggerInterface;

    /**
     * Initializes the StandardTask
     *
     * @param LoggerInterface $_loggerInterface Logger interface
     */
    public function __construct(LoggerInterface $_loggerInterface)
    {
        $this->loggerInterface = $_loggerInterface;
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

        // Начало строки для логирования.
        $headString =  'Задача [ #' . $index . ' ' . $title . ' ] > ';

        if ($executionStatus !== TaskProgressLevel::WAITING) {
            $this->loggerInterface->info($headString . 'Нет в списках на ожидание.');
            return false;
        }

        $this->executionStatus = TaskProgressLevel::PROGRESS;
        $this->loggerInterface->info($headString . 'Выполняется . . .');
        if (exec('php ' . $executionPath . ' ' . $this->arguments, $output, $retval) === false) {
            throw new GitScriptsException("An unknown error occurred while executing.");
        }

        if ($retval === 0) {
            $this->loggerInterface->info($headString . 'Была выполнена, успешно.');
            $this->executionStatus = TaskProgressLevel::DONE;

            // вызов функции перед возвратом результата, если не null
            if ($this->functionWhenExecuteTrue !== null) {
                $this->functionWhenExecuteTrue->call($this, $this);
            }
            return true;
        }

        if ($retval !== 0) {
            $this->executionStatus = TaskProgressLevel::ERROR;
            if (count($output) !== 0 && $output !== null) {
                $this->loggerInterface->warning($headString . 'Была выполнена, с ошибкой.');
                $this->loggerInterface->debug(implode(' \\n ', $output));
            } else {
                $this->loggerInterface->warning($headString . 'Была выполнена, с ошибкой. Сообщения от задачи нету!');
            }

            // вызов функции перед возвратом результата, если не null
            if ($this->functionWhenExecuteFalse !== null) {
                $this->functionWhenExecuteFalse->call($this, $this);
            }
            return false;
        }

        $this->executionStatus = TaskProgressLevel::NOT_IMPLEMENTED;
        if (count($output) !== 0 && $output !== null) {
            $this->loggerInterface->error($headString . 'Была не выполнена.');
            $this->loggerInterface->debug(implode(' \\n ', $output));
        } else {
            $this->loggerInterface->error($headString . 'Была не выполнена. Сообщения от задачи нету!');
        }

        // вызов функции перед возвратом результата, если не null
        if ($this->functionWhenExecuteFalse !== null) {
            $this->functionWhenExecuteFalse->call($this, $this);
        }
        return false;
    }

    /**
     * Выполняется если метод execute возвращает значение true
     * С начало выполниться функция установленная в этом методе,
     * потом вернется значение execute.
     * В качестве аргумента, функция принимает текущий объект.
     * Результат функции будет не обработан.
     *
     * @param callable $_function Функция, которая будет выполнена
     *
     * @return StandardTask
     */
    public function setWhenExecuteTrue(callable $_function): StandardTask
    {
        $this->functionWhenExecuteTrue = $_function;
        return $this;
    }

    /**
     * Выполняется если метод execute возвращает значение false
     * С начало выполниться функция установленная в этом методе,
     * потом вернется значение execute.
     * В качестве аргумента, функция принимает текущий объект.
     * Результат функции будет не обработан.
     *
     * @param callable $_function Функция, которая будет выполнена
     *
     * @return StandardTask
     */
    public function setWhenExecuteFalse(callable $_function): StandardTask
    {
        $this->functionWhenExecuteFalse = $_function;
        return $this;
    }

    /**
     * Метод добавляет аргументы к вызываемому файлу.
     * Данные экранируются с помощью функции addslashes.
     *
     * @param string $_arguments Аргументы в виде строки
     *
     * @return StandardTask
     */
    public function addArgumentsAsString(string $_arguments): StandardTask
    {
        $this->arguments =  $_arguments;
        return $this;
    }
}
