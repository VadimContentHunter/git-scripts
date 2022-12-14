<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\Tests\src\fakes;

use vadimcontenthunter\GitScripts\model\StandardTask;

class StandardTaskFake extends StandardTask
{
    /**
     * Возвращает значение защищенного параметра index без манипуляций с ним.
     *
     * @return string
     */
    public function getParameterIndex(): string
    {
        return $this->index;
    }

    /**
     * Возвращает значение защищенного параметра title без манипуляций с ним.
     *
     * @return string
     */
    public function getParameterTitle(): string
    {
        return $this->title;
    }

    /**
     * Возвращает значение защищенного параметра executionStatus без манипуляций с ним.
     *
     * @return string
     */
    public function getParameterExecutionStatus(): string
    {
        return $this->executionStatus;
    }

    /**
     * Устанавливает напрямую значение в защищенный параметр index, без манипуляций с ним.
     *
     * @param string $_index
     *
     * @return void
     */
    public function setParameterIndex(string $_index): void
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
    public function setParameterTitle(string $_title): void
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
    public function setParameterExecutionStatus(string $_executionStatus): void
    {
        $this->executionStatus = $_executionStatus;
    }
}
