<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 22/08/2018
 * Time: 11:03
 */

namespace Audi2014\Auth\Profile;

use Audi2014\Entity\BaseEntity;

class ProfileEntityInsert extends BaseEntity {


    public $id;
    public $updatedAt = 0;
    public $createdAt = 0;

    public function jsonSerialize() {
        return [];
    }
}