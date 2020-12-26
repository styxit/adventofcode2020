<?php

namespace Days\Day4;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{

    private $requiredFields = [
        'byr',  // Birth Year.
        'iyr', // Issue Year.
        'eyr', // Expiration Year.
        'hgt', // Height.
        'hcl', // Hair Color.
        'ecl', // Eye Color.
        'pid', // Passport ID.
   //     'cid', // Country ID.
    ];

    private $passports = [];
    

    /**
     * Find the solution.
     */
    public function execute()
    {
        $this->loadPassports();
        
        $completePassportCount = 0;
        $validPassportCount = 0;
        foreach($this->passports as $passport) {
            if ($this->checkPassportHasAllFields($passport)) {
                $completePassportCount++;

                if ($this->validatePassport($passport)) {
                    $validPassportCount++;
                }
            }
        }

        $this->part1 = $completePassportCount;
        $this->part2 = $validPassportCount;
    }

    private function validatePassport($passport)
    {
        foreach($passport as $key => $value) {
            $validatorName = 'validate'.ucfirst($key).'Field';

            // Run validation method, return false when not valid.
            if (!$this->$validatorName($value)) {
                return false;
            };
        }

        return true;
    }

    /**
     * ecl (Eye Color) - exactly one of: amb blu brn gry grn hzl oth.
     */
    private function validateEclField($value)
    {
        return in_array($value, ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth']);
    }

    /**
     * hgt (Height) - a number followed by either cm or in:
     * - If cm, the number must be at least 150 and at most 193.
     * - If in, the number must be at least 59 and at most 76.
     */
    private function validateHgtField($value)
    {
        if (str_ends_with($value, 'cm')) {
            $centimeters = (int) str_replace('cm', '', $value);
            return $centimeters >= 150 && $centimeters <= 193;
        } else {
            $inches = (int) str_replace('in', '', $value);
            return $inches >= 59 && $inches <= 76;
        }
    }

    /**
     * pid (Passport ID) - a nine-digit number, including leading zeroes.
     */
    private function validatePidField($value)
    {
        return preg_match('/^[0-9]{9}$/', trim($value));
    }

    /**
     * hcl (Hair Color) - a # followed by exactly six characters 0-9 or a-f.
     */
    private function validateHclField($value)
    {
        return preg_match('/^\#[0-9a-f]{6}$/', trim($value));
    }

    /**
     * four digits; at least 1920 and at most 2002
     */
    private function validateByrField($value)
    {
        return $this->validateYear($value, 1920, 2002);
    }

        /**
     * iyr (Issue Year) - four digits; at least 2010 and at most 2020.
     * 
     */
    private function validateIyrField($value)
    {
        return $this->validateYear($value, 2010, 2020);
    }

    /**
     * eyr (Expiration Year) - four digits; at least 2020 and at most 2030.
     */
    private function validateEyrField($value)
    {
        return $this->validateYear($value, 2020, 2030);
    }

    private function validateYear($value, $min, $max)
    {
        if (strlen($value) != 4 || !is_numeric($value)) {
            return false;
        }

        return $value >= $min && $value <= $max;
    }

    /**
     * cid (Country ID) - ignored, missing or not.
     */
    private function validateCidField($value)
    {
        return true;
    }


    private function checkPassportHasAllFields($passport)
    {
        $missingFields = array_diff($this->requiredFields, array_keys($passport));

        return empty($missingFields);
    }
    

    private function loadPassports()
    {
        $passport = [];
        foreach ($this->input->lines as $line) {
            // Save passport and start a new one.
            if (trim($line) == '') {
                $this->passports[] = $passport;
                $passport = [];
                continue;
            }

            $sections = explode(' ', $line);

            foreach ($sections as $section) {
                list($key, $value) = explode(':', $section);
                $passport[$key] = $value;
            }
        }


        if (!empty($passport)) {
            $this->passports[] = $passport;
        }
    }
}
