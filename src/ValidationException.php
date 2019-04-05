<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/1/19
 * Time: 10:28 AM
 */

namespace Audi2014\Auth;
use Throwable;

class ValidationException extends \Exception {
    const UI_EMAIL_EMPTY = 1000;
    const UI_EMAIL_INVALID = 1001;
    const UI_PASSWORD_EMPTY = 1002;
    const UI_PASSWORD_INVALID = 1003;
    const UI_BAD_DATA = 1004;
    public function __construct(string $message, int $code, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}