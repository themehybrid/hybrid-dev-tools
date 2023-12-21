<?php
/**
 * This file is part of the Hybrid Tools package.
 */

declare(strict_types = 1);

namespace Hybrid\DevTools\Composer\Util\Contracts;

interface Jsonable {

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson( $options = 0 );

}
