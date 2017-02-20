<?php
/**
 * This file is part of the PHP components.
 *
 * For the full copyright and license information, please view the LICENSE.md file distributed with this source code.
 *
 * @license MIT License
 * @link    https://github.com/ansas/php-component
 */

namespace Ansas\Slim\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Runtime
 *
 * Middleware to add script execution time (runtime) to response header.
 *
 * @package Ansas\Slim\Middleware
 * @author  Ansas Meyer <mail@ansas-meyer.de>
 */
class Runtime
{
    /** HTTP response header name */
    const HEADER = 'X-App-Runtime';

    /**
     * Execute the middleware.
     *
     * @param Request  $request
     * @param Response $response
     * @param callable $next
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $requestTime = $request->getServerParam('REQUEST_TIME_FLOAT', microtime(true));

        /** @var Response $response */
        $response = $next($request, $response);

        $executionTime = microtime(true) - $requestTime;

        return $response->withHeader(self::HEADER, $this->readableTime($executionTime));
    }

    /**
     * @param float $time
     * @param int   $decimals [optional]
     *
     * @return string
     */
    protected function readableTime($time, $decimals = 3)
    {
        $decimals = (int) $decimals;
        $unit     = 'sec';

        return sprintf("%.{$decimals}f %s", $time, $unit);
    }
}
