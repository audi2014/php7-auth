<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/20/19
 * Time: 5:06 PM
 */

namespace Audi2014\Auth;


use Throwable;

class AuthException extends \Exception {
    const UI_EMAIL_ALREADY_REGISTERED_NOT_VERIFIED = 1000;
    const UI_EMAIL_ALREADY_REGISTERED_IS_VERIFIED = 1001;
    const UI_ALREADY_EXIST = 1002;
    const UI_OAUTH_EMAIL_EMPTY = 1003;
    const UI_DB_PASSWORD_EMPTY = 1004;
    const UI_404 = 1005;
    const UI_LOGIN_OK_BUT_NOT_VERIFIED = 1006;
    const UI_OAUTH_TOKEN = 1007;
    const UI_WRONG_PASSWORD = 1008;
    const UI_ACCOUNT_NOT_REGISTERED = 1009;
    const UI_TOKEN = 1010;
    const UI_EMAIL_ALREADY_REGISTERED = 1011;


    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}