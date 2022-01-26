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

class Password extends BaseController
{

    /**
     * Show the forgotten password page
     */
    public function forgot()
    {
        View::renderTemplate('password/forgot.html');
    }

    /**
     * Send the password reset link to the supplied email
     */
    public function requestReset()
    {
        UserModel::sendPasswordReset($_POST['email']);

        View::renderTemplate('password/reset_requested.html');
    }

    /**
     * Show the reset password form
     * @throws Exception
     */
    public function reset()
    {
        $token = $this->route_params['token'];

        $user = $this->getUserOrExit($token);

        View::renderTemplate('password/reset.html', [
            'token' => $token
        ]);
    }

    /**
     * Reset the user's password
     * @throws Exception
     */
    public function resetPassword()
    {
        $token = $_POST['token'];

        $user = $this->getUserOrExit($token);

        if ($user->resetPassword($_POST['password'])) {

            //echo "password valid";
            View::renderTemplate('password/reset_success.html');
        
        } else {

            View::renderTemplate('password/reset.html', [
                'token' => $token,
                'user' => $user
            ]);
        }
    }

    /**
     * Find the user model associated with the password reset token, or end the request with a message
     * @throws Exception
     */
    protected function getUserOrExit(string $token)
    {
        $user = UserModel::findByPasswordReset($token);

        if ($user) {

            return $user;

        } else {

            View::renderTemplate('password/token_expired.html');
            exit;
        }
    }
}