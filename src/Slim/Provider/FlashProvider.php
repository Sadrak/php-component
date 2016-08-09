<?php
/**
 * This file is part of the PHP components package.
 *
 * For the full copyright and license information, please view the LICENSE.md file distributed with this source code.
 *
 * @license MIT License
 * @link    https://github.com/ansas/php-component
 */

namespace Ansas\Slim\Provider;

use Pimple\Container;
use Slim\Flash\Messages;

/**
 * Class FlashProvider
 *
 * <code>composer require slim/flash</code>
 *
 * @package Ansas\Slim\Provider
 * @author  Ansas Meyer <mail@ansas-meyer.de>
 */
class FlashProvider extends AbstractProvider
{
    /**
     * Register Profiler.
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        /**
         * Add dependency (DI).
         *
         * @return Messages
         */
        $container['flash'] = function () {
            return new Messages();
        };
    }
}
