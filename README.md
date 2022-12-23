# GIT-SCRIPTS

Идея этого проекта заключается в реализации последовательного запуска задач, перед отправкой в удаленный репозиторий.

## Примеры

### Пример 1. Линейная цепочка задач

1. Создадим точку входа для вызова скрипта, где будут
реализованы задачи. В качестве точки входа будет **файл git-scripts.php**

2. Реализуем Git hook **pre-push**, в котором запускается точка входа _git-scripts.php_

    ```git
    #!/usr/bin/sh

    php 'git-scripts.php'
    ```

3. Реализуем в файле _git-scripts.php_ список задач `new Tasks()`, добавив задачу **_phpcs_** на выполнение

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

### Пример 2. Разветвленная цепочка задач

1. Создадим точку входа для вызова скрипта, где будут
реализованы задачи. В качестве точки входа будет **файл git-scripts.php**

2. Реализуем Git hook **pre-push**, в котором запускается точка входа _git-scripts.php_

    ```git
    #!/usr/bin/sh

    php 'git-scripts.php'
    ```

3. Создадим еще один скрипт **_branch-search.php_**, который будет определять является указанная ветка активной или нет.
В случае если ветка является активной скрипт закончит работу с кодом 0.

    ```php
    <?php

    declare(strict_types=1);

    if (!isset($argv[1])) {
        print_r(PHP_EOL . 'Аргумент не указан! Укажите ветку для поиска.' . PHP_EOL);
        exit(2);
    }

    $desired_branch = $argv[1];
    $output = null;
    $retval = null;

    exec('git branch', $output, $retval);

    foreach ($output as $key => $branch) {
        if (preg_match('~^\*\s' . $desired_branch . '~iu', $branch)) {
            echo "Ветка выбрана ($branch)" . PHP_EOL;
            exit(0);
        } else {
            echo "Ветка НЕ выбрана ($branch)"  . PHP_EOL;
        }
    }

    echo "Указанная ветка ($desired_branch) не выбрана."  . PHP_EOL;
    exit(1);
    ```

4. Реализуем в файле _git-scripts.php_ список задач `new Tasks()`, добавив задачу **_branch-search_** с параметром искомой ветки _dev-vadim_, в случае успешного выполнения создается новый список задач в данном случае добавлена в этот список одна задача **_phpcs_**. Вторая задача не будет выполнена в случае успешной 1 задачи из ветки branch-search (самая первая задача), так как она заканчивает работу командой `exit(0);`.

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
                ->setTitle('branch-search')
                ->setExecutionPath('.\branch-search.php')
                ->addArgumentsAsString('dev-vadim')
                ->setWhenExecuteTrue(function (StandardTask $thisTask) use ($myLogger) {
                    $myLogger->info('Началась ветвь от [' . $thisTask->getTitle() . ']');
                    (new Tasks())
                        ->addTaskList(
                            (new StandardTask($myLogger))
                                ->setTitle('phpcs 1')
                                ->setExecutionPath('./vendor/bin/phpcs')
                        )
                        ->result(function ($result) {
                            if ($result) {
                                exit(0);
                            }
                        });
                })
        )
        ->addTaskList(
            (new StandardTask($myLogger))
                ->setTitle('phpcs 2')
                ->setExecutionPath('./vendor/bin/phpcs')
        )
        ->start() ? exit(0) : exit(1);
    ```

    ![результат работы](https://github.com/VadimContentHunter/git-scripts/blob/ac0fef171368f3d663174e5a222e96d8f4183e16/readme/r1.PNG)

    Можно продолжить выполнения основной цыпочки задачи, если в примере выше заменить задачу `phpcs 1` на

    ```php
    (new StandardTask($myLogger))
        ->setTitle('ScriptReturn5Fake')
        ->setExecutionPath('.\tests\src\fakes\ScriptReturn5Fake.php')
    ```

    Реализация **_ScriptReturn5Fake_** файла

    ```php
    <?php

    exit(5);
    ```

    ![результат работы](https://github.com/VadimContentHunter/git-scripts/blob/ac0fef171368f3d663174e5a222e96d8f4183e16/readme/r2.PNG)
