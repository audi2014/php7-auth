<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 22/08/2018
 * Time: 11:03
 */
namespace Audi2014\Auth\AuthRequest;

use Audi2014\Entity\BaseEntity;

class AuthRequestEntity extends BaseEntity {
    public $email;
    public $password;
    public $OAuthToken;
    public $type;
    public $version;
    public $platform;
    public $pushToken;
    public $expiresIn;
    public $deleteIn;

    const TYPE_FB = 'fb';
    const TYPE_EMAIL = 'email';
    const TYPES = [
        self::TYPE_FB,
        self::TYPE_EMAIL,
    ];



}