<?php
/*
 * This file is part of the Evo package.
 *
 * (c) John Andrew <simplygenius78@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

namespace Evo\Middleware;

use Closure;

class BeforeMiddleware implements MiddlewareInterface
{
    public function middleware(Object $middleware, Closure $next)
    {
        return $next($middleware);
    }
}