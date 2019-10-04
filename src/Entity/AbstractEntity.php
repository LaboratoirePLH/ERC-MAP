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

    protected function sanitizeWysiwygString($string): ?string
    {
        // Catch null strings and don't process them
        if(is_null($string)){ return null; }

        // Replace <p> and <div> with a <br/>
        $string = preg_replace("/<p[^>]*?>/", "", $string);
        $string = str_replace("</p>", "<br/>", $string);
        $string = preg_replace("/<div[^>]*?>/", "", $string);
        $string = str_replace("</div>", "<br/>", $string);

        // Clean <br/> tags
        $string = preg_replace("/(<br[^>]*?>)/", "<br/>", $string);

        // Remove all tags except the ones allowed
        $string = strip_tags($string, '<strong><em><u><s><ub><sup><span><br>');

        do {
            $before = $string;
            // Replace ampersand and unbreakable space html entities
            $string = str_replace("&amp;", "&", $string);
            $string = str_replace("&nbsp;", " ", $string);
            // Remove duplicate <br/>
            $string = preg_replace("/(\s*<br\/>\s*){2,}/", "<br/>", $string);
            // Remove leading and trailing <br/> and spaces
            $string = preg_replace("/^(<br\/>)+/", "", $string);
            $string = preg_replace("/(<br\/>)+$/", "", $string);
            $string = trim($string);
        } while($string !== $before); // Repeat until no changes are done

        // Remove fonts other than Arial or IFAOGreek
        $string = preg_replace('/"font-family:(?!IFAOGreek)[^"]+"/', '"font-family:Arial"', $string);

        return $string;
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