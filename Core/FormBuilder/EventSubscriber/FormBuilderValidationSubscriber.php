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

namespace Evo\FormBuilder\EventSubscriber;

//use JetBrains\PhpStorm\ArrayShape;
use Evo\EventDispatcher\EventDispatcherTrait;
use Evo\EventDispatcher\EventDispatcherDefaulter;
use Evo\EventDispatcher\EventSubscriberInterface;
use Evo\ValidationRule\Event\ValidateRuleEvent;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class FormBuilderValidationSubscriber extends EventDispatcherDefaulter implements EventSubscriberInterface
{

    use EventDispatcherTrait;

    /**
     * Subscribe multiple listeners to listen for the NewActionEvent. This will fire
     * each time a new user is added to the database. Listeners can then perform
     * additional tasks on that return object.
     */
    #[ArrayShape([ValidateRuleEvent::NAME => "\string[][]"])] public static function getSubscribedEvents(): array
    {
        return [
            ValidateRuleEvent::NAME => [
                ['test'],
            ]
        ];
    }


}
