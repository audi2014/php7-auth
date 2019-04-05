<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 08.10.2018
 * Time: 9:25 AM
 */

namespace Audi2014\Auth\EmailRequest;

use Audi2014\Entity\BaseEntity;

class EmailRequestEntity extends BaseEntity {

    public $email;
    public function jsonSerialize() {
        return [
            'email' => BaseEntity::string($this->email),
        ];
    }
}