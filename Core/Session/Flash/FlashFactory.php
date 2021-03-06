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

namespace Evo\Session\Flash;

use Evo\Session\Exception\SessionUnexpectedValueException;
use Evo\Session\SessionInterface;

class FlashFactory
{
    public function __construct()
    { }

    /**
     * Session factory which create the session object and instantiate the chosen
     * session storage which defaults to nativeSessionStorage. This storage object accepts
     * the session environment object as the only argument.
     */
    public function create(?SessionInterface $session = null, ?string $flashKey = null) : FlashInterface
    {
        if (!$session instanceof SessionInterface) {
            throw new SessionUnexpectedValueException('Object does not implement session interface.');
        }
        return new Flash($session, $flashKey);
    }

}