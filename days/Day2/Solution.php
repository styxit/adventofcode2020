<?php

namespace Days\Day2;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    /**
     * Find the solution.
     */
    public function execute()
    {
        
        $this->part1 = 0;
        $this->part2 = 0;
        
        foreach ($this->input->lines as $line) {
            // Explode line into two policy numbers, the letter and the password.
            list($policyNumberOne, $policyNumberTwo, $letter, $password) = preg_split('/[-,:\ ]/', $line, -1, PREG_SPLIT_NO_EMPTY);

            $this->policyOne($policyNumberOne, $policyNumberTwo, $letter, $password);
            $this->policyTwo($policyNumberOne, $policyNumberTwo, $letter, $password);
        }
    }

    private function policyOne($minOccurrence, $maxOccurrence, $letter, $password)
    {
        // Count how many times the letter appeared in the password.
        $letterCount = substr_count($password, $letter);

        // Validate how many times the letter appeared.
        if ($letterCount >= $minOccurrence && $letterCount <= $maxOccurrence) {
            // This password is valid.
            $this->part1++;
        }
    }

    private function policyTwo($positionOne, $positionTwo, $letter, $password)
    {
        // Minus one, to use 0-based index.
        $letterOne = $password[$positionOne-1];
        $letterTwo = $password[$positionTwo-1];

        // Letter one OR two must be the right letter, not both.
        if ($letterOne === $letter xor $letterTwo === $letter) {
            // This password is valid.
            $this->part2++;
        }
    }
}
