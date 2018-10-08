<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | This option specifies if the app name should be represented as an ASCII
    | logo.
    |
    */

    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Default Font
    |--------------------------------------------------------------------------
    |
    | This option defines the font which should be used for rendering. By
    | default, one standard font is shipped, but you can download additional
    | fonts at http://www.figlet.org.
    |
    */

    'font' => base_path('vendor/laravel-zero/framework/src/Components/Logo/fonts/big.flf'),

    /*
    |--------------------------------------------------------------------------
    | Output Width
    |--------------------------------------------------------------------------
    |
    | This option defines the maximum width of the output string. This is used
    | for word-wrap as well as justification. Be careful when using small
    |  values; they may result in an undefined behaviour.
    |
    */

    'outputWidth' => 80,

    /*
    |--------------------------------------------------------------------------
    | Justification
    |--------------------------------------------------------------------------
    |
    | This option defines the justification of the text. The default
    | justification is defined by the Right To Left option.
    |
    | Possible values: "left", "center", "right", null
    |
    */

    'justification' => null,

    /*
    |--------------------------------------------------------------------------
    | Right To Left
    |--------------------------------------------------------------------------
    |
    | This options defines the option in which the text is written. By default
    | the setting of the font-file is used. When justification is not defined,
    | a text written from right-to-left is automatically right-aligned.
    |
    | Possible values: "right-to-left", "left-to-right", null
    |
    */

    'rightToLeft' => null,

];
