<?php

namespace App\Helpers;

class LocaleEnum
{
    const EN = 'en';
    const FR = 'fr';
    const ES = 'es';
    const ID = 'id';
    const KM = 'km';
    const ZHHANZ = 'zh-Hans';

    public static function getString($value)
    {
        switch ($value) {
            case 'en':
                return 'English';
            case 'fr':
                return 'French';
            case 'es':
                return 'Spanish';
            case 'id':
                return 'Bahasa Indonesia';
            case 'km':
                return 'Khmer';
            case 'zh-Hans':
                return 'Chinese (Simplified)';
        }
    }
}
