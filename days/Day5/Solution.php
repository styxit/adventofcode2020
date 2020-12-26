<?php

namespace Days\Day5;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{

    /**
     * Find the solution.
     */
    public function execute()
    {
        // Get an array of all boardingpasses.
        $boardingpasses = $this->loadBoadingpasses();

        // Turn boardingpasses into a list of seatIDs.
        $boardingpassIds = array_map(
            fn($boardingpass) => $boardingpass->getSeatId(),
            $boardingpasses
        );

        // Get min and max seat ID.
        $maxSeatId = max($boardingpassIds);
        $minSeatId = min($boardingpassIds);
    
        // Max seat ID is the answer to part 1.
        $this->part1 = $maxSeatId;

        // Get a list of all seatsIDs on the plane.
        $seats = $this->generateSeats(
            128, // Plane has 128 rows.
            8 // Each row has 8 seats.
        );

        /*
         * Remove unavailable seats at the start and end of the plane.
         * - Remove seats bigger than the highest boardingpass seat ID.
         * - Remove seats smaller than the lowest boardingpass seat ID.
         */
        $filteredSeats = array_filter(
            $seats,
            fn($seatId) => ($seatId >= $minSeatId && $seatId <= $maxSeatId),
        );

        /*
         * Check which seats are missing from the boardingpass list.
         * Should be exactly one, our seat.
         */
        $seatDiff = array_diff($filteredSeats, $boardingpassIds);

        // Return the missing seat ID.
        $this->part2 = reset($seatDiff);
    }

    private function generateSeats($rows, $cols)
    {
        $rows = range(0, $rows-1);

        $seats = array_map(
            fn($rowNr) => array_map(
                fn($colNr) => ($rowNr*8) + $colNr,
                range(0, $cols-1)
            ),
            $rows
        );

        return call_user_func_array('array_merge', $seats);
    }

    private function loadBoadingpasses()
    {
        $boardingpasses = [];
        foreach ($this->input->lines as $line) {
            $boardingpass = new Boardingpass($line);

            $boardingpasses[] = $boardingpass;
        }

        return $boardingpasses;
    }
}
