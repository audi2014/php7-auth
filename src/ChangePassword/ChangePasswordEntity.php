<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 3/18/19
 * Time: 10:32 AM
 */

namespace Audi2014\Auth\ChangePassword;


use Audi2014\Entity\DataEntity;

class ChangePasswordEntity extends DataEntity {
    public $oldPassword;
    public $newPassword;
}