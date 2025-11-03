<?php

return [
    /**
     * The site key
     * get site key @  arcaptcha.ir/dashboard.
     */
    'site_key'                 => env('ARCAPTCHA_SITE_KEY', ''),

    /**
     * The secret key
     * get secret key @ arcaptcha.ir/dashboard.
     */
    'secret_key'               => env('ARCAPTCHA_SECRET_KEY', ''),


    /**
     * Default returned value from verify function 
     * when there is an Network or any other unexpected issue.
     */
    'verify_exception_value'               => env('ARCAPTCHA_VERIFY_EXCEPTION_VALUE', 'false'),

];
