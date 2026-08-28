<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Main Website Configuration
    |--------------------------------------------------------------------------
    |
    | Central configuration for the website's branding and descriptive text.
    | Values fall back to sensible defaults and can be overridden via the
    | environment file (.env) for environment-specific customization.
    |
    */

    'name' => env('APP_NAME', 'RekapPintar'),

    'short_name' => env('MAIN_CONFIG_SHORT_NAME', 'RekapPintar'),

    'tagline' => env('MAIN_CONFIG_TAGLINE', 'Pendataan dan Rekap Pintar'),

    'description' => env('MAIN_CONFIG_DESCRIPTION', 'Platform pendataan dan rekap otomatis untuk tim modern.'),

];
