<?php

namespace Days\Day5;

use Styxit\AbstractSolution;

class Boardingpass
{
    private $seatCode;

    private $seatId;

    private $row;
    private $column;

    public function __construct($seatCode)
    {
        $this->seatCode = $seatCode;

        $this->calculateSeat();

        $this->setSeatId();
    }

    public function getSeatId()
    {
        return $this->seatId;
    }

    private function setSeatId()
    {
        $this->seatId = ($this->row * 8) + $this->column;
    }

    private function calculateSeat()
    {
        $minRow = 0;
        $maxRow = 127;
        $minCol = 0;
        $maxCol = 7;

        $seatOptions = [
            'rows' => range($minRow, $maxRow),
            'columns' => range($minCol, $maxCol),
        ];      

        $seat = array_reduce(str_split($this->seatCode), [$this, 'reduceSeat'], $seatOptions);
        
        $this->row = reset($seat['rows']);
        $this->column = reset($seat['columns']);
    }

    public function reduceSeat($seatOptions, $character)
    {
        // Handle rows.
        if (in_array($character, ['F', 'B'])) {
            $split = array_chunk($seatOptions['rows'], count($seatOptions['rows'])/2);            
            $seatOptions['rows'] = $character == 'F' ? $split[0]: $split[1];
        }
        // Handle columns
        else {
            $split = array_chunk($seatOptions['columns'], count($seatOptions['columns'])/2);
            $seatOptions['columns'] = $character == 'L' ? $split[0]: $split[1];
        }
        
        return $seatOptions;
    }
}
