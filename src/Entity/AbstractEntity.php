<?php

namespace App\Entity;

abstract class AbstractEntity {
    public function __toString(): string
    {
        $rc = new \ReflectionClass($this);
        return $rc->getShortName()." #".$this->getId();
    }
}