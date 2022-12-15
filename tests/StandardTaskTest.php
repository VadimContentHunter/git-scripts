<?php

declare(strict_types=1);

namespace vadimcontenthunter\GitScripts\Tests;

use PHPUnit\Framework\TestCase;
use vadimcontenthunter\GitScripts\TaskProgressLevel;
use vadimcontenthunter\GitScripts\Tests\src\fakes\StandardTaskFake;

class StandardTaskTest extends TestCase
{
    protected StandardTaskFake $standardTaskFake;

    public function setUp(): void
    {
        $this->standardTaskFake = new StandardTaskFake();
    }

    public function test_constructor_withoutParameters_shouldChangeTheParameterToWaiting(): void
    {
        $this->assertEquals(
            TaskProgressLevel::WAITING,
            $this->standardTaskFake->fakeGetParameterExecutionStatus()
        );
    }

    public function test_setIndex_withIndexParameter_shouldChangeThe(array $IndexParameter, string $expectableResult): void
    {
        $this->standardTaskFake->setIndex($IndexParameter);
        $this->assertEquals(
            $expectableResult,
            $this->standardTaskFake->fakeGetParameterIndex()
        );
    }
}
