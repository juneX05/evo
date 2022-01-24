<?php
/*
 * This file is part of the Evo package.
 *
 * (c) John Andrew <simplygenius78@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types = 1);

namespace Evo\UserManager\Activation;

use Evo\UserManager\Activation\Event\ActivationActionEvent;
use Evo\UserManager\Activation\ActivationRepository;
use Evo\Base\BaseController;
use Evo\Base\Domain\Actions\ActivateAction;
use Evo\Base\Exception\BaseInvalidArgumentException;

class ActivationController extends BaseController
{

    /**
     * Extends the base constructor method. Which gives us access to all the base
     * methods implemented within the base controller class.
     * Class dependency can be loaded within the constructor by calling the
     * container method and passing in an associative array of dependency to use within
     * the class
     *
     * @param array $routeParams
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        /**
         * Dependencies are defined within a associative array like example below
         * [ userModel => \App\Model\UserModel::class ]. Where the key becomes the
         * property for the userModel object like so $this->userModel->getRepo();
         */
        $this->diContainer(
            [
                'repository' => ActivationRepository::class,
                'activateAction' => ActivateAction::class,
            ]
        );
    }

    protected function activateAction()
    {
        $this->activateAction
            ->execute($this, NULL, ActivationActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with(['token_valid' => ''])/* filter template to show fail validation */
            ->end();

    }

}