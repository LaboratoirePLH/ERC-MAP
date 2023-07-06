<?php

namespace App\Utils;

class TransliteratorSingleton
{
    /**
     * @var Transliterator
     * @access private
     * @static
     */
    private static $_instance = null;

    public static function transliterate(string $input): string
    {
        if (is_null(self::$_instance)) {
            self::$_instance = \Transliterator::createFromRules(':: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD);
        }
        return self::$_instance->transliterate($input);
    }
}
