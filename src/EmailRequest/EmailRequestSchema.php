<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 08.10.2018
 * Time: 9:25 AM
 */

namespace Audi2014\Auth\EmailRequest;



use Audi2014\Auth\ValidateEmailTrait;
use Audi2014\Schema\BaseSchema;
use Audi2014\Schema\SchemaInterface;

class EmailRequestSchema extends BaseSchema {
    use ValidateEmailTrait;

    public function __construct() {
        parent::__construct(
            [
                'email' => [
                    SchemaInterface::OPTION_TYPE => SchemaInterface::VALUE_TYPE_STRING,
                    SchemaInterface::OPTION_VALIDATE_FN => [self::class, 'validateEmail'],
                ],
            ], self::class
        );
    }
}