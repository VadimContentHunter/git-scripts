<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\Tests\src\providers;

class StandardTaskProviders
{
    public static function providerIndexes(): array
    {
        return [
            'An empty array of indices' => [
                [],
                '1',
            ],
        ];
    }
}
