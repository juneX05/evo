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

namespace Evo\UserManager\Rbac\Role;

use Exception;
use Evo\FormBuilder\ClientFormBuilder;
use Evo\FormBuilder\ClientFormBuilderInterface;
use Evo\FormBuilder\FormBuilderBlueprint;
use Evo\FormBuilder\FormBuilderBlueprintInterface;

class RoleForm extends ClientFormBuilder implements ClientFormBuilderInterface
{

    /** @var FormBuilderBlueprintInterface $blueprint */
    private FormBuilderBlueprintInterface $blueprint;

    /**
     * Main class constructor
     *
     * @param FormBuilderBlueprint $blueprint
     * @return void
     */
    public function __construct(FormBuilderBlueprint $blueprint)
    {
        $this->blueprint = $blueprint;
        parent::__construct();
    }

    /**
     * @param string $action
     * @param ?object $dataRepository
     * @param ?object $callingController
     * @return string
     * @throws Exception
     */
    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null): string
    {
        return $this->form(['action' => $action, 'class' => ['uk-form-stacked'], "id" => "roleForm"])
            ->addRepository($dataRepository)
            ->add($this->blueprint->text('role_name', [], $this->hasValue('role_name')))
            ->add($this->blueprint->textarea('role_description', ['uk-textarea'], 'role_description'), $this->hasValue('role_description'))
            ->add(
                $this->blueprint->submit(
                    $this->hasValue('id') ? 'edit-role' : 'new-role',
                    ['uk-button', 'uk-button-primary', 'uk-form-width-medium'],
                    'Save',
                   // "UIkit.notification({message: '<span uk-icon=\'icon: check\'></span> Saving...', status: 'success', timeout: 3000})"
                ),
                null,
                $this->blueprint->settings(false, null, false, null, true)
            )
            ->build(['before' => '<div class="uk-margin">', 'after' => '</div>']);
    }
}
