<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\interfaces;

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
     */
    public function setIndex(array $index): mixed;

    /**
     * Метод устанавливает наименование для задачи.
     *
     * @param string $title
     *
     * @return mixed
     */
    public function setTitle(string $title): mixed;

    /**
     * Метод устанавливает путь к выполняемому фалу текущей задачи
     *
     * @param string $path
     *
     * @return mixed
     */
    public function setExecutionPath(string $path): mixed;

    /**
     * Метод выполняет текущую задачу.
     *
     * @return bool Возвращает в случае успеха true, иначе false.
     */
    public function execute(): bool;
}
