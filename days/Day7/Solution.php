<?php

namespace Days\Day7;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    const GOLD = 'shiny gold';

    /**
     * Array of bags with their required content.
     * Keyed by color, values is the required bags + quantity.
     * 
     * [
     *   'blue' => [
     *     'yellow' => 4,
     *     'red' => 2,
     *   ]
     * ]
     */
    private $bags = [];

    /**
     * Find the solution.
     */
    public function execute()
    {
        // Load bags requirements from the input.
        $this->loadAllowedBagContents();

        $this->part1 = $this->bagsCanContainGoldCount();

        $this->part2 = $this->recursiveContentCount(self::GOLD);
    }

    /**
     * Count (recursive) how many bags should be in a specific bag.
     * 
     * This is a recursive function.
     * This modifies the $bags attribute.
     */
    private function recursiveContentCount($bagColor)
    {
        // Get bag.
        $bag = $this->bags[$bagColor];

        // If the count for this bag has already been calculated, return the count.
        if (is_int($bag)) {
            return $bag;
        }

        // Return 0 if this bag does not contain other bags.
        if (empty($bag)) {
            return 0;
        }

        // Keep track of how many bags this bag contains.
        $count = 0;
        
        // Loop direct bag contents.
        foreach($bag as $color => $quantity) {
            // Count how many times this bag must be present ...
            $count += $quantity;

            // ... + The contents of those bags.
            $count += ($quantity * $this->recursiveContentCount($color));
        }

        // Update the baglist for later reference.
        $this->bags[$bagColor] = $count;

        return $count;   
    }

    private function bagsCanContainGoldCount()
    {
        // Start with a list of bag colors that need to be checked if they can contain gold.
        $uncheckedBags = array_keys($this->bags);

        // Start with an empty list of bags that can contain gold.
        $bagsCanContainGold = [];

        // Loop untill all bags have been checked.
        while(!empty($uncheckedBags)) {

            foreach($uncheckedBags as $i => $bagColor) {
                $bagContents = array_keys($this->bags[$bagColor]);

                // If the bag can not contain other bags this bag has been checked.
                if(empty($bagContents)) {
                    unset($uncheckedBags[$i]);
                    continue;
                }

                // If this bag can directly contain gold it has been checked and can contain gold.
                elseif (in_array(self::GOLD, $bagContents)) {
                    $bagsCanContainGold[] = $bagColor;
                    unset($uncheckedBags[$i]);
                    continue;
                }
                
                // If this bag can contain another bag that contains gold, it has been checked and can contain gold.
                elseif(!empty(array_intersect($bagContents, $bagsCanContainGold))){
                    $bagsCanContainGold[] = $bagColor;
                    unset($uncheckedBags[$i]);
                    continue;
                }

                // See which of these bags have been checked.
                $checkedBags = array_diff($bagContents, $uncheckedBags);
                // If all of the bags have been checked, this bag is done and can not contain gold.
                if($checkedBags == $bagContents) {
                    unset($uncheckedBags[$i]);
                }
            }
        }

        return count($bagsCanContainGold);
    }

    private function loadAllowedBagContents()
    {
        foreach ($this->input->lines as $line) {
            // Clean the input line.
            $line = str_replace(' bags', '', $line);
            $line = str_replace(' bag', '', $line);
            $line = str_replace('.', '', $line);

            list($color, $contents) = explode(' contain ', $line);
            $contents = explode(',', $contents);

            $this->bags[$color] = [];

            foreach($contents as $bagCount) {
                list($count, $bag) = explode(' ', trim($bagCount), 2);

                if ((int) $count > 0) {
                    $this->bags[$color][$bag] = (int) $count;
                }
            }
        }
    }
}
