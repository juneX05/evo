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

namespace Evo\DataSchema\Traits;

use Evo\DataSchema\Exception\DataSchemaInvalidArgumentException;

trait DataSchemaTypesTrait
{

    /**
     * Validate the user defined schema columns ensure only the correct information
     * is pass or will throw an exception.
     */
    public function validateSchemaColumns($key, $value): bool
    {
        switch ($key) {
            case 'name':
                if ($value === '') {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
            case 'type':
                if (!in_array($value, $this->types)) {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
            case 'length':
                if (!is_int($value)) {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
            case 'index':
                if (!in_array($value, ['primary', 'unique', 'index', 'fulltext'])) {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
            case 'null':
            case 'auto_increment':
                if (!is_bool($value)) {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
            case 'default':
                if (!in_array($value, ['none', 'null', 'CURRENT_TIMESTAMP', $value])) {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
            case 'attributes':
                if (!in_array($value, ['', 'binary', 'unsigned', 'unsigned zerofill', 'on update CURRENT_TIMESTAMP'])) {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
        }
        $this->row[$key] = $value;
        return true;
    }


    public function _name(): string
    {
        extract($this->getRow());
        if ($name)
            return "`{$name}` {$type}";
    }

    public function _length(): string
    {
        extract($this->getRow());
        return (isset($length) && $length !== 0) ? "({$length})" : '';
    }

    public function _extra()
    {
        extract($this->getRow());
        return (isset($auto_increment) && $auto_increment === true) ? ' AUTO_INCREMENT' : '';
    }

    public function _attributes(): string
    {
        extract($this->getRow());
        return (isset($attributes) && $attributes !== '') ? ' ' . strtoupper($attributes) . ' ' : '';
    }

    public function _null(): string
    {
        extract($this->getRow());
        return (isset($null) && $null === false) ? ' NOT NULL' : '';
    }

    public function _default(): string
    {
        extract($this->getRow());
        if (isset($default)) {
            return match ($default) {
                'none' => '',
                'null' => ' DEFAULT NULL',
                'ct' => ' DEFAULT CURRENT_TIMESTAMP',
                default => ' DEFAULT ' . $default,
            };
        }
    }
}
