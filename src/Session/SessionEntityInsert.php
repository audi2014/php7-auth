<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 22/08/2018
 * Time: 11:03
 */

namespace Audi2014\Auth\Session;

use Audi2014\Entity\BaseEntity;

class SessionEntityInsert extends BaseEntity {
    public $id;
    public $profileId;
    public $type;
    public $authToken;
    public $refreshToken;
    public $version;
    public $platform;
    public $pushToken;
    public $expiresIn;
    public $deleteIn;
    public $updatedAt = 0;
    public $createdAt = 0;


    public function jsonSerialize() {
        return [];
    }

}