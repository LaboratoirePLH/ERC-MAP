<?php

namespace App\Entity;

abstract class AbstractEntity {
    /**
     * Defualt toString method, returning the Entity name followed by the record ID
     */
    public function __toString(): string
    {
        $rc = new \ReflectionClass($this);
        return $rc->getShortName()." #".$this->getId();
    }

    protected function sanitizeOpenXMLString($string): ?string
    {
        if(is_null($string)){ return null; }
        $start = "<!--StartFragment-->";
        $end = "<!--EndFragment-->";
        if (strpos($string, $start) && strpos($string, $end)) { // checks existence of StartFragment
            $startCharCount = strpos($string, $start) + strlen($start);
            $firstSubStr = substr($string, $startCharCount, strlen($string));
            $endCharCount = strpos($firstSubStr, $end);
            if ($endCharCount == 0) {
                $endCharCount = strlen($firstSubStr);
            }
            return substr($firstSubStr, 0, $endCharCount);
        } else {
            return $string;
        }
    }
}