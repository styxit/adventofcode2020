<?php

namespace Days\Day8;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    /**
     * Find the solution.
     */
    public function execute()
    {
        $program = $this->reloadProgram();

        // Execute the program.
        $program->execute();
        $this->part1 = $program->getAccumulator();

        // Get list of source code line indexes that can be reprogrammed.
        $reprogrammableLines = $program->getReprogrammableLineIndexes();

        /*
         * Change program line one at a time and try to run the program.
         * If the program exits succesfully, the fix has been found.
         */
        foreach($reprogrammableLines as $lineIdex) {
            $program = $this->reloadProgram();
            $program->changeLine($lineIdex);

            // If the program executes nicely, it has been fixed. 
            if ($program->execute()) {
                $this->part2 = $program->getAccumulator();
            }
        }
    }

    private function reloadProgram()
    {
        $program =  new Program();

        foreach ($this->input->lines as $line) {
            // Clean the input line.
            $line = str_replace('+', '', $line);

            list($operation, $argument) = explode(' ', trim($line));

            // Add line of code to the program.
            $program->addCode($operation, (int) $argument);
        }

        return $program;
    }
}
