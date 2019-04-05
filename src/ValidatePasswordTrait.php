<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 27/07/2018
 * Time: 11:48
 */

namespace Audi2014\Auth;


trait ValidatePasswordTrait {
    /**
     * @param $value
     * @return string
     * @throws ValidationException
     */
    public static function validatePassword($value) {
        $pattern = '/^(?=.*[A-Za-z])[A-Za-z\d$@!%*#?&.-]{8,}$/';
        if($value === null) return null;
        $value = trim($value);
        if(empty($value)) {
            throw new ValidationException(
                "invalid email",
                ValidationException::UI_PASSWORD_EMPTY
            );
        }
        if (!preg_match(
            $pattern,
            $value
        )) {
            throw new ValidationException(
                "invalid password `$value`",
                ValidationException::UI_PASSWORD_INVALID
            );
        }
        return $value;
    }
}