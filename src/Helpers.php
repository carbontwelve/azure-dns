<?php

/**
 * Global helper functions, this is a side effect of using Laravel for so long ;)
 */

if (! function_exists('session')) {
    function session($key = null, $value = null)
    {
        $session = app('session');

        if ( is_null($key) && is_null($value)) {
            return $session;
        }

        if ( !is_null($key) && is_null($value)) {
            return $session;
        }
    }
}

if (!function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string $path
     * @param  bool $secure
     * @return string
     */
    function asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die
     * @param mixed $dump
     */
    function dd($dump)
    {
        var_dump($dump);
        die;
    }
}
