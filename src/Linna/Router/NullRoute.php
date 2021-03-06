<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Router;

/**
 * Describes null routes.
 */
class NullRoute implements RouteInterface
{
    /**
     * Constructor.
     *
     * @param array<mixed> $route
     */
    public function __construct(array $route = [])
    {
        unset($route);
    }
}
