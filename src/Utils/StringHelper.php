<?php

namespace App\Utils;

class StringHelper
{
    public static function removeAccents(string $input): string
    {
        $transliterator = \Transliterator::createFromRules(':: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD);
        return $transliterator->transliterate($input);
    }

    public static function normalizeDiacritics(string $input): string
    {
        return \Normalizer::normalize($input, \Normalizer::FORM_C);
    }

    public static function ellipsis(string $input, int $length = 200): string
    {
        // TODO : improve function to prevent unclosed or truncated HTML tags (<spa...)
        // see : https://stackoverflow.com/questions/8933491/php-substr-but-keep-html-tags
        return strlen($input) > $length ? (substr($input, 0, $length) . '&hellip;') : $input;
    }
}
