<?php

namespace Days\Day3;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    const TREE = '#';

    private $slopes = [
        [1, 1],
        [1, 3], // The slope for solution to part 1.
        [1, 5],
        [1, 7],
        [2, 1],
    ];
    

    private $grid = [];

    private $position = [0, 0];

    /**
     * Find the solution.
     */
    public function execute()
    {
        $this->loadGrid();

        // Walk each slope and count the trees.
        $treeCount = array_map(
            fn($slope) => $this->walkSlope($slope[0], $slope[1]),
            $this->slopes
        );

        // Pick the tree count for the required slope as solution for part 1.
        $this->part1 = $treeCount[1];

        // multiply all tree counts for the solution to part 2.
        $this->part2 = array_product($treeCount);
    }
    

    private function walkSlope($down, $right)
    {
        // Start from the top.
        $this->position = [0, 0];
        $treeCount = 0;

        while (true) {
            // Advance the current position one step.
            $this->step($down, $right);

            // If the current position contains a tree, increment tree counter.
            if ($this->detectTree()) {
                $treeCount++;
            }

            // Stop walking when the bottom of the grid is reached.
            if ($this->reachedEnd()) {
                break;
            }
        }

        return $treeCount;
    }

    private function detectTree()
    {
        $row = $this->grid[$this->position[0]];
        $column = $this->position[1];

        /*
         * If the current position exceeds the row lenth.
         * continue again from the front of the row, as many times as needed.
         */
        if ($column+1 > count($row)) {
            // Use modulo, this gets you a number that fits in the row.
            $column = ($column) % count($row);
        }

        return $row[$column] === self::TREE;
    }

    private function step($down, $right)
    {
        $this->position[0] += $down;
        $this->position[1] += $right;
    }

    /**
     * Figure out if the current position is at the bottom of the grid.
     */
    private function reachedEnd()
    {
        return $this->position[0]+1 === count($this->grid);
    }

    private function loadGrid()
    {
        foreach ($this->input->lines as $line) {
            $this->grid[] = str_split($line);
        }
    }
}
