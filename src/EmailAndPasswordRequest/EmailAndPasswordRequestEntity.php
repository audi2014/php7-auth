<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 08.10.2018
 * Time: 9:25 AM
 */
namespace Audi2014\Auth\EmailAndPasswordRequest;

use Audi2014\Entity\BaseEntity;

class EmailAndPasswordRequestEntity extends BaseEntity {
    public $email;
    public $password;
}