<?php

namespace Tests\Days;

use PHPUnit\Framework\TestCase;

abstract class AbstractDayTest extends TestCase
{
    public function testOutcome()
    {
        // Load solution for a day.
        $day = new $this->class();

        // Execute day solution.
        $day->execute();

        // Assert outcome.
        $this->assertSame($this->solutionPart1, $day->part1);
        $this->assertSame($this->solutionPart2, $day->part2);
    }
}