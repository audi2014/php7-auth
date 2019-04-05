<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/26/19
 * Time: 2:43 PM
 */

namespace Audi2014\Auth\AuthRequest;


use Audi2014\Entity\BaseEntity;

class AuthConfigEntity extends BaseEntity {

    public $version;
    public $platform;
    public $pushToken;
    public $expiresIn;
    public $deleteIn;

}