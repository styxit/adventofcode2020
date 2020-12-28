<?php

namespace Days\Day8;

use Styxit\AbstractSolution;

class Program
{
    private array $sourceCode = [];

    private int $accumulator = 0;

    private int $currentLine = 0;

    /**
     * Add a line of code to the program.
     */
    public function addCode(string $operation, int $argument)
    {
        $this->sourceCode[] = [
            'operation' => $operation,
            'argument' => $argument,
            'executed' => false,
        ];
    }

    public function getAccumulator()
    {
        return $this->accumulator;
    }

    /**
     * Get indexes for source code lines that can be reprogrammed.
     * The 'nop' and 'jmp' operations can be reprogrammed.
     */
    public function getReprogrammableLineIndexes()
    {
        $reprogrammableLines = array_filter(
            $this->sourceCode,
            fn($line) => in_array($line['operation'], ['nop', 'jmp'])
        );

        return array_keys($reprogrammableLines);
    }

    /**
     * Execute lines untill reaching a line that has already been executed.
     * Changing the accumulator in the progress.
     * 
     * @return boolean True when at the end of the program, false when trying to run an already executed line.
     */
    public function execute()
    {
        while(true) {
            // Get the line to execute.
            $line = $this->sourceCode[$this->currentLine];

            // Stop if this line has already been executed.
            if ($line['executed']) {
                return false;
            }

            // By default continue to the next line after executing the current line.
            $nextLine = $this->currentLine + 1;

            if ($line['operation'] == 'nop') {
                // Do nothing, continue to the nextline.
            }
            elseif ($line['operation'] == 'acc') {
                // Increment accumulator, then continue to the next line.
                $this->accumulator += $line['argument'];
            }
            elseif ($line['operation'] == 'jmp') {
                // Jump to a different line.
            $this->sourceCode[$this->currentLine]['executed'] = true;
                $nextLine = $this->currentLine + $line['argument'];
            }

            // Mark the current line as executed.
            $this->sourceCode[$this->currentLine]['executed'] = true;

            // Stop if this is the end of the program.
            if ($this->currentLine+1 == count($this->sourceCode)) {
                return true;
            }

            // Prepare the next line that should be executed.
            $this->currentLine = $nextLine;
        }
    }

     /**
      * Change a line in the program.
      *
      * @param int $lineIdex The index of line to change.
      * @return void
      */
    public function changeLine($lineIdex)
    {
        if ($this->sourceCode[$lineIdex]['operation'] == 'nop') {
            $this->sourceCode[$lineIdex]['operation'] = 'jmp';
        }
        elseif ($this->sourceCode[$lineIdex]['operation'] == 'jmp') {
            $this->sourceCode[$lineIdex]['operation'] = 'nop';
        }
    }
}