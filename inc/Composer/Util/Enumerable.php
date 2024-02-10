<?php
/**
 * This file is part of the Hybrid Tools package.
 */

declare(strict_types = 1);

namespace Hybrid\DevTools\Composer\Util;

use Countable;
use Hybrid\DevTools\Composer\Util\Contracts\Arrayable;
use Hybrid\DevTools\Composer\Util\Contracts\Jsonable;
use IteratorAggregate;
use JsonSerializable;

/**
 * @template TKey of array-key
 * @template-covariant TValue
 * @extends \Hybrid\DevTools\Composer\Util\Contracts\Arrayable<TKey, TValue>
 * @extends \IteratorAggregate<TKey, TValue>
 */
interface Enumerable extends Arrayable, Countable, IteratorAggregate, Jsonable, JsonSerializable {

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param  TKey|array<array-key, TKey> $key
     * @return bool
     */
    public function has( $key );

}
