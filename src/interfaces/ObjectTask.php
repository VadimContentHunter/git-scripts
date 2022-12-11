<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\interfaces;

use vadimcontenthunter\GitScripts\exception\GitScriptsException;

/**
 * Интерфейс описывает основные методы для работы с "Объектами задача".
 *
 * @package   GitScripts_Interfaces
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface ObjectTask
{
    /**
     * Метод устанавливает уникальный index для задачи, на основе существующих.
     *
     * @param array<int|string> $index Массив существующих индексов.
     *
     * @return mixed
     *
     * @throws GitScriptsException
     */
    public function setIndex(array $index): mixed;

    /**
     * Метод устанавливает наименование для задачи.
     *
     * @param string $title
     *
     * @return mixed
     *
     * @throws GitScriptsException
     */
    public function setTitle(string $title): mixed;

    /**
     * Метод устанавливает путь к выполняемому фалу текущей задачи
     *
     * @param string $path
     *
     * @return mixed
     *
     * @throws GitScriptsException
     */
    public function setExecutionPath(string $path): mixed;

    /**
     * Метод выполняет текущую задачу.
     *
     * @return bool Возвращает в случае успеха true, иначе false.
     *
     * @throws GitScriptsException
     */
    public function execute(): bool;

    /**
     * Метод возвращает статус выполнения задачи.
     *
     * @return string
     */
    public function getExecutionStatus(): string;

    /**
     * Метод возвращает уникальный index для задачи.
     *
     * @return mixed
     */
    public function getIndex(): string;

    /**
     * Метод возвращает наименование для задачи.
     *
     * @return mixed
     */
    public function getTitle(): string;
}
