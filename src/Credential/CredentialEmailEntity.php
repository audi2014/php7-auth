<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 22/08/2018
 * Time: 11:03
 */

namespace Audi2014\Auth\Credential;


use Audi2014\Entity\BaseEntity;

class CredentialEmailEntity extends BaseEntity {



    public $id;
    public $passwordHash;
    public $isEmailVerified;
    public $email;
    public $profileId;
    public $updatedAt = 0;
    public $createdAt = 0;


    public function jsonSerialize() {
        return [
            'id' => self::nullableInt($this->id),
            'isEmailVerified' => self::bool($this->isEmailVerified),
            'passwordHash' => self::bool($this->passwordHash),
            'email' => self::string($this->email),
            'profileId' => self::nullableInt($this->profileId),
            'updatedAt' => self::nullableInt($this->updatedAt),
            'createdAt' => self::nullableInt($this->createdAt),
        ];
    }
}