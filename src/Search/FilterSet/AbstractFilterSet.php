<?php

namespace App\Search\FilterSet;

abstract class AbstractFilterSet
{
    protected $data;
    protected $sortedData;

    public function __construct(array $data)
    {
        $this->data       = $data;
        $this->sortedData = [
            'sources'      => [],
            'attestations' => [],
            'elements'     => [],
        ];
        $sources = [];
        $attestations = [];
        $elements = [];
        foreach($data as $e){
            $targetArray = strtolower($e->getEntite()) . 's';
            $this->sortedData[$targetArray][$e->getId()] = $e;
        }
    }
}
