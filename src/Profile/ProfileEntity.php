<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 22/08/2018
 * Time: 11:03
 */

namespace Audi2014\Auth\Profile;


use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\Entity\BaseEntity;

class ProfileEntity extends BaseEntity {

    public $id;
    public $emailEmail;
    public $fbEmail;
    public $isEmailVerified;
    public $passwordHash;
    public $updatedAt = 0;
    public $createdAt = 0;

    public function getEmail(): ?string {
        return $this->emailEmail ?? $this->fbEmail;
    }

    public function hasType(string $type): bool {
        if ($type === AuthRequestEntity::TYPE_EMAIL) return $this->emailEmail !== null;
        else if ($type === AuthRequestEntity::TYPE_FB) return $this->fbEmail !== null;
        else return false;
    }

    public function jsonSerialize() {
        return [
            'id' => BaseEntity::nullableInt($this->id),
            'email' => $this->getEmail(),
            'isEmailVerified' => BaseEntity::nullableBool($this->isEmailVerified),
            'updatedAt' => (int)$this->updatedAt,
            'createdAt' => (int)$this->createdAt,
        ];
    }
}