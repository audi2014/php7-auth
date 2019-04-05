<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 27/07/2018
 * Time: 11:23
 */

namespace Audi2014\Auth\AuthRequest;


use Audi2014\Auth\Session\SessionEntity;
use Audi2014\Auth\ValidateExpiresInTrait;
use Audi2014\Schema\BaseSchema;
use Audi2014\Schema\SchemaInterface;

class AuthConfigSchema extends BaseSchema {
    use ValidateExpiresInTrait;

    public function __construct($childProps = []) {
        parent::__construct(
            array_merge([

                'version' => BaseSchema::TYPE_NULL_STRING,
                'platform' => [
                    SchemaInterface::OPTION_NULLABLE => true,
                    SchemaInterface::OPTION_TYPE => SchemaInterface::VALUE_TYPE_STRING,
                    SchemaInterface::OPTION_ENUM => SessionEntity::PLATFORMS,
                ],
                'pushToken' => BaseSchema::TYPE_NULL_STRING,
                'expiresIn' => [
                    SchemaInterface::OPTION_DEFAULT_VALUE => 60*60*5,
                    SchemaInterface::OPTION_TYPE => SchemaInterface::VALUE_TYPE_INT,
                    SchemaInterface::OPTION_VALIDATE_FN => [self::class, 'validateExpiresIn']
                ],
                'deleteIn' => [
                    SchemaInterface::OPTION_DEFAULT_VALUE => 60*60*24*14,
                    SchemaInterface::OPTION_TYPE => SchemaInterface::VALUE_TYPE_INT,
                    SchemaInterface::OPTION_VALIDATE_FN => [self::class, 'validateDeleteIn']
                ],
            ],$childProps), self::class
        );
    }
}