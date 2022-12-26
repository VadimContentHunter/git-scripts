<?php

if (!isset($argv[1]) || !is_numeric($argv[1])) {
    exit(1);
}

exit((int)$argv[1]);
