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

namespace Evo\UserManager\Model;

use Evo\UserManager\Entity\UserRoleEntity;
use Evo\UserManager\Schema\UserRoleSchema;
use Evo\Base\AbstractBaseModel;
use Evo\Base\Exception\BaseInvalidArgumentException;
use Evo\Orm\DataRelationship\Relationships\ManyToMany;

class UserRoleModel extends AbstractBaseModel
{

    /** @var string */
    protected const TABLESCHEMA = 'user_role';
    /** @var string */
    protected const TABLESCHEMAID = 'id';
    /** @var object $relationship */
    protected object $relationship;

    /**
     * Main constructor class which passes the relevant information to the
     * base model parent constructor. This allows the repository to fetch the
     * correct information from the database based on the model/entity
     *
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function __construct()
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, UserRoleEntity::class);
    }

    /**
     * Guard these IDs from being deleted etc..
     *
     * @return array
     */
    public function guardedID(): array
    {
        return [
        ];
    }

    /**
     * Create an relation between the user and role models using the user_role
     * pivot table as the glue between both relationships
     *
     * @return ManyToMany
     */
    public function hasRelationship(): ManyToMany
    {
        return $this->addRelationship(ManyToMany::class)
            ->hasOne(UserModel::class)->belongsToMany(RoleModel::class)
            ->tablePivot($this, UserRoleSchema::class);
    }

}