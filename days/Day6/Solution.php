<?php

namespace Days\Day6;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    /**
     * Find the solution.
     */
    public function execute()
    {
        // Get the Yes answers per group.
        $groupAnswers = $this->loadAnswersInGroups();

        /*
         * Turn group answers into array of unique answers.
         * Merge all answers in the group, then apply unique filter.
         */
        $groupAnswersUnique = array_map(
            fn($group) => array_unique(call_user_func_array('array_merge', $group)),
            $groupAnswers
        );

        // Answer part 1:  Sum unique counts.
        $this->part1 = array_reduce(
            $groupAnswersUnique,
            fn($count, $group) => $count + count($group),
            0
        );

        /*
         * Turn group answers into array of mutual answers.
         * If there was only one person in the group, consider all their answers as mutual.
         */
        $groupAnswersMutual = array_map(
            fn($group) => count($group) === 1 ? reset($group) : array_intersect(...$group),
            $groupAnswers
        );

        // Answer part 2: Sum mutual counts.
        $this->part2 = array_reduce(
            $groupAnswersMutual,
            fn($count, $group) => $count + count($group),
            0
        );
    }

    private function loadAnswersInGroups()
    {
        $answers = [];

        $group = [];

        foreach ($this->input->lines as $line) {
            // Save group and start a new one.
            if (trim($line) == '') {
                $answers[] = $group;
                $group = [];
                continue;
            }

            $group[] = str_split($line);
        }

        if (!empty($line)) {
            $answers[] = $group;
        }

        return $answers;
    }
}
