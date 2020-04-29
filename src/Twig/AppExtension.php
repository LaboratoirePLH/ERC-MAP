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
            new TwigFilter('ellipsis', [$this, 'ellipsis']),
            new TwigFilter('operatorToString', [$this, 'operatorToString']),
            new TwigFilter('snake_case', [$this, 'snake_case']),
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

    public function ellipsis($text, $length)
    {
        return \App\Utils\StringHelper::ellipsis($text, $length);
    }

    public function operatorToString($text)
    {
        return \App\Utils\StringHelper::operatorToString($text);
    }

    public function snake_case($text)
    {
        return \App\Utils\StringHelper::snakeCase($text);
    }
}
