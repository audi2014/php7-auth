<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 22/08/2018
 * Time: 11:03
 */

namespace Audi2014\Auth\Session;

use Audi2014\Entity\BaseEntity;

class SessionEntity extends BaseEntity {

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

    public $scToken;
    public $isNewUser = false;

    public function getEmail(): ?string {
        return $this->emailEmail ?? $this->fbEmail;
    }

    public $emailEmail;
    public $fbEmail;
    public $isEmailVerified;


    const PLATFORM_IOS = 'ios';
    const PLATFORM_IOS_DEV = 'ios_dev';
    const PLATFORM_ANDROID = 'android';
    const PLATFORM_WEB = 'web';
    const PLATFORMS = [
        self::PLATFORM_IOS,
        self::PLATFORM_IOS_DEV,
        self::PLATFORM_ANDROID,
        self::PLATFORM_WEB,
    ];

    public function jsonSerialize() {

        return [
            'id' => parent::nullableInt($this->id),
            'profileId' => parent::nullableInt($this->profileId),
            'type' => parent::string($this->type),
            'authToken' => parent::string($this->authToken),
            'refreshToken' => parent::string($this->refreshToken),
            'version' => parent::string($this->version),
            'platform' => parent::string($this->platform),
            'pushToken' => parent::nullableString($this->pushToken),
            'expiresIn' => parent::int($this->expiresIn),
            'deleteIn' => parent::int($this->deleteIn),
            'updatedAt' => parent::int($this->updatedAt),
            'createdAt' => parent::int($this->createdAt),
            'isEmailVerified' => parent::bool($this->isEmailVerified),
            'isNewUser' => parent::bool($this->isNewUser),
        ];
    }
}