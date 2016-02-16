<?php

/**
 * Global helper functions, this is a side effect of using Laravel for so long ;).
 */
if (!function_exists('dd')) {
    /**
     * Dump and die.
     *
     * @param mixed $dump
     */
    function dd($dump)
    {
        var_dump($dump);
        die;
    }
}

if (!function_exists('templatePath')) {

    /**
     * @param string $name
     *
     * @return string
     */
    function templatePath($name)
    {
        return APP_BASE.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$name;
    }
}
