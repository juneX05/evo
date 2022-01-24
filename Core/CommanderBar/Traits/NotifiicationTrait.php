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

namespace Evo\CommanderBar\Traits;

use Evo\Utility\Stringify;

trait NotifiicationTrait
{

    private function notifications(): string
    {
        if (isset($this->controller)) {
            if (in_array($this->controller->thisRouteAction(), $this->controller->commander->unsetNotification())) {
                return '';
            }
        }
        //$off = '<ion-icon name="notifications-off-outline"></ion-icon>';
        $commander = '<li class="uk-active">';
        $commander .= '<a href="javascript:void()" class="uk-text-muted">';
        $commander .= '<ion-icon size="large" name="notifications-outline"></ion-icon>';
        $commander .= '<span><sup class="uk-badge">3</sup></span>';
        $commander .= '</a>';
        $commander .= '</li>';

        return $commander;
    }


}
