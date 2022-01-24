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

namespace Evo\UserManager\Rbac\Schema;

use Evo\UserManager\Rbac\Model\TemporaryRoleModel;
use Evo\DataSchema\DataSchema;
use Evo\DataSchema\DataSchemaBlueprint;
use Evo\DataSchema\DataSchemaBuilderInterface;

class TemporaryRoleSchema implements DataSchemaBuilderInterface
{

    /** @var object - $schema for chaining the schema together */
    protected object $schema;
    /** @var object - provides helper function for quickly adding schema types */
    protected object $blueprint;
    /** @var object - the database model this schema is linked to */
    protected object $tempRoleModel;

    /**
     * Main constructor class. Any typed hinted dependencies will be autowired. As this
     * class can be inserted inside a dependency container
     *
     * @param DataSchema $schema
     * @param DataSchemaBlueprint $blueprint
     * @param TemporaryRoleModel $tempRoleModel
     * @return void
     */
    public function __construct(DataSchema $schema, DataSchemaBlueprint $blueprint, TemporaryRoleModel $tempRoleModel)
    {
        $this->schema = $schema;
        $this->blueprint = $blueprint;
        $this->tempRoleModel = $tempRoleModel;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function createSchema(): string
    {
        return $this->schema
            ->schema()
            ->table($this->userModel)
            ->row($this->blueprint->autoID())
            ->build(function ($schema) {
                return $schema
                    ->addPrimaryKey($this->blueprint->getPrimaryKey())
                    ->setUniqueKey(['user_id'])
                    ->addKeys();
            });
    }
}

