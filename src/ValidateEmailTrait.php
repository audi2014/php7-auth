<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 27/07/2018
 * Time: 11:48
 */

namespace Audi2014\Auth;


trait ValidateEmailTrait {
    /**
     * @param $value
     * @return string
     * @throws ValidationException
     */
    public static  function validateEmail($value) {
        if($value === null) return null;
        $value = strtolower(trim($value));
        if(empty($value)) {
            throw new ValidationException(
                "invalid email",
                ValidationException::UI_EMAIL_EMPTY
            );
        }
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException(
                "invalid email",
                ValidationException::UI_EMAIL_INVALID
            );
        }
        return $value;
    }
}