<?php

namespace App\Entity;

use InvalidArgumentException;

abstract class AbstractEntity
{

    /**
     * Default toString method, returning the Entity name followed by the record ID
     */
    public function __toString(): string
    {
        $rc = new \ReflectionClass($this);
        return $rc->getShortName() . " #" . $this->getId();
    }

    /**
     * Function to sanitize HTML
     */
    public function sanitizeHtml($string): ?string
    {
        $functions = [
            "\App\Utils\HTMLCleaner::sanitizeOpenXML",
            "\App\Utils\HTMLCleaner::sanitizeHtmlEncoding",
            "\App\Utils\HTMLCleaner::sanitizeHtmlNewLines",
            "\App\Utils\HTMLCleaner::sanitizeHtmlTags",
            "\App\Utils\HTMLCleaner::sanitizeHtmlAttributes"
        ];
        foreach ($functions as $f) {
            $string = $f($string);
        }
        return $string;
    }
}
