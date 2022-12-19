<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts;

/**
 * Описывает уровни выполнения задачи.
 *
 * @package   GitScripts
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class TaskProgressLevel
{
    public const WAITING = 'waiting';

    public const DONE = 'done';

    public const PROGRESS = 'progress';

    public const ABORTED = 'aborted';

    public const ERROR = 'error';

    public const NOT_IMPLEMENTED = 'not_implemented';
}
