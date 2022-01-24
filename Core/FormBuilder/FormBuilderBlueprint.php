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

namespace Evo\FormBuilder;

use Evo\FormBuilder\Type\TextType;
use Evo\FormBuilder\Type\EmailType;
use Evo\FormBuilder\Type\RadioType;
use Evo\FormBuilder\Type\HiddenType;
use Evo\FormBuilder\Type\NumberType;
use Evo\FormBuilder\Type\SelectType;
use Evo\FormBuilder\Type\SubmitType;
use Evo\FormBuilder\Type\UploadType;
use Evo\FormBuilder\Type\CheckboxType;
use Evo\FormBuilder\Type\PasswordType;
use Evo\FormBuilder\Type\TextareaType;
use Evo\FormBuilder\Type\MultipleCheckboxType;
use Evo\FormBuilder\FormBuilderBlueprintInterface;

class FormBuilderBlueprint implements FormBuilderBlueprintInterface
{

    private function args(
        string $name,
        array $class = [],
        $value = null,
        ?string $placeholder = null
    ): array {
        return [
            'name' => $name,
            'class' => array_merge(['uk-input'], $class),
            'placeholder' => ($placeholder !== null) ? $placeholder : '',
            'value' => ($value !== null) ? $value : ''

        ];
    }

    private function arg(
        string $name,
        array $class = [],
        $value = null
    ): array {
        return [
            'name' => $name,
            'class' => $class,
            'value' => ($value !== null) ? $value : ''

        ];
    }

    private function subArg(
        string $name,
        array $class = [],
        $value = null,
        string $onclick = null
    ): array {
        return [
            'name' => $name,
            'class' => $class,
            'value' => ($value !== null) ? $value : '',
            'onclick' => $onclick

        ];
    }


    public function text(
        string $name,
        array $class = [],
        $value = null,
        bool $disabled = false,
        ?string $placeholder = null
    ): array {
        return [
            TextType::class => [
                array_merge(
                    $this->args($name, $class, $value, $placeholder),
                    ['disabled' => $disabled]
                )
            ]
        ];

    }

    public function number(
        string $name,
        array $class = [],
        $value = null,
        bool $disabled = false,
        ?string $placeholder = null
    ): array {
        return [
            NumberType::class => [
                array_merge(
                    $this->args($name, $class, $value, $placeholder),
                    ['disabled' => $disabled]
                )
            ]
        ];

    }

    public function hidden(
        string $name,
        $value = null,
        array $class = []
    ): array {
        return [
            HiddenType::class => [
                array_merge(
                    $this->arg($name, $class, $value),
                    []
                )

            ]
        ];

    }

    public function textarea(
        string $name,
        array $class = [],
        $id = null,
        ?string $placeholder = null,
        int $rows = 5,
        int $cols = 33,
    ): array {
        return [
            TextareaType::class => [
                'name' => $name,
                'class' => $class,
                'id' => $id,
                'placeholder' => $placeholder,
                'rows' => $rows,
                'cols' => $cols
            ]
        ];

    }


    public function email(
        string $name,
        array $class = [],
        $value = null,
        bool $required = true,
        bool $pattern = false,
        ?string $placeholder = null
    ): array {
        return [
            EmailType::class => [
                array_merge(
                    $this->args($name, $class, $value, $placeholder),
                    ['required' => $required, 'pattern' => $pattern]
                )
            ]
        ];
    }

    public function password(
        string $name,
        array $class = [],
        $value = null,
        ?string $autocomplete = null,
        bool $required = false,
        bool $pattern = false,
        bool $disabled = false,
        ?string $placeholder = null
    ): array {
        return [
            PasswordType::class => [
                array_merge(
                    $this->args($name, $class, $value, $placeholder),
                    ['autocomplete' => $autocomplete, 'required' => $required, 'pattern' => $pattern, 'disabled' => $disabled]
                )
            ]
        ];
    }
    
    public function radio(string $name, array $class = [], $value = null): array
    {
        return [
            RadioType::class => [
                array_merge(
                    $this->arg($name, array_merge(['uk-radio'], $class), $value),
                    []
                )
            ]
        ];
    }

    public function checkbox(
        string $name,
        array $class = [],
        $value = null
    ): array {
        return [
            CheckboxType::class => [
                $this->arg($name, array_merge(['uk-checkbox'], $class), $value)
            ]
        ];
    }

    public function select(
        string $name,
        array $class = [],
        string $id = null,
        $size = null,
        bool $multiple = false,
    ): array
    {

        return [
            SelectType::class => [
                'name' => $name,
                'class' => $class,
                'id' => $id,
                'size' => $size,
                'multiple' => $multiple
            ]
        ];
    }


    public function multipleCheckbox(
        string $name,
        array $class = [],
        $value = null
    ): array {
        return [
            MultipleCheckboxType::class => [
                $this->arg($name, array_merge(['uk-checkbox'], $class), $value)
            ]
        ];
    }

    public function upload(string $name, array $class = [], ?string $value = null, bool $multiple = true)
    {
        return [
            UploadType::class => [
                ['name' => $name, 'class' => $class, 'value' => $value, 'multiple' => $multiple]
            ]
        ];
    }

    public function submit(
        string $name,
        array $class = [],
        $value = null,
        ?string $onclick = null
    ): array {
        return [
            SubmitType::class => [
                $this->subArg($name, $class, $value, $onclick)
            ]
        ];
    }

    public function choices(array $choices, string|int|array $default = null, object $form = null): array
    {
        return [
            'choices' => $choices,
            'default' => ($default !==null) ? $default : 'pending',
            'object' => $form
        ];
    }

    public function settings(
        bool $inlineFlipIcon = false,
        ?string $inlineIcon = null,
        bool $showLabel = true,
        ?string $newLabel = null,
        bool $wrapper = false,
        ?string $checkboxLabel = null,
        ?string $description = null): array
    {
        return [
            'inline_flip_icon' => $inlineFlipIcon,
            'inline_icon' => $inlineIcon,
            'show_label' => $showLabel,
            'new_label' => $newLabel,
            'before_after_wrapper' => $wrapper,
            'checkbox_label' => $checkboxLabel,
            'description' => $description
        ];
    }
}
