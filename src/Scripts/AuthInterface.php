<?php
/**
 * Created by PhpStorm.
 * User: arturmich
 * Date: 2/21/19
 * Time: 4:28 PM
 */

namespace Audi2014\Auth\Scripts;

use Audi2014\Auth\AuthRequest\AuthRequestEntity;
use Audi2014\Auth\Profile\ProfileEntity;

interface AuthInterface {
    /**
     * @param AuthRequestEntity $authRequestEntity
     * @param ProfileEntity $ProfileEntity
     * @param bool $checkEmailVerified
     */
    function auth(
        AuthRequestEntity $authRequestEntity,
        ProfileEntity &$ProfileEntity,
        bool $checkEmailVerified = false
    ): void;
}