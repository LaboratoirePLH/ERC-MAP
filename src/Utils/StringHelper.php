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
}
