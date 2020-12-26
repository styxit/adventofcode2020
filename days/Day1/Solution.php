<?php

namespace Days\Day1;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    /**
     * @var int Target number to find sum for.
     */
    private $targetNumber = 2020;

    /**
     * Find the solution.
     */
    public function execute()
    {
        // Find the 2 numbers that sum 2020.
        $sumNumbers = $this->findSum();
        $this->part1 = array_product($sumNumbers);

        // Find the 3 numbers that sum 2020.
        $sumNumbers = $this->findSum(true);
        $this->part2 = array_product($sumNumbers);

        return;
    }

    /**
     * Find the 2 or 3 numbers that sum 2020.
     *
     * @return int[] Array with the numbers that sum 2020.
     */
    private function findSum($threeSteps = false)
    {
        foreach ($this->input->lines as $line) {
            $firstNumber = (int) $line;

            foreach ($this->input->lines as $otherLine) {
                $secondNumber = (int) $otherLine;

                $sum2 = $firstNumber + $secondNumber;

                if ($sum2 === $this->targetNumber && $threeSteps === false) {
                    return [$firstNumber, $secondNumber];
                }

                // Early return; if the two numbers already exceed the target, there is no need to try it with a third number.
                if ($sum2 > $this->targetNumber) {
                    continue;
                }

                // Add 3rd loop.
                foreach ($this->input->lines as $thirdLine) {
                    $thirdNumber = (int) $thirdLine;
    
                    $sum3 = $sum2 + $thirdNumber;
    
                    if ($sum3 === $this->targetNumber) {
                        return [$firstNumber, $secondNumber, $thirdNumber];
                    }
                }
            }
        }

        return false;
    }
}
