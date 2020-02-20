<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('json_decode', [$this, 'jsonDecode']),
            new TwigFilter('remove_accents', [$this, 'removeAccents']),
        ];
    }

    public function jsonDecode($text, $forceAssociative = false)
    {
        return json_decode($text, $forceAssociative);
    }

    public function removeAccents($text)
    {
        return \App\Utils\StringHelper::removeAccents($text);
    }
}

