<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\Tests\src\fakes;

use vadimcontenthunter\GitScripts\model\StandardTask;

/**
 * Класс подделка StandardTask
 *
 * @package   GitScripts_Tests_Fakes
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class StandardTaskFake extends StandardTask
{
    /**
     * Возвращает значение защищенного параметра index без манипуляций с ним.
     *
     * @return string
     */
    public function fakeGetParameterIndex(): string
    {
        return $this->index;
    }

    /**
     * Возвращает значение защищенного параметра title без манипуляций с ним.
     *
     * @return string
     */
    public function fakeGetParameterTitle(): string
    {
        return $this->title;
    }

    /**
     * Возвращает значение защищенного параметра executionStatus без манипуляций с ним.
     *
     * @return string
     */
    public function fakeGetParameterExecutionStatus(): string
    {
        return $this->executionStatus;
    }

    /**
     * Возвращает значение защищенного параметра executionPath без манипуляций с ним.
     *
     * @return string
     */
    public function fakeGetParameterExecutionPath(): string
    {
        return $this->executionPath;
    }

    /**
     * Устанавливает напрямую значение в защищенный параметр index, без манипуляций с ним.
     *
     * @param string $_index
     *
     * @return void
     */
    public function fakeSetParameterIndex(string $_index): void
    {
        $this->index = $_index;
    }

    /**
     * Устанавливает напрямую значение в защищенный параметр title, без манипуляций с ним.
     *
     * @param string $_title
     *
     * @return void
     */
    public function fakeSetParameterTitle(string $_title): void
    {
        $this->title = $_title;
    }

    /**
     * Устанавливает напрямую значение в защищенный параметр executionStatus, без манипуляций с ним.
     *
     * @param string $_executionStatus
     *
     * @return void
     */
    public function fakeSetParameterExecutionStatus(string $_executionStatus): void
    {
        $this->executionStatus = $_executionStatus;
    }

    /**
     * Устанавливает напрямую значение в защищенный параметр executionPath, без манипуляций с ним.
     *
     * @param string $_executionPath
     *
     * @return void
     */
    public function fakeSetParameterExecutionPath(string $_executionPath): void
    {
        $this->executionPath = $_executionPath;
    }
}
