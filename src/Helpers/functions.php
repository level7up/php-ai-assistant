<?php

use Illuminate\Support\Facades\App;

if (!function_exists('is_rtl')) {
    /**
     * Determine if the current application locale is RTL.
     *
     * @return bool
     */
    function is_rtl(): bool
    {
        $rtlLocales = ['ar', 'fa', 'he', 'ur'];

        // Use the facade directly to avoid dependency issues
        $locale = App::getLocale();

        return in_array($locale, $rtlLocales);
    }
}
