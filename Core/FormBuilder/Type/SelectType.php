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

namespace Evo\FormBuilder\Type;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Evo\FormBuilder\FormBuilderTypeInterface;
use Evo\FormBuilder\FormBuilderTrait;

class SelectType implements FormBuilderTypeInterface
{

    use FormBuilderTrait;

    /** @var string - returns the name of the extension. IMPORTANT */
    protected string $type = 'select';
     /** @var array - returns the combined attr options from extensions and constructor fields */
    protected array $attr = [];
    /** @var array - return an array of form fields attributes */
    protected array $fields = [];
    /** @var array returns an array of form settings */
    protected array $settings = [];
    /** @var */
    protected $options = null;
    /** @var array returns an array of default options set */
    protected array $baseOptions = [];

    /**
     * @param array $fields
     * @param mixed|null $options
     * @param array $settings
     */
    #[Pure] public function __construct(array $fields, $options = null, array $settings = [])
    {
        $this->fields = $fields;
        $this->options = ($options !=null) ? $options : null;
        $this->settings = $settings;
        if (is_array($this->baseOptions)) {
            $this->baseOptions = $this->getBaseOptions();
        }
    }

    /**
     * Returns an array of base options.
     *
     * @return array
     */
    #[ArrayShape(['name' => "string", 'id' => "string", 'class' => "string[]", 'size' => "string", 'multiple' => "false", 'autocomplete' => "string"])] public function getBaseOptions() : array
    {
        return [
            'name' => '',
            'id' => '',
            'class' => ['uk-select'],
            'size' => 30,
            'multiple' => false,
            'autocomplete' => 'off'
        ];
    }

    /**
     * Options which are defined for this object type
     * Pass the default array to the parent::configureOptions to merge together
     *
     * @param array $options
     * @return void
     */
    public function configureOptions(array $options = []): void
    {
        $defaultWithExtensionOptions = (!empty($options) ? array_merge($this->baseOptions, $options) : $this->baseOptions);
        if ($this->fields) {
            $this->throwExceptionOnBadInvalidKeys(
                $this->fields, 
                $defaultWithExtensionOptions,
                __CLASS__
            );

            $this->attr = array_merge($defaultWithExtensionOptions, $this->fields);
        }
    }

    /**
     * Publicize the default object type to other classes
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Publicize the default object options to the base class
     *
     * @return array
     */
    public function getOptions() : array
    {
        return $this->attr;
    }

    /**
     * Return the third argument from the add() method. This array can be used
     * to modify and filter the final output of the input and HTML wrapper
     *
     * @return array
     */
    public function getSettings() : array
    {
        $defaults = [
            'container' => true,
            'show_label' => true,
            'new_label' => ''
        ];
        return (!empty($this->settings) ? array_merge($defaults, $this->settings) : $defaults);
    }

    /**
     * The pre filter method provides a way to filtered the build field input
     * on a a per object type basis as all types share the same basic tags
     *
     * there are cases where a tag is not required or valid within a
     * particular input/field. So we can filter it out here before being sent
     * back to the controller class
     * 
     * @return - return the filtered or unfiltered string
     */
    public function filtering(): string
    {
        if (!is_array($this->options)) {
            $this->options = array();
        }
        return $this->renderHtmlElement($this->attr);
    }

    /**
     * Render the form view to the builder method within the base class
     *
     * @return string
     */
    public function view(): string
    { 
        return sprintf('<select %s>%s</select>', $this->filtering(), $this->renderSelectOptions($this->options));
    }


}