# GIT-SCRIPTS

Идея этого проекта заключается в реализации последственного запуска задач, перед оправкой в удаленный репозиторий.

## Примеры

### Пример 1

1. Создадим точку входа для вызова скрипта, где будут
реализованы задачи. В качестве точки входа будет **файл git-scripts.php**

2. Реализуем Git hook **pre-push**, в котором запускается точка входа _git-scripts.php_

#### Файл pre-push

```git
#!/usr/bin/sh

php 'git-scripts.php'
```

#### Файл git-scripts.php

```php
<?php

declare(strict_types=1);

require_once __DIR__ . "/vendor/autoload.php";

use vadimcontenthunter\GitScripts\Tasks;
use vadimcontenthunter\GitScripts\model\StandardTask;
use vadimcontenthunter\MyLogger\MyLogger;
use vadimcontenthunter\MyLogger\modules\ConsoleLogger;
use vadimcontenthunter\MyLogger\formatters\BaseFormatter;

$myLogger = new MyLogger(new ConsoleLogger(BaseFormatter::class));

(new Tasks())
    ->addTaskList(
        (new StandardTask($myLogger))
            ->setTitle('phpcs')
            ->setExecutionPath('./vendor/bin/phpcs')
    )
    ->start() ? exit(0) : exit(1);
```
