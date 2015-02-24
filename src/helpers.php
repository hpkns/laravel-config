<?php

if ( ! function_exists('array_set_dot'))
{

    /**
     * Add a item to an array using dot notation.
     *
     * @param  array  $array
     * @param  string $key
     * @param  mixed  $value
     * @return mixed
     */
    function array_set_dot(array &$array, $key, $value)
    {
        $pointer = &$array;

        foreach (explode('.', $key) as $segment)
        {
            $pointer = &$pointer[$segment];
        }

        return $pointer = $value;
    }
}

if ( ! function_exists('pretty_config'))
{

    function pretty_config(array $input)
    {
        return var_export($input, true);
    }
}
