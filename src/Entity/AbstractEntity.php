<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
            "\App\Utils\HTMLCleaner::sanitizeHtmlAttributes",
            "\App\Utils\StringHelper::normalizeDiacritics"
        ];
        foreach ($functions as $f) {
            $string = $f($string);
        }
        return $string;
    }

    /**
     * Reapply all collection-based relations
     *
     * @param array $relations Array of relations with 3 elements : name of property, name of getter, name of adder
     * @return void
     * @see AbstractEntity::reapplyRelation()
     */
    public function reapplyRelations(array $relations): void
    {
        foreach ($relations as $relation) {
            list($property, $getter, $adder) = $relation;
            $this->reapplyRelation($property, $getter, $adder);
        }
    }

    /**
     * Reapply a single collection based relation by creating a new collection instance and adding the known items to it
     * @param string $property The property name (e.g. `items`)
     * @param string $getter The getter method name (e.g. `getItems`)
     * @param string $adder The adder method name (e.g. `addItem`)
     */
    public function reapplyRelation(string $property, string $getter, string $adder): void
    {
        $collection = $this->$getter();
        $this->$property = new ArrayCollection();
        if (!$collection->isEmpty()) {
            foreach ($collection as $item) {
                $this->$adder($item);
            }
        }
    }
}
