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

namespace Evo\Base;

use Evo\Base\BaseEntity;
use Evo\Base\Exception\BaseInvalidArgumentException;
use Evo\DataSchema\DataSchemaBuilderInterface;
use Evo\Orm\ClientRepository\ClientRepository;
use Evo\Orm\ClientRepository\ClientRepositoryFactory;
use Exception;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Throwable;

class BaseModel
{
    protected string $tableSchema;
    protected string $tableSchemaID;
    protected Object $repository;
    protected BaseEntity $entity;

    /**
     * @throws Throwable
     */
    public function __construct(string $tableSchema = null, string $tableSchemaID = null, $entity = null)
    {
        $this->throwExceptionIfEmpty($tableSchema, $tableSchemaID);
        if ($entity !== null) {
            $this->entity  = BaseApplication::diGet($entity);
            if (!$this->entity instanceof BaseEntity) {
                throw new BaseInvalidArgumentException($entity . ' is not an instance of BaseEntity::class');
            }
        }
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
//        $this->casting(self::ALLOWED_CASTING_TYPES);
        $this->createRepository($this->tableSchema, $this->tableSchemaID);

//        parent::__construct($this);
    }

    /**
     * Create the model repositories
     */
    public function createRepository(string $tableSchema, string $tableSchemaID): void
    {
        try {
//            $factory = new DataRepositoryFactory('baseModel', $tableSchema, $tableSchemaID);
            $factory = new ClientRepositoryFactory('baseModel', $tableSchema, $tableSchemaID);
//            $this->repository = $factory->create(DataRepository::class);
            $this->repository = $factory->create(ClientRepository::class);

        } catch(Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * Throw an exception if the two required model constants is empty
     */
    private function throwExceptionIfEmpty(string $tableSchema, string $tableSchemaID): void
    {
        if (empty($tableSchema) || empty($tableSchemaID)) {
            throw new BaseInvalidArgumentException('Your repository is missing the required constants. Please add the TABLESCHEMA and TABLESCHEMAID constants to your repository.');
        }
    }

    public function getRepository(): object
    {
        return $this->repository;
    }

    public function getSchemaID(): string
    {
        return $this->tableSchemaID;
    }

    public function getSchema(): string
    {
        return $this->tableSchema;
    }

    public function getEntity(): BaseEntity
    {
        return $this->entity;
    }

    /**
     * Allows models to retrieve other models. Simple pass in the qualified namespace of the model
     * we want an object of ie getOtherModel(RoleModel::class)->getRepository() which will give access
     * to the repository for the other model
     */
    public function getOtherModel(string $model): BaseModel
    {
        if (!is_string($model)) {
            throw new BaseInvalidArgumentException('Invalid argument. Ensure you are passing the fully qualified namespace of the other model. ie [ExampleModel::class]');
        }
        $modelObject = BaseApplication::diGet($model);
        if (!$modelObject instanceof self) {
            throw new BaseInvalidArgumentException($model . ' is an invalid model. As its does not relate to the BaseModel.');
        }
        return $modelObject;

    }

    /**
     * Return the name object from within the app namespace. i,e validate.user
     * will instantiate the App\Validate\UserValidate Object. We only call the object
     * on the fly and use it when we want.
     */
    public function get(string $objectName, string $optionalNamespace = null)
    {
        if (empty($objectName)) {
            throw new InvalidArgumentException('Please provide the name of your object');
        }
        if (strpos($objectName, '.') === false) {
            throw new InvalidArgumentException('Invalid object name ensure you are referencing the object using the correct notation i.e validate.user');
        }
        // As we are expecting the object name using dot notations we need to convert it
        if (is_string($objectName)) {

            if ($optionalNamespace !==null) {
                $pieces = explode('.', $objectName);
                $name = $pieces[1] ?? '';
                $modelName = ucwords($optionalNamespace . $name);

            } else {
                $objectName = 'app.' . $objectName;
                $modelName = ucwords(str_replace('.', '\\', $objectName));

            }

            return BaseApplication::diGet($modelName);
        }
    }

    /**
     * Create method which initialize the schema object and return its result
     * within the set class property.
     */
    public function create(string $dataSchema = null): BaseModel
    {
        if ($dataSchema !== null) {
            $newSchema = BaseApplication::diGet($dataSchema);
            if (!$newSchema instanceof DataSchemaBuilderInterface) {
                throw new BaseInvalidArgumentException('');
            }
            $this->dataSchemaObject = $newSchema;
            return $this;
        }
    }

    /**
     * Return an array of database column name matching the object schema
     * and model
     */
    public function getColumns(string $schema): array
    {
        return $this->create($schema)->getSchemaColumns();
    }

    /**
     * Allows each model to return a $fillable array of database column names which
     * must never be null. Each model must define a class property of $fillable which
     * returns an array of fillable fields
     */
    public function getFillables(?string $model = null): array
    {
        if (!$this->fillable) {
            throw new BaseInvalidArgumentException('No fillable array set for your entity class ' . $model);
        }
        return $this->fillable;
    }

    /**
     * Return the schema object database column name as an array. Which can be used
     * to map the columns within the dataColumn object. To construct the datatable
     * @throws ReflectionException
     */
    public function getSchemaColumns(int $indexPosition = 2): array
    {
        $reflectionClass = new ReflectionClass($this->dataSchemaObject);
        $propertyName = $reflectionClass->getProperties()[$indexPosition]->getName();
        if (str_contains($propertyName, 'Model') === false) {
            throw new BaseInvalidArgumentException('Invalid property name');
        }
        if ($reflectionClass->hasProperty($propertyName)) {
            $reflectionProperty = new ReflectionProperty($this->dataSchemaObject, $propertyName);
            $reflectionProperty->setAccessible(true);
            $props = $reflectionProperty->getValue($this->dataSchemaObject);
            $this->dbColumns = $props->getRepository()
                ->getEm()
                ->getCrud()
                ->rawQuery('SHOW COLUMNS FROM ' . $props->getSchema(), [], 'columns');

            $reflectionProperty->setAccessible(false);

            return $this->dbColumns;

        }
    }

    /**
     * Un-serialize any serialize data coming from the database
     * @throws Exception
     */
    public function unserializeData(array $conditions, $data = null)
    {
        if ($conditions) {
            $serializeData = $this->getRepository()->findOneBy($conditions);
            if ($serializeData) {
                foreach ($serializeData as $serialData) {
                    if (is_null($data)) {
                        throw new Exception();
                    }
                    if (is_array($data)) {
                        return array_map(fn($d) => unserialize($serialData[$d]), $data);
                    } elseif (is_string($data)) {
                        return unserialize($serialData[$data]);
                    }

                }
            }
        }

    }

    /**
     * Unset the database column which is not cloneable
     */
    public function unsetCloneKeys(array $cloneArray): array
    {
        if (is_array($this->unsettableClone) && count($this->unsettableClone) > 0) {
            foreach ($this->unsettableClone as $unsettable) {
                unset($cloneArray[$unsettable]);
            }
        }
        return $cloneArray;
    }

    /**
     * returns an array of database column which should be unique when cloning
     */
    public function getClonableKeys(): ?array
    {
        if (is_array($this->cloneableKeys) && count($this->cloneableKeys) > 0) {
            return $this->cloneableKeys;
        }
        return null;
    }

    /**
     * Get the value of a column[name] for the current queried ID. The name of the column must
     * be specified within the second argument.
     */
    public function getSelectedNameField(int $id, string $field = null, ?string $model = null)
    {
        $name = $this->getRepository()->findObjectBy(['id' => $id], [$field]);
        if ($field === null) {
            throw new BaseInvalidArgumentException('Your second argument is null. This needs to represent a column name for the matching repository.');
        }
        return $name->$field;
    }
}