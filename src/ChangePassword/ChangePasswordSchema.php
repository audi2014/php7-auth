<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 3/18/19
 * Time: 10:23 AM
 */

namespace Audi2014\Auth\ChangePassword;

use Audi2014\Auth\ValidatePasswordTrait;
use Audi2014\Schema\BaseSchema;
use Audi2014\Schema\SchemaInterface;

class ChangePasswordSchema extends BaseSchema {
    use ValidatePasswordTrait;

    /**
     * @param array $data
     * @param bool $validate
     * @return array
     * @throws ChangePasswordException
     */
    protected function didMap(array $data, bool $validate): array {
        $oldPassword = $data['oldPassword'] ?? $data['prevPassword'] ?? $data['currentPassword'] ?? null;
        $newPassword = $data['newPassword'] ?? $data['nextPassword'] ?? $data['password'] ?? null;
        if ($oldPassword === $newPassword) {
            throw new ChangePasswordException("oldPassword and newPassword are equals", ChangePasswordException::PEV_NEXT_PASS_EQ);
        }
        return [
            'oldPassword' => $oldPassword,
            'newPassword' => $newPassword,
        ];
    }

    public function __construct($childProps = []) {
        parent::__construct(
            array_merge([
                'oldPassword' => [
                    SchemaInterface::OPTION_TYPE => SchemaInterface::VALUE_TYPE_STRING,
                    SchemaInterface::OPTION_VALIDATE_FN => [self::class, 'validatePassword'],
                ],
                'newPassword' => [
                    SchemaInterface::OPTION_TYPE => SchemaInterface::VALUE_TYPE_STRING,
                    SchemaInterface::OPTION_VALIDATE_FN => [self::class, 'validatePassword'],
                ],
            ], $childProps), self::class
        );
    }
}