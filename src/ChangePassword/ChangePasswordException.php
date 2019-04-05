<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 3/18/19
 * Time: 10:29 AM
 */

namespace Audi2014\Auth\ChangePassword;


use Throwable;

class ChangePasswordException extends \Exception {
    const PEV_NEXT_PASS_EQ = 1;


    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}