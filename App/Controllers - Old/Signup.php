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

use App\Models\UserModel;
use Evo\Base\BaseController;
use Evo\Controller;
use Evo\View;
use \App\Models\User;
use Exception;
use Throwable;

class Signup extends BaseController
{

    /**
     * Show the signup page
     */
    public function new()
    {
        View::renderTemplate('signup/new.html');
    }

    /**
     * Sign up a new user
     * @throws Throwable
     */
    public function create()
    {
        $user = new UserModel($_POST);

        if ($user->save()) {

            $user->sendActivationEmail();

            $this->redirect('/signup/success');

        } else {

            View::renderTemplate('signup/new.html', [
                'user' => $user
            ]);

        }
    }

    /**
     * Show the signup success page
     */
    public function success()
    {
        View::renderTemplate('signup/success.html');
    }

    /**
     * Activate a new account
     * @throws Exception
     */
    public function activate()
    {
        UserModel::activateAccount($this->route_params['token']);

        $this->redirect('/signup/activated');
    }

    /**
     * Show the activation success page
     */
    public function activated()
    {
        View::renderTemplate('signup/activated.html');
    }
}