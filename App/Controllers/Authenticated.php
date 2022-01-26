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

namespace App\Controllers;

use Evo\Base\BaseController;

abstract class Authenticated extends BaseController
{
    /**
     * Require the user to be authenticated before giving access to all methods in the controller
     */
    protected function before()
    {
        $this->requireLogin();
    }
}