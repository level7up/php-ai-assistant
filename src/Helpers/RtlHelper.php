<?php

namespace Level7up\AIAssistant\Helpers;

use Illuminate\Support\Facades\App;

class RtlHelper
{
    /**
     * RTL language codes.
     *
     * @var array
     */
    protected static array $rtlLocales = ['ar', 'fa', 'he', 'ur'];

    /**
     * Determine if the current application locale is RTL.
     *
     * @return bool
     */
    public static function isRtl(): bool
    {
        return in_array(App::getLocale(), static::$rtlLocales);
    }
}
