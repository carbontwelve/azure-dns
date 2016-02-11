<?php

/**
 * Global helper functions, this is a side effect of using Laravel for so long ;)
 */

if (! function_exists('app')) {
    /**
     * @param null|string $id
     * @return \Carbontwelve\AzureDns\App|mixed
     */
    function app($id = null)
    {
        if (is_null($id)) {
            return \Carbontwelve\AzureDns\App::getInstance();
        }
        return \Carbontwelve\AzureDns\App::getInstance()->getContainer()->get($id);
    }
}
if (! function_exists('session')) {
    /**
     * @param null|string $key
     * @param null|mixed $value
     * @return \Aura\Session\Segment|mixed
     */
    function session($key = null, $value = null)
    {
        /** @var \Aura\Session\Segment $segment */
        $segment = app(\Aura\Session\Segment::class);

        if (is_null($key) && is_null($value)) {
            return $segment;
        }

        if (!is_null($key) && is_null($value)) {
            return $segment->get($key);
        }

        if (!is_null($key) && !is_null($value)) {
            $segment->set($key, $value);
        }

        return null;
    }
}
