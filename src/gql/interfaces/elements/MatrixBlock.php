<?php
namespace craft\gql\interfaces\elements;

use craft\elements\MatrixBlock as MatrixBlockElement;
use craft\gql\interfaces\Element;
use craft\gql\TypeLoader;
use craft\gql\GqlEntityRegistry;
use craft\gql\types\generators\MatrixBlockType;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\Type;

/**
 * Class MatrixBlock
 */
class MatrixBlock extends Element
{
    /**
     * @inheritdoc
     */
    public static function getTypeGenerator(): string
    {
        return MatrixBlockType::class;
    }

    /**
     * @inheritdoc
     */
    public static function getType($fields = null): Type
    {
        if ($type = GqlEntityRegistry::getEntity(self::class)) {
            return $type;
        }

        $type = GqlEntityRegistry::createEntity(self::class, new InterfaceType([
            'name' => static::getName(),
            'fields' => self::class . '::getFields',
            'description' => 'This is the interface implemented by all matrix blocks.',
            'resolveType' => function (MatrixBlockElement $value) {
                return GqlEntityRegistry::getEntity($value->getGqlTypeName());
            }
        ]));

        foreach (MatrixBlockType::generateTypes() as $typeName => $generatedType) {
            TypeLoader::registerType($typeName, function () use ($generatedType) { return $generatedType ;});
        }

        return $type;
    }

    /**
     * @inheritdoc
     */
    public static function getName(): string
    {
        return 'MatrixBlockInterface';
    }

    /**
     * @inheritdoc
     */
    public static function getFields(): array {
        // Todo nest nestable things. Such as field data under field subtype.
        return array_merge(parent::getFields(), [
            'fieldUid' => [
                'name' => 'fieldUid',
                'type' => Type::string(),
                'description' => 'The UID of the field that owns the matrix block.'
            ],
            'fieldId' => [
                'name' => 'fieldId',
                'type' => Type::int(),
                'description' => 'The ID of the field that owns the matrix block.'
            ],
            'ownerUid' => [
                'name' => 'ownerUid',
                'type' => Type::string(),
                'description' => 'The UID of the element that owns the matrix block.'
            ],
            'ownerId' => [
                'name' => 'ownerId',
                'type' => Type::int(),
                'description' => 'The ID of the element that owns the matrix block.'
            ],
            'typeUid' => [
                'name' => 'typeUid',
                'type' => Type::string(),
                'description' => 'The UID of the matrix block\'s type.'
            ],
            'typeId' => [
                'name' => 'typeId',
                'type' => Type::int(),
                'description' => 'The ID of the matrix block\'s type.'
            ],
            'typeHandle' => [
                'name' => 'typeHandle',
                'type' => Type::string(),
                'description' => 'The handle of the matrix block\'s type.'
            ],
            'sortOrder' => [
                'name' => 'sortOrder',
                'type' => Type::int(),
                'description' => 'The sort order of the matrix block within the owner element field.'
            ],
        ]);
    }
}
