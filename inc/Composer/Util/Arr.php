<?php
/**
 * This file is part of the Hybrid Tools package.
 */

declare(strict_types = 1);

namespace Hybrid\DevTools\Composer\Util;

use ArrayAccess;
use Closure;

class Arr {

    /**
     * Return the default value of the given value.
     *
     * @param  mixed $value
     * @param mixed ...$args
     * @return mixed
     */
    public static function value( $value, ...$args ) {
        return $value instanceof Closure
            ? $value( ...$args )
            : $value;
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  iterable $array
     * @param  string   $prepend
     * @return array
     */
    public static function dot( $array, $prepend = '' ) {
        $results = [];

        foreach ( $array as $key => $value ) {
            if ( is_array( $value ) && ! empty( $value ) ) {
                $results = array_merge( $results, static::dot( $value, $prepend . $key . '.' ) );
            } else {
                $results[ $prepend . $key ] = $value;
            }
        }

        return $results;
    }

    /**
     * Convert a flatten "dot" notation array into an expanded array.
     *
     * @param  iterable $array
     * @return array
     */
    public static function undot( $array ) {
        $results = [];

        foreach ( $array as $key => $value ) {
            static::set( $results, $key, $value );
        }

        return $results;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array           $array
     * @param  string|int|null $key
     * @param  mixed           $value
     * @return array
     */
    public static function set( &$array, $key, $value ) {
        if ( is_null( $key ) ) {
            return $array = $value;
        }

        $keys = explode( '.', $key );

        foreach ( $keys as $i => $key ) {
            if ( count( $keys ) === 1 ) {
                break;
            }

            unset( $keys[ $i ] );

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if ( ! isset( $array[ $key ] ) || ! is_array( $array[ $key ] ) ) {
                $array[ $key ] = [];
            }

            $array = &$array[ $key ];
        }

        $array[ array_shift( $keys ) ] = $value;

        return $array;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param  \ArrayAccess|array $array
     * @param  string|int         $key
     * @return bool
     */
    public static function exists( $array, $key ) {
        if ( $array instanceof Enumerable ) {
            return $array->has( $key );
        }

        if ( $array instanceof ArrayAccess ) {
            return $array->offsetExists( $key );
        }

        if ( is_float( $key ) ) {
            $key = (string) $key;
        }

        return array_key_exists( $key, $array );
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  \ArrayAccess|array $array
     * @param  string|int|null    $key
     * @param  mixed              $default
     * @return mixed
     */
    public static function get( $array, $key, $default = null ) {
        if ( ! static::accessible( $array ) ) {
            return static::value( $default );
        }

        if ( is_null( $key ) ) {
            return $array;
        }

        if ( static::exists( $array, $key ) ) {
            return $array[ $key ];
        }

        if ( ! str_contains( $key, '.' ) ) {
            return $array[ $key ] ?? static::value( $default );
        }

        foreach ( explode( '.', $key ) as $segment ) {
            if ( static::accessible( $array ) && static::exists( $array, $segment ) ) {
                $array = $array[ $segment ];
            } else {
                return static::value( $default );
            }
        }

        return $array;
    }

    /**
     * Determine whether the given value is array accessible.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function accessible( $value ) {
        return is_array( $value ) || $value instanceof ArrayAccess;
    }

}
