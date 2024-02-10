<?php
/**
 * This file is part of the Hybrid Tools package.
 */

declare(strict_types = 1);

namespace Hybrid\DevTools\Composer\Util\Contracts;

/**
 * @template TKey of array-key
 * @template TValue
 */
interface Arrayable {

    /**
     * Get the instance as an array.
     *
     * @return array<TKey, TValue>
     */
    public function toArray();

}
