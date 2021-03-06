<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Router\Exception;

use Exception;

/**
 * Redirect Exception.
 * This class should be used to stop execution when a redirection to another
 * route/path is requred. $path in setPath() method, must be a value present
 * in at least one Route inside routes collection passed to the Router object.
 */
class RedirectException extends Exception
{
    /**
     * @var string Path on which to be redirected.
     */
    private $path = '';

    /**
     * Class Constructor.
     *
     * @param string $path Path on which to be redirected.
     */
    public function __construct(string $path = '')
    {
        parent::__construct();

        $this->path = $path;
    }

    /**
     * Set path.
     *
     * @param string $path Path on which to be redirected.
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * Get path.
     *
     * @return string Path on which to be redirected.
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
