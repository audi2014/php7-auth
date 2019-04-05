<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 27/07/2018
 * Time: 11:23
 */

namespace Audi2014\Auth\AuthRequest;


use Audi2014\Auth\ValidationException;
use Audi2014\Auth\Session\SessionEntity;
use Audi2014\Auth\ValidateEmailTrait;
use Audi2014\Auth\ValidatePasswordTrait;
use Audi2014\Auth\ValidateExpiresInTrait;
use Audi2014\Schema\BaseSchema;
use Audi2014\Schema\SchemaInterface;

class AuthRequestSchema extends BaseSchema {
    use ValidateExpiresInTrait;
    use ValidatePasswordTrait;
    use ValidateEmailTrait;

    /**
     * @param array $data
     * @param bool $validate
     * @return array
     * @throws ValidationException
     */
    protected function didMap(array $data, bool $validate): array {

        if ($data['type'] === AuthRequestEntity::TYPE_EMAIL) {
            if (empty($data['email'])) {
                throw new ValidationException(
                    "invalid email",
                    ValidationException::UI_EMAIL_EMPTY
                );
            }
            if (empty($data['password'])) {
                throw new ValidationException(
                    "invalid email",
                    ValidationException::UI_PASSWORD_EMPTY
                );
            }
        } else if (empty($data['OAuthToken'])) {
            throw new ValidationException("empty OAuthToken", ValidationException::UI_BAD_DATA);
        }
        return $data;
    }


    public function __construct($childProps = []) {
        parent::__construct(
            array_merge([
                'type' => [
                    SchemaInterface::OPTION_TYPE => SchemaInterface::VALUE_TYPE_STRING,
                    SchemaInterface::OPTION_ENUM => AuthRequestEntity::TYPES,
                ],
                'email' => [
                    SchemaInterface::OPTION_TYPE => SchemaInterface::VALUE_TYPE_STRING,
                    SchemaInterface::OPTION_NULLABLE => true,
                    SchemaInterface::OPTION_VALIDATE_FN => [self::class, 'validateEmail'],
                ],
                'password' => [
                    SchemaInterface::OPTION_TYPE => SchemaInterface::VALUE_TYPE_STRING,
                    SchemaInterface::OPTION_NULLABLE => true,
                    SchemaInterface::OPTION_VALIDATE_FN => [self::class, 'validatePassword'],
                ],
                'OAuthToken' => BaseSchema::TYPE_NULL_STRING,
                'version' => BaseSchema::TYPE_STRING_NOT_EMPTY,
                'platform' => [
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